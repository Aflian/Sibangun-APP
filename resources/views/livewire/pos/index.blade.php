<div class="bg-[#f4f7fb] text-on-surface antialiased select-none min-h-screen flex flex-col justify-between">
<header class="fixed top-0 left-0 w-full z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="w-full flex flex-col">
            <div class="h-16 px-4 xl:px-6 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white shadow">
                            <span class="material-symbols-outlined text-[24px]">apartment</span>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2"><span
                                    class="font-extrabold text-[17px] text-primary tracking-tight leading-none">Sibangun</span><span
                                    class="text-[10px] font-bold bg-amber-500 text-white px-1.5 py-0.5 rounded tracking-wide">CBG-01
                                    PEKANBARU</span></div><span
                                class="text-[11px] text-slate-500 font-medium leading-tight mt-0.5">Sistem Bahan
                                Bangunan, Grosir &amp; Logistik Armada</span>
                        </div>
                    </div>
                    <div class="h-7 w-[1px] bg-slate-200 mx-1 hidden lg:block"></div>
                    <div class="hidden lg:flex items-center gap-2.5">
                        <div
                            class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200/80">
                            <span
                                class="material-symbols-outlined text-emerald-600 text-[18px]">account_balance_wallet</span>
                            <div class="flex flex-col"><span
                                    class="text-[10px] text-slate-500 font-semibold uppercase leading-tight">Saldo Kas
                                    Cabang</span><span
                                    class="text-[12px] font-bold text-slate-900 font-mono-num leading-tight">Rp
                                    {{ number_format($this->cashBalance, 0, ',', '.') }}</span></div>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200/80">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <div class="flex flex-col"><span
                                    class="text-[11px] font-bold text-emerald-700 leading-tight">Scanner
                                    Online</span><span class="text-[10px] text-slate-500">USB HID Ready</span></div>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200/80">
                            <span class="material-symbols-outlined text-slate-700 text-[18px]">schedule</span>
                            <div class="flex flex-col"><span
                                    class="text-[11px] font-bold text-slate-800 leading-tight">Shift Pagi</span><span
                                    class="text-[10px] text-slate-500">07:30 - 16:00 WIB</span></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div
                        class="hidden xl:flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                        <div class="flex items-center gap-1 font-semibold text-slate-700"><span
                                class="bg-white px-1.5 py-0.5 rounded shadow-sm text-slate-900 font-bold border border-slate-200">F1</span>
                            Cari</div><span class="text-slate-300">|</span>
                        <div class="flex items-center gap-1 font-semibold text-slate-700"><span
                                class="bg-white px-1.5 py-0.5 rounded shadow-sm text-slate-900 font-bold border border-slate-200">F2</span>
                            Scan</div><span class="text-slate-300">|</span>
                        <div class="flex items-center gap-1 font-semibold text-slate-700"><span
                                class="bg-white px-1.5 py-0.5 rounded shadow-sm text-slate-900 font-bold border border-slate-200">F4</span>
                            Hold</div><span class="text-slate-300">|</span>
                        <div class="flex items-center gap-1 font-bold text-amber-700"><span
                                class="bg-amber-600 text-white px-1.5 py-0.5 rounded shadow-sm">F9</span> Bayar</div>
                    </div>
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <div class="flex items-center gap-2.5 text-right">
                            <div class="flex flex-col"><span
                                    class="text-[13px] font-bold text-slate-900 leading-tight">{{ auth()->user()->name ?? 'Kasir Utama' }}</span><span
                                    class="text-[11px] text-slate-500 font-medium">{{ ucfirst(auth()->user()->role ?? 'kasir') }} • {{ $this->selectedBranchId && isset($this->branches[$this->selectedBranchId]) ? $this->branches[$this->selectedBranchId] : 'Semua Cabang' }}</span></div>
                            <div
                                class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">person</span></div>
                        </div><button
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 hover:bg-red-600 hover:text-white rounded-lg transition-colors font-semibold text-xs shadow-sm"
                            title="Tutup Shift &amp; Logout"
                            wire:click="logout"><span
                                class="material-symbols-outlined text-[16px]">logout</span><span>Tutup
                                Shift</span></button>
                    </div>
                </div>
            </div>
            <div
                            class="h-10 px-4 xl:px-6 flex items-center justify-between bg-slate-50 border-b border-slate-200/70 text-xs">
                    <nav class="flex items-center gap-1 h-full">
                        <button
                            wire:click="activeTab = 'kasir'"
                            class="h-full flex items-center px-4 font-bold text-slate-900 {{ $activeTab === 'kasir' ? 'bg-white border-b-2 border-amber-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors' }}">
                            Kasir / Transaksi
                        </button>
                        <button
                            wire:click="activeTab = 'antrean'; showHoldModal = true"
                            class="h-full flex items-center px-4 text-slate-600 font-medium hover:text-slate-900 hover:bg-slate-100 transition-colors">
                            Daftar Antrean & Draft Bon
                        </button>
                        <button
                            wire:click="activeTab = 'suratjalan'; showDeliveryModal = true"
                            class="h-full flex items-center px-4 text-slate-600 font-medium hover:text-slate-900 hover:bg-slate-100 transition-colors">
                            Surat Jalan / Armada Toko
                        </button>
                        <button
                            wire:click="activeTab = 'stok'"
                            class="h-full flex items-center px-4 font-bold text-slate-900 {{ $activeTab === 'stok' ? 'bg-white border-b-2 border-amber-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors' }}">
                            Cek Stok Multi-Gudang
                        </button>
                        <button
                            wire:click="activeTab = 'laporan'"
                            class="h-full flex items-center px-4 font-bold text-slate-900 {{ $activeTab === 'laporan' ? 'bg-white border-b-2 border-amber-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors' }}">
                            Riwayat Penjualan & Kas Shift
                        </button>
                    </nav>
                            <div class="flex items-center gap-3 font-medium">
                                <div
                                    class="flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded font-semibold">
                                    <span class="material-symbols-outlined text-[15px]">cloud_done</span><span>Database Pusat
                                        Terhubung</span></span><span class="text-slate-300">•</span><span
                                    class="font-bold text-slate-800 font-mono-num">{{ now()->format('H:i:s') }} WIB</span>
                                </div>
                            </div>
                        </div>
        </div>
    </header>
    <main class="w-full pt-[108px] pb-3 px-3 xl:px-6 max-w-[1920px] mx-auto flex-1">
        @if ($activeTab === 'kasir')
        <div class="grid grid-cols-12 gap-4 items-start w-full">
            <!-- ================= KOLOM KIRI: KATALOG MATERIAL & FILTER (~60%) ================= -->
            <div class="col-span-12 xl:col-span-7 flex flex-col gap-3.5">
                <!-- Bar Pencarian & Shortcut Barcode -->
                <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-sm flex flex-col gap-3">
                    <div class="flex flex-col sm:flex-row items-center gap-2.5">
                        <div
                            class="relative flex-1 w-full flex items-center bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all">
                            <span
                                class="material-symbols-outlined text-amber-600 text-[22px] mr-2 shrink-0">barcode_scanner</span>
                            <input autofocus=""
                                wire:model.live.debounce.300ms="search"
                                class="w-full bg-transparent text-slate-900 placeholder:text-slate-400 text-sm font-medium focus:outline-none"
                                id="barcodeSearchInput"
                                placeholder="Scan Barcode [F2] atau cari nama material (Semen, Besi, Cat, Pipa...)"
                                type="text" />
                            <div class="flex items-center gap-1.5 ml-2 shrink-0">
                                <span
                                    class="text-[11px] font-mono-num font-bold bg-slate-200 text-slate-800 px-2 py-0.5 rounded border border-slate-300">F1</span>
                                <button
                                    class="px-2 py-1 bg-slate-800 text-white rounded text-xs font-semibold flex items-center gap-1 hover:bg-slate-700 transition-colors"
                                    title="Deteksi Scanner Barcode">
                                    <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                                    <span class="hidden sm:inline">Scan F2</span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-between sm:justify-start">
                            <div
                                class="flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 px-2.5 py-2 rounded-lg">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                <span class="text-xs font-semibold text-emerald-800">Auto-Focus Aktif</span>
                            </div>
                            <button
                                class="flex items-center gap-1.5 px-3 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-200 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">tune</span>
                                <span>Filter Rak</span>
                            </button>
                        </div>
                    </div>
                    <!-- Tab Kategori Material -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs font-semibold scrollbar-none">
                        <button
                            wire:click="selectCategory(null)"
                            class="{{ $selectedCategory === null ? 'px-3.5 py-1.5 bg-primary text-white rounded-lg shadow-sm shrink-0 whitespace-nowrap' : 'px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg shrink-0 whitespace-nowrap transition-colors' }}">Semua
                            Produk</button>
                        @foreach ($categories as $category)
                            <button
                                wire:click="selectCategory({{$category['id']}})"
                                class="{{ $selectedCategory == $category['id'] ? 'px-3.5 py-1.5 bg-primary text-white rounded-lg shadow-sm shrink-0 whitespace-nowrap' : 'px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg shrink-0 whitespace-nowrap transition-colors' }}">{{ $category['name'] }}</button>
                        @endforeach
                    </div>
                </div>
                <!-- Grid Produk 3 Kolom Responsif -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[calc(100vh-275px)] pr-1">
                    @foreach ($products as $product)
                        <div
                            wire:click="addToCart({{$product['id']}})"
                            class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm hover:shadow-md hover:border-amber-500 transition-all flex flex-col justify-between group cursor-pointer">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-[10px] font-mono-num font-bold bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border border-slate-200">SKU:
                                        {{$product['code']}}</span>
                                    <span
                                        class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded border border-emerald-200">{{$this->selectedBranchId ? $this->branches[$this->selectedBranchId] : 'Pusat'}}:
                                        {{$product['stock']}} {{$product['unit']}}</span>
                                </div>
                                <div class="w-full h-32 rounded-lg bg-slate-100 overflow-hidden mb-2 relative">
                                    <img alt="{{$product['name']}}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOrqbXrvrapMEb0HB7NX67ykmSe0LON-m8ubrZm7mbb7V1jBsQOBvfsR7ZWGDi7U5covbOzuavdW0Ib8Vbqe42lOhK0itjvido9zNdiMr2RlnkmpflsMr2ttyW5Wo4vS7-IStrKzBYe9cSbdSKT2LQbDkLK5V5vXHdA86IclwUCTHdwBYbygr4BZGNuCdacXjs8sjYD4p-KvQLKS04SQ7KFFGFKPdm9-JzDV1YqETuC_KZf0DeKXfvHw" />
                                </div>
                                <h3 class="font-bold text-[13px] text-slate-900 leading-snug line-clamp-1">{{$product['name']}}</h3>
                                <p class="text-[11px] text-slate-500 line-clamp-1">{{$product['description']}}</p>
                            </div>
                            <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Eceran / Grosir</span>
                                    <span class="font-bold text-slate-900 text-[14px] font-mono-num">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                                </div>
                                <button
                                    class="px-2.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg flex items-center gap-1 text-xs font-bold transition-all shadow-sm active:scale-95">
                                    <span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
                                    <span>+ {{$product['unit']}}</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if (empty($products))
                        <div class="col-span-full text-center text-slate-500 py-10">Produk tidak ditemukan.</div>
                    @endif
                </div>
            </div>
            <!-- ================= KOLOM KANAN: FAKTUR, LIMIT KREDIT & CHECKOUT (~40%) ================= -->
            <div
                class="col-span-12 xl:col-span-5 flex flex-col bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Header Faktur & Pelanggan VIP + Limit Kredit -->
                <div class="p-3.5 bg-slate-50 border-b border-slate-200 flex flex-col gap-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span
                                class="text-slate-900 font-extrabold text-[17px] font-mono-num">#INV-{{ now()->format('YmdHis') }}-{{ rand(100, 999) }}</span>
                            <span
                                class="text-[10px] bg-amber-100 text-amber-800 border border-amber-300 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Bon
                                Aktif</span>
                        </div>
                        <div class="flex items-center gap-1 text-slate-500 text-xs font-medium">
                            <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                            <span class="font-mono-num">{{ now()->format('d M Y') }} • {{ now()->format('H:i') }}</span>
                        </div>
                    </div>
                    <!-- Dropdown / Selector Pelanggan & Logistik Armada -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <!-- Selector Pelanggan -->
                        <div class="bg-white p-2.5 rounded-lg border border-slate-200 shadow-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Pelanggan [F5]</span>
                                @if ($this->selectedCustomerId && $this->selectedCustomerObject)
                                    <span class="text-[10px] bg-amber-600 text-white px-1.5 py-0.2 rounded font-bold">{{ $this->selectedCustomerObject->name }} ({{ $this->selectedCustomerObject->code }})</span>
                                @else
                                    <span class="text-[10px] bg-slate-400 text-white px-1.5 py-0.2 rounded font-bold">Umum / Non-member</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="truncate">
                                    <select wire:model.live="selectedCustomerId"
                                            class="block w-full rounded-lg border-slate-300">
                                        <option value="">Umum / Non-member</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{$customer['id']}}">{{ $customer['name'] }} ({{ $customer['code'] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="p-1 hover:bg-slate-100 rounded text-slate-600 ml-1 shrink-0"><span class="material-symbols-outlined text-[18px]">expand_more</span></button>
                            </div>
                        </div>
                        <!-- Armada Pengiriman -->
                        <div class="bg-white p-2.5 rounded-lg border border-slate-200 shadow-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Logistik
                                    Armada</span>
                                <span
                                    class="text-[10px] bg-emerald-50 text-emerald-700 px-1.5 py-0.2 rounded font-bold border border-emerald-200">Siap
                                    Muat</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="material-symbols-outlined text-amber-600 text-[20px] shrink-0">local_shipping</span>
                                <div class="truncate">
                                    <span class="font-bold text-[12px] text-slate-900 truncate block">Colt Diesel
                                        BM-9142-TB</span>
                                    <span class="text-[10px] text-slate-500">Driver: Pak Wardi (Toko)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ringkasan Limit Kredit & Piutang Sesuai PRD -->
                    <div
                        class="bg-white p-2.5 rounded-lg border border-slate-200/90 grid grid-cols-3 gap-2 text-center shadow-xs">
                        <div class="flex flex-col border-r border-slate-100 pr-1">
                            <span class="text-[10px] text-slate-400 font-semibold uppercase">Plafon Limit</span>
                            <span class="text-[12px] font-bold text-slate-900 font-mono-num">Rp {{ number_format($this->selectedCustomerObject->credit_limit ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-col border-r border-slate-100 pr-1">
                            <span class="text-[10px] text-slate-400 font-semibold uppercase">Total Piutang</span>
                            <span class="text-[12px] font-bold text-amber-700 font-mono-num">Rp {{ number_format($this->customerReceivablesTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-emerald-600 font-bold uppercase">Sisa Limit (Aman)</span>
                            <span class="text-[12px] font-bold text-emerald-700 font-mono-num">Rp {{ number_format($this->customerCreditRemaining, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Keranjang Belanjaan (Cart Items) -->
                <div class="p-3 overflow-y-auto max-h-[260px] space-y-2 bg-slate-50/40">
                    @forelse ($cart as $id => $item)
                        <div class="bg-white p-2.5 rounded-lg border border-slate-200 flex flex-col gap-1.5 hover:border-slate-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0 pr-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] font-bold bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-mono-num">{{ $item['code'] }}</span>
                                        <h4 class="font-bold text-[12px] text-slate-900 truncate">{{ $item['name'] }}</h4>
                                        <span class="text-[9px] bg-amber-50 text-amber-800 border border-amber-200 px-1 rounded font-bold shrink-0">{{ $item['unit'] === 'Sak' ? 'Grosir' : 'Eceran' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 mt-1 text-[11px] text-slate-500">
                                        <span>@ Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</span>
                                        <span>Satuan: <strong class="text-slate-800">{{ $item['unit'] }}</strong></span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="font-bold text-[13px] text-slate-900 font-mono-num">Rp {{ number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                                <div class="flex items-center gap-1 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">
                                    <button wire:click="updateQuantity({{$id}}, {{$item['quantity'] - 1}})" class="w-5 h-5 flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 rounded"><span class="material-symbols-outlined text-[14px]">remove</span></button>
                                    <input wire:change="updateQuantity({{$id}}, $event.target.value)" class="w-8 text-center font-bold text-xs text-slate-900 bg-transparent focus:outline-none font-mono-num" type="text" value="{{ $item['quantity'] }}" />
                                    <button wire:click="updateQuantity({{$id}}, {{$item['quantity'] + 1}})" class="w-5 h-5 flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 rounded"><span class="material-symbols-outlined text-[14px]">add</span></button>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button class="text-slate-400 hover:text-slate-700 p-0.5" title="Catatan Barang"><span class="material-symbols-outlined text-[16px]">edit_note</span></button>
                                    <button wire:click="removeFromCart({{$id}})" class="text-red-500 hover:bg-red-50 p-0.5 rounded" title="Hapus"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-slate-500 py-10">Keranjang kosong.</div>
                    @endforelse
                </div>
                <!-- Rincian Biaya & GRAND TOTAL -->
                <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex flex-col gap-2">
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Material ({{ count($cart) }} Produk, {{ (array_sum(array_column($cart, 'quantity'))) }} Qty)</span>
                            <span class="font-bold text-slate-900 font-mono-num">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span class="flex items-center gap-1.5">
                                <span>Ongkir / Bongkar Muat</span>
                                <span
                                    class="text-emerald-700 bg-emerald-50 px-1 py-0.2 rounded text-[10px] font-bold">Gratis
                                    &lt;5km</span>
                            </span>
                            <span class="font-bold text-emerald-700 font-mono-num">Rp {{ number_format(0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Diskon Proyek Kontraktor (2%)</span>
                            <span class="font-bold text-amber-700 font-mono-num">- Rp {{ number_format($this->discountAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>PPN 11% (Faktur Pajak Standar)</span>
                            <span class="font-bold text-slate-800 font-mono-num">Rp {{ number_format($this->taxAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <!-- Banner GRAND TOTAL Kontras Tinggi -->
                    <div
                        class="bg-slate-900 text-white p-3 rounded-xl flex items-center justify-between shadow-md my-0.5 border border-slate-800">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">TOTAL
                                TAGIHAN BERSIH</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-[14px] text-amber-400 font-bold">Rp</span>
                                <span
                                    class="text-[24px] font-extrabold tracking-tight font-mono-num text-white">{{ number_format($this->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-[11px] bg-slate-800 border border-slate-700 px-2 py-0.5 rounded font-bold text-amber-300 block">Termasuk
                                PPN 11%</span>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">Status: {{ $this->canCheckout ? 'Valid' : 'Pembayaran Kurang' }}</span>
                        </div>
                    </div>
                    <!-- Pilihan Metode Pembayaran (Tunai, Transfer, QRIS, Debit, Kredit/Tempo) -->
                    <div class="flex flex-col gap-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Metode
                            Pembayaran:</span>
                        <div class="grid grid-cols-5 gap-1.5">
                            <button
                                wire:click="setPaymentMethod('cash')"
                                class="{{ $this->paymentMethod === 'cash' ? 'py-2 px-1 bg-amber-600 text-white rounded-lg flex flex-col items-center justify-center gap-0.5 font-bold shadow-xs' : 'py-2 px-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg flex flex-col items-center justify-center gap-0.5 font-semibold border border-slate-200 transition-colors' }}">
                                <span class="material-symbols-outlined text-[17px]">payments</span>
                                <span class="text-[10px]">Tunai</span>
                            </button>
                            <button
                                wire:click="setPaymentMethod('transfer')"
                                class="{{ $this->paymentMethod === 'transfer' ? 'py-2 px-1 bg-amber-600 text-white rounded-lg flex flex-col items-center justify-center gap-0.5 font-bold shadow-xs' : 'py-2 px-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg flex flex-col items-center justify-center gap-0.5 font-semibold border border-slate-200 transition-colors' }}">
                                <span class="material-symbols-outlined text-[17px]">account_balance</span>
                                <span class="text-[10px]">Transfer</span>
                            </button>
                            <button
                                wire:click="setPaymentMethod('qris')"
                                class="{{ $this->paymentMethod === 'qris' ? 'py-2 px-1 bg-amber-600 text-white rounded-lg flex flex-col items-center justify-center gap-0.5 font-bold shadow-xs' : 'py-2 px-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg flex flex-col items-center justify-center gap-0.5 font-semibold border border-slate-200 transition-colors' }}">
                                <span class="material-symbols-outlined text-[17px]">qr_code_2</span>
                                <span class="text-[10px]">QRIS</span>
                            </button>
                            <button
                                wire:click="setPaymentMethod('debit')"
                                class="{{ $this->paymentMethod === 'debit' ? 'py-2 px-1 bg-amber-600 text-white rounded-lg flex flex-col items-center justify-center gap-0.5 font-bold shadow-xs' : 'py-2 px-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg flex flex-col items-center justify-center gap-0.5 font-semibold border border-slate-200 transition-colors' }}">
                                <span class="material-symbols-outlined text-[17px]">credit_card</span>
                                <span class="text-[10px]">Debit</span>
                            </button>
                            <button
                                wire:click="setPaymentMethod('tempo')"
                                class="{{ $this->paymentMethod === 'tempo' ? 'py-2 px-1 bg-white hover:bg-slate-100 text-amber-700 rounded-lg flex flex-col items-center justify-center gap-0.5 font-bold border border-amber-200 transition-colors' : 'py-2 px-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg flex flex-col items-center justify-center gap-0.5 font-semibold border border-slate-200 transition-colors' }}">
                                <span class="material-symbols-outlined text-[17px]">schedule_send</span>
                                <span class="text-[10px]">Tempo</span>
                            </button>
                        </div>
                    </div>
                    <!-- Tombol Aksi Kasir -->
                    <div class="flex flex-col gap-2 pt-1">
                        <button
                            wire:click="placeOrder"
                            class="w-full py-3 px-4 bg-amber-600 hover:bg-amber-700 active:scale-[0.99] text-white rounded-xl text-[14px] font-extrabold flex items-center justify-center gap-2 shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            @if(!$this->canCheckout) disabled @endif>
                            <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                            <span>BAYAR &amp; CETAK STRUK</span>
                            <span
                                class="text-[11px] bg-amber-800 text-amber-100 px-2 py-0.5 rounded font-mono-num font-bold">[F9]</span>
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                wire:click="holdCart"
                                class="py-2 px-3 bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-xs">
                                <span class="material-symbols-outlined text-[16px] text-amber-600">pause_circle</span>
                                <span>Simpan / Hold [F4]</span>
                            </button>
                            <button
                                wire:click="showDeliveryModal = true"
                                class="py-2 px-3 bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-xs">
                                <span class="material-symbols-outlined text-[16px] text-blue-600">local_shipping</span>
                                <span>Surat Jalan Pengiriman</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="fixed top-24 right-6 z-[60] bg-emerald-600 text-white px-4 py-2.5 rounded-lg shadow-lg text-sm font-semibold"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="fixed top-24 right-6 z-[60] bg-red-600 text-white px-4 py-2.5 rounded-lg shadow-lg text-sm font-semibold"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition>
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="fixed top-24 right-6 z-[60] bg-amber-600 text-white px-4 py-2.5 rounded-lg shadow-lg text-sm font-semibold"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition>
            {{ session('warning') }}
        </div>
    @endif

    @if ($showHoldModal)
        <div class="fixed inset-0 z-[70] bg-slate-900/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900">Daftar Antrean &amp; Draft Bon</h3>
                    <button wire:click="$set('showHoldModal', false)" class="text-slate-500 hover:text-slate-900">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-3 overflow-y-auto space-y-2">
                    @forelse ($this->heldSales as $held)
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <span class="font-mono-num font-bold text-slate-900 text-sm">{{ $held->invoice_number }}</span>
                                <div class="text-[11px] text-slate-600 mt-0.5">
                                    {{ $held->customer->name ?? 'Umum / Non-member' }} •
                                    {{ $held->items->count() }} item •
                                    Rp {{ number_format($held->total, 0, ',', '.') }} •
                                    {{ $held->transaction_date?->format('d M Y H:i') }}
                                </div>
                            </div>
                            <button wire:click="loadHeldSale({{ $held->id }})"
                                    class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold">
                                Lanjutkan
                            </button>
                        </div>
                    @empty
                        <div class="text-center text-slate-500 py-8 text-sm">Tidak ada draft bon tertahan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    @if ($showDeliveryModal)
        <div class="fixed inset-0 z-[70] bg-slate-900/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg flex flex-col">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900">Buat Surat Jalan Pengiriman</h3>
                    <button wire:click="$set('showDeliveryModal', false)" class="text-slate-500 hover:text-slate-900">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Nama Driver</label>
                        <input wire:model="driverName" type="text" placeholder="Contoh: Pak Wardi"
                               class="w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Nomor Kendaraan</label>
                        <input wire:model="vehicleNumber" type="text" placeholder="Contoh: BM-9142-TB"
                               class="w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Alamat Tujuan</label>
                        <textarea wire:model="deliveryAddress" rows="3" placeholder="Alamat proyek / pelanggan"
                                  class="w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    <p class="text-[11px] text-slate-500">{{ count($cart) }} item akan dikirim sesuai keranjang aktif.</p>
                </div>
                <div class="px-4 py-3 border-t border-slate-200 flex justify-end gap-2">
                    <button wire:click="$set('showDeliveryModal', false)"
                            class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700">
                        Batal
                    </button>
                    <button wire:click="createDeliveryOrder"
                            class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold">
                        Simpan Surat Jalan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <footer
        class="w-full h-11 bg-white border-t border-slate-200 px-4 xl:px-6 flex items-center justify-between text-xs text-slate-600 mt-2">
        <div class="flex items-center gap-3 overflow-x-auto"><span
                class="font-semibold text-slate-800 whitespace-nowrap">Panduan Tombol Kasir:</span>
            <div class="flex items-center gap-1.5"><span
                    class="bg-slate-100 border border-slate-200 font-bold px-1.5 py-0.5 rounded text-[11px]">F1</span><span
                    class="text-slate-500">Cari</span></div>
            <div class="flex items-center gap-1.5"><span
                    class="bg-slate-100 border border-slate-200 font-bold px-1.5 py-0.5 rounded text-[11px]">F2</span><span
                    class="text-slate-500">Scan</span></div>
            <div class="flex items-center gap-1.5"><span
                    class="bg-slate-100 border border-slate-200 font-bold px-1.5 py-0.5 rounded text-[11px]">F4</span><span
                    class="text-slate-500">Hold</span></div>
            <div class="flex items-center gap-1.5"><span
                    class="bg-slate-100 border border-slate-200 font-bold px-1.5 py-0.5 rounded text-[11px]">F5</span><span
                    class="text-slate-500">Customer</span></div>
            <div class="flex items-center gap-1.5"><span
                    class="bg-amber-100 text-amber-900 border border-amber-300 font-bold px-1.5 py-0.5 rounded text-[11px]">F9</span><span
                    class="font-semibold text-amber-900">Bayar</span></div>
        </div>
        <div class="flex items-center gap-3 shrink-0"><span
                class="hidden md:inline font-mono-num bg-slate-100 px-2 py-0.5 rounded border border-slate-200 text-slate-700">Server:
                CBG-PKU-01 (Online)</span><span class="font-medium text-slate-500">Mitra Bangunan POS v4.2</span></div>
    </footer>
    <script>
        // Keyboard Shortcut Listeners
        document.addEventListener('keydown', function(event) {
            if (event.key === 'F1' || event.key === 'F2') {
                event.preventDefault();
                const input = document.getElementById('barcodeSearchInput');
                if (input) {
                    input.focus();
                    input.select();
                }
            }
        });
    </script>
</div>
