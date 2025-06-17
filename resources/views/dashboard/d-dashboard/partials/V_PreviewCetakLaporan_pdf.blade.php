<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judulLaporan }}</title>
    <style>
        /* CSS Native untuk meniru Tailwind CSS */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #374151; /* gray-700 */
        }
        .container {
            padding: 1.5rem;
        }
        .header {
            display: block;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb; /* gray-200 */
            padding-bottom: 1rem;
        }
        .header-title {
            float: left;
            width: 70%;
        }
        .header-logo {
            float: right;
            width: 30%;
            text-align: right;
        }
        .header-logo img {
            height: 40px;
            display: inline-block;
            margin-left: 8px;
        }
        .clear {
            clear: both;
        }
        h2 {
            font-size: 1.875rem; /* text-3xl */
            font-weight: bold;
            color: #1f2937; /* gray-800 */
            margin: 0;
        }
        h3 {
            font-size: 1.25rem; /* text-xl */
            font-weight: bold;
            color: #374151; /* gray-700 */
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 0.875rem; /* text-sm */
            color: #6b7280; /* gray-500 */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #e5e7eb; /* gray-200 */
            padding: 4px; /* p-3 */
            text-align: left;
        }
        thead th {
            background-color: #f3f4f6 !important; /* bg-gray-100 */
        }
        .summary-table table, .summary-table th, .summary-table td {
            border: none;
        }
        .summary-table {
            background-color: #f9fafb; /* bg-gray-50 */
            padding: 1rem;
            border-radius: 0.5rem;
            max-width: 500px;
        }
        .summary-row {
            display: block;
            padding: 0.5rem 0;
        }
        .summary-label {
            font-weight: 600; /* font-semibold */
            color: #4b5563; /* gray-600 */
            float: left;
            width: 60%;
        }
        .summary-value {
            float: right;
            width: 40%;
            text-align: left;
        }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h2>{{ $judulLaporan }}</h2>
                <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} oleh {{ Auth::user()->name }}</p>
            </div>
            <div class="header-logo">
                <img src="{{ public_path('images/logos/roundLogo.png') }}" alt="Logo">
                <img src="{{ public_path('images/logos/homeLogo.png') }}" alt="Logo Text" style="padding-bottom: 10px;">
            </div>
            <div class="clear"></div>
        </div>

        <div class="summary-table">
            <h3 style="margin: 0;">Ringkasan Pendapatan</h3>
            <div class="summary-row">
                <span class="summary-label">Total Pendapatan Kotor (Termasuk Ongkir)</span>
                <span class="summary-value">: Rp. {{ number_format($pendapatanKotor, 0, ',', '.') }}</span>
                 <div class="clear"></div>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Pendapatan Bersih (Profit Lelang)</span>
                <span class="summary-value">: Rp. {{ number_format($pendapatanBersih, 0, ',', '.') }}</span>
                 <div class="clear"></div>
            </div>
        </div>

        <h3>Daftar Transaksi Berhasil</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Tanggal</th><th>Kode Transaksi</th><th>Nama Lelang</th><th>Pemenang</th><th class="text-right">Harga Bid</th><th class="text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $index => $transaksi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y') }}</td>
                        <td class="font-mono">{{ $transaksi->order_id }}</td>
                        <td>{{ $transaksi->lelang?->nama_produk_lelang ?? 'N/A' }}</td>
                        <td>{{ $transaksi->lelang?->pemenang?->user?->name ?? 'N/A' }}</td>
                        <td class="text-right font-mono">Rp. {{ number_format($transaksi->pasangLelang?->harga_pengajuan ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right font-mono">Rp. {{ number_format($transaksi->gross_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center; padding: 1rem;">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h3>Daftar Lelang pada Periode Ini</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Tanggal Tutup</th><th>Nama Lelang</th><th class="text-right">Harga Dibuka</th><th class="text-right">Harga Tertinggi</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lelangs as $index => $lelang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($lelang->tanggal_ditutup)->format('d M Y') }}</td>
                        <td>{{ $lelang->nama_produk_lelang }}</td>
                        <td class="text-right font-mono">Rp. {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</td>
                        @php
                            $hargaTertinggi = $lelang->pasangLelang->isNotEmpty() ? $lelang->pasangLelang->max('harga_pengajuan') : 0;
                        @endphp
                        <td class="text-right font-mono">{{ $hargaTertinggi > 0 ? 'Rp. ' . number_format($hargaTertinggi, 0, ',', '.') : '-' }}</td>
                        <td>{{ $lelang->deleted_at ? 'Dibatalkan' : ($lelang->pemenang_id ? 'Selesai' : 'Selesai (Tanpa Pemenang)') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; padding: 1rem;">Tidak ada lelang pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
