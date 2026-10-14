<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Quick Range Buttons -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="flex flex-wrap gap-2 mb-4">
                <button wire:click="setTodayRange"
                    class="px-3 py-1.5 text-sm font-medium rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Hari Ini
                </button>
                <button wire:click="setThisWeekRange"
                    class="px-3 py-1.5 text-sm font-medium rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Minggu Ini
                </button>
                <button wire:click="setThisMonthRange"
                    class="px-3 py-1.5 text-sm font-medium rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Bulan Ini
                </button>
                <button wire:click="setThisYearRange"
                    class="px-3 py-1.5 text-sm font-medium rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Tahun Ini
                </button>
                <button wire:click="setCustomRange"
                    class="px-3 py-1.5 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Custom
                </button>
            </div>

            {{ $this->form }}
        </div>

        @if ($reportData ?? false)
            <!-- Summary Table -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Ringkasan Keuangan
                </h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                                Indikator
                            </th>
                            <th class="text-right py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                                Nilai (IDR)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Total Penjualan
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($reportData['totalSales'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Pembayaran Diterima
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($reportData['totalSalesPaid'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Transaksi Penjualan
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900 dark:text-white">
                                {{ $reportData['salesCount'] }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Total Pembelian
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($reportData['totalPurchases'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Pembelian Dibayar
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($reportData['totalPurchasesPaid'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Transaksi Pembelian
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900 dark:text-white">
                                {{ $reportData['purchasesCount'] }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Pemasukan Kas
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($reportData['cashIncome'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-gray-900 dark:text-white">
                                Pengeluaran Kas/Operasional
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-red-600 dark:text-red-400">
                                Rp {{ number_format($reportData['cashExpense'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                                Estimasi Laba/Rugi Bersih
                            </td>
                            <td class="py-3 px-4 text-right font-bold {{ $reportData['netProfit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                Rp {{ number_format($reportData['netProfit'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Summary Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Total Penjualan
                    </div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($reportData['totalSales'], 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Diterima: Rp {{ number_format($reportData['totalSalesPaid'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Total Pembelian
                    </div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($reportData['totalPurchases'], 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Dibayar: Rp {{ number_format($reportData['totalPurchasesPaid'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Estimasi Laba/Rugi
                    </div>
                    <div class="mt-2 text-2xl font-bold {{ $reportData['netProfit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp {{ number_format($reportData['netProfit'], 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Omset - HPP - Kas Keluar + Kas Masuk
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
