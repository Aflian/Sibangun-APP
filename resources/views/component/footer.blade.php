<footer class="bg-primary text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[24px]">apartment</span>
                        </div>
                        <div>
                            <div class="font-bold text-lg">MITRA BANGUNAN</div>
                            <div class="text-xs text-gray-400">Toko Bahan Bangunan</div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Pusat bahan bangunan terlengkap di Pekanbaru. Melayani kontraktor, pemborong, dan kebutuhan
                        renovasi rumah.
                    </p>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Kontak</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[20px]">call</span>
                            <span>(0811) 758-8990</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[20px]">mail</span>
                            <span>info@mitrabangunan.com</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[20px]">location_on</span>
                            <span>Jl. HR Soebrantas No. 88, Pekanbaru</span>
                        </div>
                    </div>
                </div>

                <!-- Jam Operasional -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Jam Operasional</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Senin - Sabtu</span>
                            <span class="font-semibold font-mono">07.30 - 17.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Minggu</span>
                            <span class="font-semibold font-mono">08.00 - 14.00</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <span class="text-gray-400 text-xs">Pengiriman setiap hari kerja</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
                <div>© {{ date('Y') }} Mitra Bangunan. All rights reserved.</div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('pos.index') }}" class="hover:text-secondary transition-colors">POS System</a>
                        <span class="text-white/20">|</span>
                    @endauth
                    <a href="/admin/login" class="hover:text-secondary transition-colors">Admin Login</a>
                </div>
            </div>
        </div>
    </footer>