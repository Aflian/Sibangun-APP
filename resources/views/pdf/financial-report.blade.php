<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Sibangun</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; }
        h1 { text-align: center; font-size: 16pt; margin-bottom: 5px; }
        h2 { text-align: center; font-size: 12pt; margin-top: 0; color: #666; }
        .period { text-align: center; font-size: 10pt; margin: 10px 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .number { text-align: right; }
        .footer { margin-top: 30px; font-size: 9pt; color: #666; text-align: center; }
    </style>
</head>
<body>
    <h1>LAPORAN KEUANGAN & RINGKASAN KAS</h1>
    @if($branchName)
        <h2>{{ $branchName }}</h2>
    @else
        <h2>Semua Cabang</h2>
    @endif
    <div class="period">Periode: {{ $startDate }} s/d {{ $endDate }}</div>

    <table>
        <thead>
            <tr>
                <th>Indikator Keuangan</th>
                <th class="number">Nilai (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Omset Penjualan</td>
                <td class="number">Rp {{ number_format($data['totalSales'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pembayaran Penjualan Diterima</td>
                <td class="number">Rp {{ number_format($data['totalSalesPaid'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jumlah Transaksi Penjualan</td>
                <td class="number">{{ $data['salesCount'] }} transaksi</td>
            </tr>
            <tr>
                <td>Total Pembelian Stok (HPP)</td>
                <td class="number">Rp {{ number_format($data['totalPurchases'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pembelian Stok Dibayar</td>
                <td class="number">Rp {{ number_format($data['totalPurchasesPaid'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jumlah Transaksi Pembelian</td>
                <td class="number">{{ $data['purchasesCount'] }} transaksi</td>
            </tr>
            <tr>
                <td>Pemasukan Kas Lainnya</td>
                <td class="number">Rp {{ number_format($data['cashIncome'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pengeluaran Kas/Operasional</td>
                <td class="number">Rp {{ number_format($data['cashExpense'], 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td>Estimasi Laba/Rugi Bersih Tentatif</td>
                <td class="number">Rp {{ number_format($data['netProfit'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }}<br>
        Sistem Manajemen Toko Bahan Bangunan - Sibangun
    </div>
</body>
</html>
