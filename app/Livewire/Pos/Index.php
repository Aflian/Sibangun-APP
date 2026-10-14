<?php

namespace App\Livewire\Pos;

use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\CashTransaction;
use App\Models\Category;
use App\Models\Customer;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Receivable;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    public $selectedCategory = null;

    public $selectedCustomerId = null;

    public $paymentMethod = 'cash';

    public $amountReceived = 0;

    public $discount = 0;

    public $taxRate = 0; // %

    public $cart = [];

    public $products = [];

    public $categories = [];

    public $customers = [];

    public $branches = [];

    public $selectedBranchId = null;

    public $cashBalance = 0;

    public $isDeliveryOrder = false;

    public $driverName = '';

    public $vehicleNumber = '';

    public $deliveryAddress = '';

    public $showHoldModal = false;

    public $showDeliveryModal = false;

    public $lastSaleId = null;

    public $activeTab = 'kasir';

    public $stockSearch = '';

    public $showFilterRackModal = false;

    public $salesPage = 1;

    public $salesPerPage = 20;

    public $cashTransactionsPage = 1;

    public $cashTransactionsPerPage = 20;

    public $stockPage = 1;

    public $stockPerPage = 20;

    public $selectedSaleId = null;

    public $selectedTransactionId = null;

    public function mount()
    {
        $this->categories = Category::where('status', 'active')->get(['id', 'name'])->toArray();
        $this->branches = Branch::where('status', 'active')->pluck('name', 'id')->toArray();

        if (Auth::check()) {
            if (Auth::user()->branch_id) {
                $this->selectedBranchId = Auth::user()->branch_id;
            } else {
                $first = array_keys($this->branches)[0] ?? null;
                $this->selectedBranchId = $first;
            }
        }

        $this->loadCustomers();
        $this->loadProducts();
        $this->loadCashBalance();
    }

    public function loadCustomers()
    {
        $this->customers = Customer::where('status', 'active')
            ->get(['id', 'name', 'code', 'credit_limit'])
            ->toArray();
    }

    public function loadCashBalance(): void
    {
        if (! $this->selectedBranchId) {
            $this->cashBalance = 0;

            return;
        }

        // ponytail: signed sum over all cash types. Widening the type set when
        // transfer/adjustment gain a sign convention in the ledger.
        $this->cashBalance = (float) CashTransaction::where('branch_id', $this->selectedBranchId)
            ->selectRaw('COALESCE(SUM(CASE WHEN type IN (?, ?, ?) THEN amount ELSE -amount END), 0) as balance', ['income', 'sale', 'receivable_payment'])
            ->value('balance');
    }

    public function loadProducts()
    {
        $query = Product::where('status', 'active')
            ->with(['category', 'unit'])
            ->when($this->selectedCategory, fn ($q) => $q->where('category_id', $this->selectedCategory))
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%")
                    ->orWhere('barcode', 'like', "%{$this->search}%");
            })
            ->limit(100);

        $this->products = $query->get()->map(function ($p) {
            $stock = 0;
            if ($this->selectedBranchId) {
                $bs = $p->branchStocks->where('branch_id', $this->selectedBranchId)->first();
                $stock = $bs ? $bs->quantity : 0;
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'code' => $p->code,
                'barcode' => $p->barcode,
                'category' => optional($p->category)->name ?? 'Umum',
                'unit' => optional($p->unit)->symbol ?? 'Pcs',
                'price' => $p->selling_price,
                'stock' => $stock,
                'description' => $p->description,
            ];
        })->toArray();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function updatedSelectedCategory()
    {
        $this->loadProducts();
    }

    public function updatedSelectedBranchId()
    {
        $this->loadProducts();
        $this->loadCashBalance();
    }

    public function selectCategory($id)
    {
        $this->selectedCategory = $id;
        $this->loadProducts();
    }

    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::find($productId);
        if (! $product) {
            session()->flash('error', 'Produk tidak ditemukan.');

            return;
        }

        $stock = 0;
        $bs = $product->branchStocks->where('branch_id', $this->selectedBranchId)->first();
        if ($bs) {
            $stock = $bs->quantity;
        }

        $currentQty = $this->cart[$productId]['quantity'] ?? 0;
        if ($currentQty + $quantity > $stock) {
            session()->flash('warning', "Stok {$product->name} tidak mencukupi (sisa {$stock}).");

            return;
        }

        if (! isset($this->cart[$productId])) {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'unit' => optional($product->unit)->symbol ?? 'Pcs',
                'unit_price' => $product->selling_price,
                'quantity' => 0,
                'discount' => 0,
            ];
        }

        $this->cart[$productId]['quantity'] += $quantity;
    }

    public function updateQuantity($productId, $quantity)
    {
        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $stock = 0;
        $bs = $product->branchStocks->where('branch_id', $this->selectedBranchId)->first();
        if ($bs) {
            $stock = $bs->quantity;
        }

        $qty = (int) $quantity;
        if ($qty > $stock) {
            session()->flash('warning', "Stok {$product->name} tidak mencukupi (sisa {$stock}).");
            if (isset($this->cart[$productId])) {
                $this->cart[$productId]['quantity'] = min($this->cart[$productId]['quantity'], $stock);
            }

            return;
        }

        if ($qty <= 0) {
            unset($this->cart[$productId]);
        } else {
            $this->cart[$productId]['quantity'] = $qty;
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function holdCart()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja masih kosong.');

            return;
        }

        DB::transaction(function () {
            $sale = Sale::create([
                'branch_id' => $this->selectedBranchId,
                'customer_id' => $this->selectedCustomerId,
                'user_id' => Auth::id(),
                'invoice_number' => 'HOLD-'.now()->format('YmdHis').'-'.rand(100, 999),
                'transaction_date' => now(),
                'subtotal' => $this->subtotal,
                'discount' => $this->discountAmount,
                'tax' => $this->taxAmount,
                'total' => $this->total,
                'paid_amount' => 0,
                'status' => 'draft',
                'note' => 'Bon Tertahan / Hold',
            ]);

            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'],
                    'subtotal' => ($item['unit_price'] * $item['quantity']) * (1 - $item['discount'] / 100),
                ]);
            }
        });

        session()->flash('success', 'Transaksi berhasil disimpan sebagai draft (Hold)!');
        $this->resetAfterSale();
    }

    public function loadHeldSale($saleId)
    {
        $sale = Sale::where('branch_id', $this->selectedBranchId)
            ->where('status', 'draft')
            ->with('items.product')
            ->find($saleId);

        if (! $sale) {
            session()->flash('error', 'Draft transaksi tidak ditemukan.');

            return;
        }

        $this->cart = [];
        foreach ($sale->items as $item) {
            $this->cart[$item->product_id] = [
                'product_id' => $item->product_id,
                'name' => $item->product->name ?? 'Barang',
                'code' => $item->product->code ?? '',
                'unit' => optional($item->product->unit)->symbol ?? 'Pcs',
                'unit_price' => $item->unit_price,
                'quantity' => (float) $item->quantity,
                'discount' => (float) $item->discount,
            ];
        }

        $this->selectedCustomerId = $sale->customer_id;
        $sale->delete(); // Remove draft after loaded back into active cart
        $this->showHoldModal = false;
        session()->flash('success', 'Draft transaksi berhasil dimuat ke keranjang.');
    }

    public function createDeliveryOrder()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja masih kosong.');

            return;
        }

        DB::transaction(function () {
            $do = DeliveryOrder::create([
                'branch_id' => $this->selectedBranchId,
                'customer_id' => $this->selectedCustomerId,
                'delivery_number' => 'SJ-'.now()->format('YmdHis').'-'.rand(100, 999),
                'delivery_date' => now()->toDateString(),
                'destination_address' => $this->deliveryAddress ?: 'Alamat Toko / Pelanggan',
                'driver_name' => $this->driverName ?: 'Driver Toko',
                'vehicle_number' => $this->vehicleNumber ?: 'BM-0000-XX',
                'status' => 'prepared',
                'note' => 'Surat Jalan POS',
                'created_by' => Auth::id(),
            ]);

            foreach ($this->cart as $item) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $do->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'note' => "POS Order SJ {$do->delivery_number}",
                ]);
            }
        });

        session()->flash('success', 'Surat Jalan Pengiriman berhasil dibuat!');
        $this->showDeliveryModal = false;
    }

    public function getHeldSalesProperty()
    {
        if (! $this->selectedBranchId) {
            return collect();
        }

        return Sale::where('branch_id', $this->selectedBranchId)
            ->where('status', 'draft')
            ->with(['customer', 'items'])
            ->latest()
            ->get();
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
    }

    public function getDiscountAmountProperty()
    {
        $itemDiscount = collect($this->cart)->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'] * ($item['discount'] / 100);
        });

        return $itemDiscount + $this->discount;
    }

    public function getTaxAmountProperty()
    {
        return ($this->subtotal - $this->discountAmount) * ($this->taxRate / 100);
    }

    public function getTotalProperty()
    {
        return max(0, $this->subtotal - $this->discountAmount + $this->taxAmount);
    }

    public function getChangeProperty()
    {
        return max(0, $this->amountReceived - $this->total);
    }

    public function getSelectedCustomerObjectProperty()
    {
        if (! $this->selectedCustomerId) {
            return null;
        }

        return Customer::find($this->selectedCustomerId);
    }

    public function getCustomerReceivablesTotalProperty()
    {
        if (! $this->selectedCustomerId) {
            return 0;
        }

        return Receivable::where('customer_id', $this->selectedCustomerId)
            ->whereIn('status', ['unpaid', 'partial', 'due_today', 'overdue'])
            ->sum('remaining_amount');
    }

    public function getCustomerCreditRemainingProperty()
    {
        $cust = $this->selectedCustomerObject;
        if (! $cust) {
            return 0;
        }

        return max(0, $cust->credit_limit - $this->customerReceivablesTotal);
    }

    public function getCanCheckoutProperty()
    {
        if (empty($this->cart)) {
            return false;
        }

        if ($this->paymentMethod === 'tempo') {
            return $this->selectedCustomerId && $this->customerCreditRemaining >= $this->total;
        }

        return $this->amountReceived >= $this->total;
    }

    public function placeOrder()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja masih kosong.');

            return;
        }

        if ($this->paymentMethod === 'tempo' && ! $this->selectedCustomerId) {
            session()->flash('error', 'Pembayaran tempo wajib memilih Pelanggan.');

            return;
        }

        if ($this->paymentMethod === 'tempo' && $this->customerCreditRemaining < $this->total) {
            session()->flash('error', 'Sisa limit kredit pelanggan tidak mencukupi untuk pembayaran tempo.');

            return;
        }

        if ($this->paymentMethod !== 'tempo' && $this->amountReceived < $this->total) {
            session()->flash('error', 'Jumlah uang diterima kurang dari total tagihan.');

            return;
        }

        DB::transaction(function () {
            $paid = $this->paymentMethod === 'tempo' ? 0 : $this->amountReceived;

            // 1. Create Sale
            $sale = Sale::create([
                'branch_id' => $this->selectedBranchId,
                'customer_id' => $this->selectedCustomerId,
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-'.now()->format('YmdHis').'-'.rand(100, 999),
                'transaction_date' => now(),
                'subtotal' => $this->subtotal,
                'discount' => $this->discountAmount,
                'tax' => $this->taxAmount,
                'total' => $this->total,
                'paid_amount' => min($paid, $this->total),
                'status' => 'completed',
            ]);

            // 2. Sale Items & Stock Mutations
            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);
                $qty = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $itemDiscount = $item['discount'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'discount' => $itemDiscount,
                    'subtotal' => ($unitPrice * $qty) * (1 - $itemDiscount / 100),
                ]);

                // Decrease stock
                $branchStock = BranchStock::where('branch_id', $this->selectedBranchId)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if ($branchStock) {
                    $before = $branchStock->quantity;
                    $branchStock->decrement('quantity', $qty);
                    $after = $branchStock->quantity;

                    StockMovement::create([
                        'branch_id' => $this->selectedBranchId,
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'reference_type' => 'Sale',
                        'reference_id' => $sale->id,
                        'type' => 'sale',
                        'quantity' => $qty,
                        'stock_before' => $before,
                        'stock_after' => $after,
                        'note' => "Penjualan invoice {$sale->invoice_number}",
                    ]);
                }
            }

            // 3. Payment
            if ($paid > 0) {
                $payment = Payment::create([
                    'sale_id' => $sale->id,
                    'branch_id' => $this->selectedBranchId,
                    'payment_method' => $this->paymentMethod === 'tempo' ? 'cash' : $this->paymentMethod,
                    'amount' => min($paid, $this->total),
                    'payment_date' => now(),
                    'reference_number' => 'TRX-'.now()->format('His'),
                    'created_by' => Auth::id(),
                ]);

                CashTransaction::create([
                    'branch_id' => $this->selectedBranchId,
                    'user_id' => Auth::id(),
                    'type' => 'sale',
                    'reference_type' => 'Payment',
                    'reference_id' => $payment->id,
                    'payment_method' => $this->paymentMethod === 'tempo' ? 'cash' : $this->paymentMethod,
                    'amount' => min($paid, $this->total),
                    'transaction_date' => now(),
                    'description' => "Penjualan invoice {$sale->invoice_number}",
                ]);
            }

            // 4. Receivable if tempo or underpaid
            $due = $this->total - $paid;
            if ($due > 0 && $this->selectedCustomerId) {
                $dueDate = now()->addDays(14)->toDateString();

                Receivable::create([
                    'branch_id' => $this->selectedBranchId,
                    'customer_id' => $this->selectedCustomerId,
                    'sale_id' => $sale->id,
                    'due_date' => $dueDate,
                    'original_amount' => $this->total,
                    'paid_amount' => max(0, $paid),
                    'remaining_amount' => $due,
                    'status' => 'unpaid',
                ]);
            }
        });

        session()->flash('success', 'Transaksi penjualan berhasil diproses!');
        $this->resetAfterSale();
    }

    public function getSalesHistoryProperty()
    {
        if (! $this->selectedBranchId) {
            return collect();
        }

        return Sale::where('branch_id', $this->selectedBranchId)
            ->where('status', 'completed')
            ->with(['customer', 'items', 'payments'])
            ->latest('transaction_date')
            ->paginate($this->salesPerPage, ['*'], 'salesPage', $this->salesPage);
    }

    public function getCashTransactionsHistoryProperty()
    {
        if (! $this->selectedBranchId) {
            return collect();
        }

        return CashTransaction::where('branch_id', $this->selectedBranchId)
            ->with(['user'])
            ->latest('transaction_date')
            ->paginate($this->cashTransactionsPerPage, ['*'], 'cashTransactionsPage', $this->cashTransactionsPage);
    }

    public function getBranchStocksProperty()
    {
        if (! $this->selectedBranchId) {
            return collect();
        }

        return BranchStock::where('branch_id', $this->selectedBranchId)
            ->where('quantity', '>', 0)
            ->with(['product.category', 'product.unit'])
            ->when($this->stockSearch, function ($q) {
                $q->whereHas('product', function ($sub) {
                    $sub->where('name', 'like', "%{$this->stockSearch}%")
                        ->orWhere('code', 'like', "%{$this->stockSearch}%")
                        ->orWhere('barcode', 'like', "%{$this->stockSearch}%");
                });
            })
            ->orderBy('quantity', 'asc')
            ->paginate($this->stockPerPage, ['*'], 'stockPage', $this->stockPage);
    }

    public function getSaleDetailProperty()
    {
        if (! $this->selectedSaleId) {
            return null;
        }

        return Sale::where('branch_id', $this->selectedBranchId)
            ->with(['customer', 'items.product.unit', 'payments'])
            ->find($this->selectedSaleId);
    }

    public function getTransactionDetailProperty()
    {
        if (! $this->selectedTransactionId) {
            return null;
        }

        return CashTransaction::where('branch_id', $this->selectedBranchId)
            ->with(['user'])
            ->find($this->selectedTransactionId);
    }

    public function selectSaleDetail($saleId)
    {
        $this->selectedSaleId = $saleId;
    }

    public function selectTransactionDetail($transactionId)
    {
        $this->selectedTransactionId = $transactionId;
    }

    public function closeDetailModals()
    {
        $this->selectedSaleId = null;
        $this->selectedTransactionId = null;
    }

    public function resetAfterSale()
    {
        $this->cart = [];
        $this->search = '';
        $this->selectedCategory = null;
        $this->selectedCustomerId = null;
        $this->paymentMethod = 'cash';
        $this->amountReceived = 0;
        $this->discount = 0;
        $this->taxRate = 0;
        $this->loadProducts();
        $this->loadCashBalance();
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function render()
    {
        return view('livewire.pos.index');
    }
}
