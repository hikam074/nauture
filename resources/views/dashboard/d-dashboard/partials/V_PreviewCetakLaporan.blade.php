<div id="area-laporan" class="border-t-2 pt-8 mt-8 p-4 border-gray-200">
    <div class="flex justify-between items-start mb-8">
        {{-- Header Laporan --}}
        <div class="flex-grow">
            <h2 class="text-2xl font-bold text-gray-800">{{ $judulLaporan }}</h2>
            <p class="text-sm text-gray-500">Dicetak oleh: {{ Auth::user()->name }} pada {{ now()->translatedFormat('d F Y, H:i') }}</p>
        </div>
        {{-- Logo --}}
        <div class="flex items-center gap-2 h-10">
            <img src="{{ asset('images/logos/roundLogo.png') }}" alt="NauTure Logo" class="h-full object-contain mt-5">
            <img src="{{ asset('images/logos/homeLogo.png') }}" alt="NauTure Text" class="h-8 object-contain">
        </div>
    </div>

    {{-- Ringkasan Pendapatan --}}
    <div class="mb-8">
        <div class="flex justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-700">Ringkasan Pendapatan</h3>
            <div class="mt-8 flex justify-end">
                <a id="tombol-download-laporan" href="#"
                class="h-full flex gap-1 w-auto bg-primer text-xs text-white font-bold px-6 py-3 rounded-lg hover:bg-sekunderDark transition-transform transform hover:-translate-y-1 items-center">
                    <img src="{{ asset('images/icons/download-icon.svg') }}" class="h-5 object-contain">
                    <span>Download Laporan PDF</span>
                </a>
            </div>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg space-y-2 w-[50%]">
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Total Pendapatan Kotor (Termasuk Ongkir)</span>
                <span class="font-mono">: Rp. {{ number_format($pendapatanKotor, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Total Pendapatan Bersih (Profit Lelang)</span>
                <span class="font-mono">: Rp. {{ number_format($pendapatanBersih, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-700 mb-4">Daftar Transaksi Berhasil</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Kode Transaksi</th>
                        <th class="p-3 text-left">Nama Lelang</th>
                        <th class="p-3 text-left">Pemenang</th>
                        <th class="p-3 text-right">Harga Bid</th>
                        <th class="p-3 text-right">Total Bayar</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($transaksis as $index => $transaksi)
                        <tr class="border-b">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y') }}</td>
                            <td class="p-3 font-mono">{{ $transaksi->order_id }}</td>
                            <td class="p-3">{{ $transaksi->lelang?->nama_produk_lelang ?? 'N/A' }}</td>
                            <td class="p-3">{{ $transaksi->lelang?->pemenang?->user?->name ?? 'N/A' }}</td>
                            <td class="p-3 text-right font-mono">Rp. {{ number_format($transaksi->pasangLelang?->harga_pengajuan ?? 0, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-mono">Rp. {{ number_format($transaksi->gross_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar Lelang --}}
    <div>
        <h3 class="text-xl font-bold text-gray-700 mb-4">Daftar Lelang pada Periode Ini</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Tanggal Tutup</th>
                        <th class="p-3 text-left">Nama Lelang</th>
                        <th class="p-3 text-right">Harga Dibuka</th>
                        <th class="p-3 text-right">Harga Tertinggi</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($lelangs as $index => $lelang)
                        <tr class="border-b">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($lelang->tanggal_ditutup)->format('d M Y') }}</td>
                            <td class="p-3">{{ $lelang->nama_produk_lelang }}</td>
                            <td class="p-3 text-right font-mono">Rp. {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</td>
                            @php
                                $hargaTertinggi = $lelang->pasangLelang->isNotEmpty() ? $lelang->pasangLelang->max('harga_pengajuan') : 0;
                            @endphp
                            <td class="p-3 text-right font-mono">{{ $hargaTertinggi > 0 ? 'Rp. ' . number_format($hargaTertinggi, 0, ',', '.') : '-' }}</td>
                            <td class="p-3">{{ $lelang->deleted_at ? 'Dibatalkan' : ($lelang->pemenang_id ? 'Selesai' : 'Selesai (Tanpa Pemenang)') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada lelang pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tombol Download --}}
    <div class="mt-8 flex justify-end">
        <a id="tombol-download-laporan" href="#"
        class="h-full flex gap-1 w-auto bg-primer text-xs text-white font-bold px-6 py-3 rounded-lg hover:bg-sekunderDark transition-transform transform hover:-translate-y-1 items-center">
            <img src="{{ asset('images/icons/download-icon.svg') }}" class="h-5 object-contain">
            <span>Download Laporan PDF</span>
        </a>
    </div>

</div>
