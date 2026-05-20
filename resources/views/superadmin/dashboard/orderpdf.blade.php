<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Sanna Space</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; line-height: 1.4; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #1e40af; margin: 0; }
        .subtitle { font-size: 14px; color: #64748b; margin: 5px 0 0 0; }
        .filter-info { margin-bottom: 20px; font-size: 11px; color: #475569; }
        table { w-full; border-collapse: collapse; margin-top: 10px; }
        th { bg-color: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 10px; padding: 12px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; font-size: 11px; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .summary-box { margin-top: 30px; border-top: 2px solid #e2e8f0; padding-top: 15px; text-align: right; }
        .total-label { font-size: 12px; color: #64748b; text-transform: uppercase; }
        .total-amount { font-size: 18px; font-weight: bold; color: #0f172a; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <p class="logo-text">SANNA SPACE</p>
        <p class="subtitle">Laporan Data Riwayat Pesanan Berhasil</p>
    </div>

    <div class="filter-info">
        <strong>Rentang Laporan:</strong> 
        @if($startDate && $endDate)
            {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        @else
            Semua Riwayat Penjualan Terdata
        @endif
        <br>
        <strong>Waktu Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 15%;">ID Pesanan</th>
                <th style="width: 35%;">Pelanggan</th>
                <th style="width: 25%;">Tanggal Selesai</th>
                <th style="width: 25%; text-align: right;">Total Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="bold">#{{ $order->id }}</td>
                    <td>
                        <span class="bold">{{ $order->user ? $order->user->name : 'Guest User' }}</span><br>
                        <span style="font-size: 10px; color: #64748b;">{{ $order->user ? $order->user->email : '-' }}</span>
                    </td>
                    <td>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                    <td class="text-right bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8; font-style: italic;">
                        Tidak ada data penjualan pada kriteria filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->count() > 0)
        <div class="summary-box">
            <span class="total-label">Total Pendapatan Bersih Terfilter ({{ $orders->count() }} Pesanan)</span>
            <div class="total-amount">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
        </div>
    @endif

</body>
</html>