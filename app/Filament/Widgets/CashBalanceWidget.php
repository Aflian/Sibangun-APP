<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\CashTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashBalanceWidget extends BaseWidget
{
    protected ?string $heading = 'Saldo Kas';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $branches = Branch::all();
        $stats = [];

        foreach ($branches as $branch) {
            $balance = CashTransaction::where('branch_id', $branch->id)
                ->whereIn('type', ['income', 'expense'])
                ->get()
                ->reduce(function ($carry, $item) {
                    return $carry + ($item->type === 'income' ? $item->amount : -$item->amount);
                }, 0);

            $stats[] = Stat::make($branch->name, 'Rp '.number_format($balance, 0, ',', '.'))
                ->description('Saldo kas cabang')
                ->color($balance >= 0 ? 'success' : 'danger');
        }

        $totalBalance = CashTransaction::whereIn('type', ['income', 'expense'])
            ->get()
            ->reduce(function ($carry, $item) {
                return $carry + ($item->type === 'income' ? $item->amount : -$item->amount);
            }, 0);

        $stats[] = Stat::make('Total Kas', 'Rp '.number_format($totalBalance, 0, ',', '.'))
            ->description('Seluruh cabang')
            ->color($totalBalance >= 0 ? 'success' : 'danger');

        return $stats;
    }
}
