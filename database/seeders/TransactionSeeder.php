<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\CashTransaction;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Receivable;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $users = User::all();
        $products = Product::all();
        $customers = Customer::all();
        $vendors = Vendor::all();

        if ($branches->isEmpty() || $users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Purchase transactions (last 30 days)
        for ($i = 0; $i < 8; $i++) {
            $branch = $branches->random();
            $vendor = $vendors->random();
            $user = $users->where('branch_id', $branch->id)->first() ?? $users->first();
            $purchaseDate = now()->subDays(rand(1, 30))->setHour(rand(8, 16));

            $purchase = Purchase::create([
                'branch_id' => $branch->id,
                'vendor_id' => $vendor->id,
                'user_id' => $user->id,
                'purchase_number' => 'PO-'.date('Ymd').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'purchase_date' => $purchaseDate,
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'paid_amount' => 0,
                'payment_method' => ['cash', 'transfer'][rand(0, 1)],
                'status' => 'completed',
                'note' => 'Pembelian stok reguler',
            ]);

            $itemCount = rand(2, 4);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $qty = rand(10, 50);
                $itemSubtotal = $qty * $product->purchase_price;
                $subtotal += $itemSubtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->purchase_price,
                    'subtotal' => $itemSubtotal,
                ]);

                // Update stock
                $branchStock = BranchStock::firstOrCreate(
                    ['branch_id' => $branch->id, 'product_id' => $product->id],
                    ['quantity' => 0, 'reserved_quantity' => 0]
                );
                $oldQty = $branchStock->quantity;
                $branchStock->quantity += $qty;
                $branchStock->save();

                StockMovement::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'type' => 'purchase',
                    'quantity' => $qty,
                    'stock_before' => $oldQty,
                    'stock_after' => $branchStock->quantity,
                    'note' => 'Purchase in',
                    'created_at' => $purchaseDate,
                ]);
            }

            $discount = rand(0, 1) ? rand(5000, 50000) : 0;
            $tax = 0;
            $total = $subtotal - $discount + $tax;
            $paidAmount = rand(0, 1) ? $total : $total * 0.5;

            $purchase->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
            ]);
        }

        // Sales transactions (last 30 days)
        for ($i = 0; $i < 15; $i++) {
            $branch = $branches->random();
            $customer = rand(0, 1) ? $customers->random() : null;
            $user = $users->where('branch_id', $branch->id)->first() ?? $users->first();
            $saleDate = now()->subDays(rand(1, 30))->setHour(rand(8, 18));

            $sale = Sale::create([
                'branch_id' => $branch->id,
                'customer_id' => $customer?->id,
                'user_id' => $user->id,
                'invoice_number' => 'INV-'.date('Ymd').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'transaction_date' => $saleDate,
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'paid_amount' => 0,
                'status' => 'completed',
                'note' => null,
            ]);

            $itemCount = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $branchStock = BranchStock::where('branch_id', $branch->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (! $branchStock || $branchStock->quantity < 1) {
                    continue;
                }

                $maxQty = min($branchStock->quantity, 20);
                $qty = rand(1, $maxQty);
                $itemDiscount = 0;
                $itemSubtotal = ($qty * $product->selling_price) - $itemDiscount;
                $subtotal += $itemSubtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'discount' => $itemDiscount,
                    'subtotal' => $itemSubtotal,
                ]);

                $oldQty = $branchStock->quantity;
                $branchStock->quantity -= $qty;
                $branchStock->save();

                StockMovement::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'type' => 'sale',
                    'quantity' => -$qty,
                    'stock_before' => $oldQty,
                    'stock_after' => $branchStock->quantity,
                    'note' => 'Sale out',
                    'created_at' => $saleDate,
                ]);
            }

            if ($subtotal == 0) {
                $sale->delete();

                continue;
            }

            $discount = rand(0, 1) ? rand(5000, 25000) : 0;
            $tax = 0;
            $total = $subtotal - $discount + $tax;
            $paidAmount = rand(0, 2) == 0 ? $total * 0.5 : $total;

            $sale->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
            ]);

            // Create payment
            $paymentMethod = ['cash', 'transfer', 'qris', 'debit'][rand(0, 3)];
            Payment::create([
                'sale_id' => $sale->id,
                'branch_id' => $branch->id,
                'payment_method' => $paymentMethod,
                'amount' => $paidAmount,
                'payment_date' => $saleDate,
                'reference_number' => $paymentMethod == 'cash' ? null : 'REF-'.rand(100000, 999999),
                'note' => null,
                'created_by' => $user->id,
                'created_at' => $saleDate,
            ]);

            // Create receivable if not fully paid
            if ($paidAmount < $total && $customer) {
                Receivable::create([
                    'branch_id' => $branch->id,
                    'customer_id' => $customer->id,
                    'sale_id' => $sale->id,
                    'due_date' => $saleDate->copy()->addDays(30),
                    'original_amount' => $total - $paidAmount,
                    'paid_amount' => 0,
                    'remaining_amount' => $total - $paidAmount,
                    'status' => 'unpaid',
                ]);
            }
        }

        // Cash transactions (misc income/expense)
        for ($i = 0; $i < 10; $i++) {
            $branch = $branches->random();
            $user = $users->where('branch_id', $branch->id)->first() ?? $users->first();
            $type = ['income', 'expense'][rand(0, 1)];
            $date = now()->subDays(rand(1, 30))->setHour(rand(8, 17));

            $descriptions = [
                'income' => ['Pendapatan lain-lain', 'Penjualan scrap', 'Denda keterlambatan'],
                'expense' => ['Biaya listrik', 'Gaji karyawan', 'Biaya transportasi', 'Biaya maintenance'],
            ];

            CashTransaction::create([
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'type' => $type,
                'reference_type' => null,
                'reference_id' => null,
                'payment_method' => ['cash', 'transfer'][rand(0, 1)],
                'amount' => rand(100000, 5000000),
                'transaction_date' => $date,
                'description' => $descriptions[$type][rand(0, count($descriptions[$type]) - 1)],
                'created_at' => $date,
            ]);
        }
    }
}
