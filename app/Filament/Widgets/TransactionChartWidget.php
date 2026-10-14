<?php

namespace App\Filament\Widgets;

use App\Models\CashTransaction;
use App\Models\Purchase;
use App\Models\Sale;
use Filament\Widgets\ChartWidget;

class TransactionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tren Transaksi';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public ?string $filter = 'monthly';

    protected function getFilters(): ?array
    {
        return [
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
        ];
    }

    protected function getData(): array
    {
        $endDate = now();
        $startDate = match ($this->filter) {
            'weekly' => $endDate->copy()->subWeeks(4),
            default => $endDate->copy()->subMonths(11),
        };

        $labels = [];
        $salesData = [];
        $purchasesData = [];
        $cashFlowData = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            if ($this->filter === 'monthly') {
                $labels[] = $currentDate->format('M Y');
            } else {
                $labels[] = 'Minggu '.$currentDate->weekOfYear;
            }

            $salesData[] = Sale::whereBetween('transaction_date', [$currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth()])
                ->sum('total');

            $purchasesData[] = Purchase::whereBetween('purchase_date', [$currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth()])
                ->sum('total');

            $cashFlowData[] = CashTransaction::whereBetween('transaction_date', [$currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth()])
                ->where('type', 'income')
                ->sum('amount') - CashTransaction::whereBetween('transaction_date', [$currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth()])
                ->where('type', 'expense')
                ->sum('amount');

            if ($this->filter === 'monthly') {
                $currentDate->addMonth();
            } else {
                $currentDate->addWeek();
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Penjualan',
                    'data' => $salesData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => false,
                ],
                [
                    'label' => 'Pembelian',
                    'data' => $purchasesData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'fill' => false,
                ],
                [
                    'label' => 'Arus Kas',
                    'data' => $cashFlowData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'fill' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
