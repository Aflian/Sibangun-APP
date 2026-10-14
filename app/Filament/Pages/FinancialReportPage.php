<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\CashTransaction;
use App\Models\Purchase;
use App\Models\Sale;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinancialReportPage extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.financial-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $title = 'Laporan Keuangan & Kas';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->endOfMonth()->toDateString(),
            'branch_id' => null,
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->live()
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->live()
                    ->required(),
                Select::make('branch_id')
                    ->label('Cabang')
                    ->options(Branch::pluck('name', 'id'))
                    ->live()
                    ->placeholder('Semua Cabang'),
            ])
            ->statePath('data')
            ->columns(3);
    }

    public function setTodayRange(): void
    {
        $this->data['start_date'] = now()->startOfDay()->toDateString();
        $this->data['end_date'] = now()->endOfDay()->toDateString();
    }

    public function setThisWeekRange(): void
    {
        $this->data['start_date'] = now()->startOfWeek()->toDateString();
        $this->data['end_date'] = now()->endOfWeek()->toDateString();
    }

    public function setThisMonthRange(): void
    {
        $this->data['start_date'] = now()->startOfMonth()->toDateString();
        $this->data['end_date'] = now()->endOfMonth()->toDateString();
    }

    public function setThisYearRange(): void
    {
        $this->data['start_date'] = now()->startOfYear()->toDateString();
        $this->data['end_date'] = now()->endOfYear()->toDateString();
    }

    public function setCustomRange(): void
    {
        // No action needed - user will pick dates manually
    }

    /**
     * @return array{reportData: array<string, int|float>, branches: array<int, string>}
     */
    protected function getViewData(): array
    {
        return [
            'reportData' => $this->getReportData(),
            'branches' => Branch::pluck('name', 'id')->all(),
        ];
    }

    public function getReportData(): array
    {
        $startDate = isset($this->data['start_date']) ? Carbon::parse($this->data['start_date'])->startOfDay() : now()->startOfMonth();
        $endDate = isset($this->data['end_date']) ? Carbon::parse($this->data['end_date'])->endOfDay() : now()->endOfMonth();
        $branchId = $this->data['branch_id'] ?? null;

        $salesQuery = Sale::query()
            ->whereBetween('transaction_date', [$startDate, $endDate]);
        if ($branchId) {
            $salesQuery->where('branch_id', $branchId);
        }

        $totalSales = $salesQuery->sum('total');
        $totalSalesPaid = $salesQuery->sum('paid_amount');
        $salesCount = $salesQuery->count();

        $purchasesQuery = Purchase::query()
            ->whereBetween('purchase_date', [$startDate, $endDate]);
        if ($branchId) {
            $purchasesQuery->where('branch_id', $branchId);
        }

        $totalPurchases = $purchasesQuery->sum('total');
        $totalPurchasesPaid = $purchasesQuery->sum('paid_amount');
        $purchasesCount = $purchasesQuery->count();

        $cashIncomeQuery = CashTransaction::query()
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate]);
        if ($branchId) {
            $cashIncomeQuery->where('branch_id', $branchId);
        }
        $cashIncome = $cashIncomeQuery->sum('amount');

        $cashExpenseQuery = CashTransaction::query()
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate]);
        if ($branchId) {
            $cashExpenseQuery->where('branch_id', $branchId);
        }
        $cashExpense = $cashExpenseQuery->sum('amount');

        $netProfit = $totalSales - $totalPurchases - $cashExpense + $cashIncome;

        return [
            'totalSales' => $totalSales,
            'totalSalesPaid' => $totalSalesPaid,
            'salesCount' => $salesCount,
            'totalPurchases' => $totalPurchases,
            'totalPurchasesPaid' => $totalPurchasesPaid,
            'purchasesCount' => $purchasesCount,
            'cashIncome' => $cashIncome,
            'cashExpense' => $cashExpense,
            'netProfit' => $netProfit,
        ];
    }

    public function getTableRows(): array
    {
        $reportData = $this->getReportData();

        return [
            [
                'indikator' => 'Total Penjualan',
                'nilai' => $reportData['totalSales'],
            ],
            [
                'indikator' => 'Total Pembelian',
                'nilai' => $reportData['totalPurchases'],
            ],
            [
                'indikator' => 'Laba/Rugi Est.',
                'nilai' => $reportData['netProfit'],
            ],
            [
                'indikator' => 'Transaksi Penjualan',
                'nilai' => $reportData['salesCount'],
            ],
            [
                'indikator' => 'Transaksi Pembelian',
                'nilai' => $reportData['purchasesCount'],
            ],
            [
                'indikator' => 'Pemasukan Kas (Lain-lain)',
                'nilai' => $reportData['cashIncome'],
            ],
            [
                'indikator' => 'Pengeluaran Kas/Operasional',
                'nilai' => $reportData['cashExpense'],
            ],
        ];
    }

    public function getTable(): Table
    {
        return Table::make()
            ->columns([
                TextColumn::make('indikator')
                    ->label('Indikator'),
                TextColumn::make('nilai')
                    ->label('Nilai (IDR)')
                    ->formatStateUsing(fn (mixed $state): string => 'Rp '.number_format((float) $state, 0, ',', '.')),
            ])
            ->rows($this->getTableRows());
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_csv')
                ->label('Export CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(fn () => $this->exportCsv()),
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->action(fn () => $this->exportPdf()),
        ];
    }

    public function exportPdf(): StreamedResponse
    {
        $reportData = $this->getReportData();
        $startDate = $this->data['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth()->toDateString();
        $branchId = $this->data['branch_id'] ?? null;

        $filename = "Laporan_Keuangan_{$startDate}_sd_{$endDate}.pdf";

        $pdf = Pdf::loadView('pdf.financial-report', [
            'data' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchId ? Branch::find($branchId)?->name : null,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function exportCsv(): StreamedResponse
    {
        $reportData = $this->getReportData();
        $startDate = $this->data['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth()->toDateString();

        $filename = "Laporan_Keuangan_{$startDate}_sd_{$endDate}.csv";

        return response()->streamDownload(function () use ($reportData, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['LAPORAN KEUANGAN & RINGKASAN KAS']);
            fputcsv($handle, ['Periode', "{$startDate} s/d {$endDate}"]);
            fputcsv($handle, []);

            fputcsv($handle, ['Indikator Keuangan', 'Nilai (IDR)']);
            fputcsv($handle, ['Total Omset Penjualan', 'Rp '.number_format($reportData['totalSales'], 0, ',', '.')]);
            fputcsv($handle, ['Total Pembayaran Penjualan Diterima', 'Rp '.number_format($reportData['totalSalesPaid'], 0, ',', '.')]);
            fputcsv($handle, ['Jumlah Transaksi Penjualan', $reportData['salesCount']]);
            fputcsv($handle, ['Total Pembelian Stok (HPP)', 'Rp '.number_format($reportData['totalPurchases'], 0, ',', '.')]);
            fputcsv($handle, ['Total Pembelian Stok Dibayar', 'Rp '.number_format($reportData['totalPurchasesPaid'], 0, ',', '.')]);
            fputcsv($handle, ['Jumlah Transaksi Pembelian', $reportData['purchasesCount']]);
            fputcsv($handle, ['Pemasukan Kas Lainnya', 'Rp '.number_format($reportData['cashIncome'], 0, ',', '.')]);
            fputcsv($handle, ['Pengeluaran Kas/Operasional', 'Rp '.number_format($reportData['cashExpense'], 0, ',', '.')]);
            fputcsv($handle, ['Estimasi Laba/Rugi Bersih Tentatif', 'Rp '.number_format($reportData['netProfit'], 0, ',', '.')]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
