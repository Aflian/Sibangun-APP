<?php

namespace App\Filament\Widgets;

use App\Models\CashTransaction;
use App\Models\Purchase;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Ringkasan Bisnis';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $monthSales = Sale::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('total');

        $monthPurchases = Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');

        $cashIncome = CashTransaction::where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $cashExpense = CashTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $netProfit = $monthSales - $monthPurchases + $cashIncome - $cashExpense;

        return [
            Stat::make('Omset Bulan Ini', 'Rp '.number_format($monthSales, 0, ',', '.'))
                ->description('Total penjualan')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Pembelian Bulan Ini', 'Rp '.number_format($monthPurchases, 0, ',', '.'))
                ->description('HPP & pengadaan')
                ->icon('heroicon-o-truck')
                ->color('warning'),

            Stat::make('Arus Kas', 'Rp '.number_format($cashIncome - $cashExpense, 0, ',', '.'))
                ->description('Masuk - Keluar')
                ->icon('heroicon-o-wallet')
                ->color($cashIncome >= $cashExpense ? 'success' : 'danger'),

            Stat::make('Laba/Rugi Est.', 'Rp '.number_format($netProfit, 0, ',', '.'))
                ->description('Perkiraan bulan ini')
                ->icon('heroicon-o-chart-bar')
                ->color($netProfit >= 0 ? 'success' : 'danger'),
        ];
    }
}
