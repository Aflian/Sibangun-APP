 <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-[28px]">apartment</span>
                    </div>
                    <div>
                        <div class="font-bold text-xl text-primary tracking-tight">Sibangun</div>
                        <div class="text-xs text-secondary-dark font-semibold tracking-wide">TOKO BAHAN BANGUNAN
                            TERLENGKAP</div>
                    </div>
                </div>

                <!-- Nav Desktop -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-medium">
                    <a href="#produk" class="text-gray-700 hover:text-primary transition-colors">Produk</a>
                    <a href="#kategori" class="text-gray-700 hover:text-primary transition-colors">Kategori</a>
                    <a href="#keunggulan" class="text-gray-700 hover:text-primary transition-colors">Keunggulan</a>
                    <a href="#cabang" class="text-gray-700 hover:text-primary transition-colors">Cabang</a>
                    <a href="#kontak" class="text-gray-700 hover:text-primary transition-colors">Kontak</a>
                </nav>

                <!-- CTA -->
                <div class="flex items-center gap-3">
                    <a href="https://wa.me/628117588990"
                        class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 transition-all shadow-md">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        <span>Chat Sales</span>
                    </a>
                    @auth
                        <a href="{{ route('pos.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-secondary text-white rounded-lg font-semibold text-sm hover:bg-secondary-dark transition-all shadow-md">
                            <span class="material-symbols-outlined text-[20px]">point_of_sale</span>
                            <span>POS</span>
                        </a>
                    @else
                        <a href="/admin/login"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg font-semibold text-sm hover:bg-primary-light transition-all shadow-md">
                            <span class="material-symbols-outlined text-[20px]">login</span>
                            <span class="hidden sm:inline">Admin</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
