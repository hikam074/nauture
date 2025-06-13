@extends('layouts.app')

@section('title', 'Lelang Anda')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-10 w-full">
        <div>
            <h1 class="font-bold text-4xl">Lelang Anda</h1>
            <p class="font-thin text-sm">Semua lelang yang anda pasang penawaran</p>
        </div>
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-1 border-primer py-1">
                        <th>No.</th>
                        {{-- <th>ID Tawaran</th> --}}
                        <th>Kode Lelang</th>
                        <th>Judul Lelang</th>
                        <th>Tawaran Anda</th>
                        <th>Status Saat Ini</th>
                        <th>Waktu Dimenangkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allBids as $index => $myBid)
                        @include('dashboard.d-lelang.V_FormTambahPembayaran')
                        <tr class="border-b-1 border-bsoft">
                            <!-- No. -->
                            <td class="text-center">{{ ($allBids->currentPage() - 1) * $allBids->perPage() + $index + 1 }}.</td>
                            {{-- <!-- ID Tawaran -->
                            <td>{{ $myBid->id }}</td> --}}
                            <!-- Kode Lelang -->
                            <td>{{ $myBid->lelang->kode_lelang }}</td>
                            <!-- Judul Lelang -->
                            <td>{{ $myBid->lelang->nama_produk_lelang }}</td>
                            <!-- Tawaran Anda -->
                            <td>Rp. {{ number_format($myBid->harga_pengajuan, 0, ',', '.') }}</td>
                            <!-- Status -->
                            @if ($myBid->status === 'Berlangsung, Penawar Tertinggi')
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-edit"></span>
                                    {{ $myBid->status }}
                                </td>
                            @elseif ($myBid->status === 'Berlangsung, BUKAN Penawar Tertinggi')
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-hapus"></span>
                                    {{ $myBid->status }}
                                </td>
                            @elseif ($myBid->status === 'Menang, belum dibayar')
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-restore"></span>
                                    {{ $myBid->status }}
                                </td>
                            @elseif ($myBid->status === 'Menang, selesai dibayar')
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-success"></span>
                                    {{ $myBid->status }}
                                </td>
                            @elseif ($myBid->status === 'Menang, pesanan selesai')
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-edit"></span>
                                    {{ $myBid->status }}
                                </td>
                            @elseif ($myBid->status === 'Dialihkan ke pemenang lain')
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-hapus"></span>
                                    {{ $myBid->status }}
                                </td>
                            @else
                                <td class="flex flex-row items-center">
                                    <span class="h-3 w-3 rounded-full mr-2 bg-canceled"></span>
                                    {{ $myBid->status }}
                                </td>
                            @endif
                            <!-- Waktu menang -->
                            <td>{{ $myBid->waktu_dimenangkan }}</td>
                            <!-- Aksi -->
                            <td class="text-white font-semibold text-sm">
                                @if ($myBid->status ==='Menang, belum dibayar')
                                    <a class="">
                                        <button type="button" onclick="openPopup({{ $myBid->id }}, {{ $myBid->lelang->jumlah_kg }}, {{ $myBid->harga_pengajuan }}, '{{ $myBid->lelang->kode_lelang }}', '{{ $myBid->waktu_dimenangkan }}')"
                                            class="h-full w-full px-3 py-2 rounded-lg bg-sekunderDark
                                                hover:bg-primer"
                                            >
                                            Bayar Pesanan Lelang
                                        </button>
                                    </a>
                                @elseif ($myBid->status === 'Kalah')
                                    <a href="{{ route('lelang.show', ['id' => $myBid->lelang_id]) }}">
                                        <button class="h-full w-full bg-canceled rounded-lg px-3 py-2
                                                hover:bg-canceledhov"
                                            >
                                            Lihat
                                        </button>
                                    </a>
                                @elseif ($myBid->status === ('Menang, segera selesaikan pembayaran'))
                                    <a href="{{ route('transaksi.index') }}">
                                        <button class="h-full w-full bg-info rounded-lg px-3 py-2
                                                hover:bg-infohov"
                                            >
                                            Lihat Transaksi
                                        </button>
                                    </a>
                                @elseif ($myBid->status === ('Menang, selesai dibayar'))
                                    <a href="{{ route('transaksi.index') }}">
                                        <button class="h-full w-full bg-info rounded-lg px-3 py-2
                                                hover:bg-infohov"
                                            >
                                            Lihat Transaksi
                                        </button>
                                    </a>
                                @elseif (($myBid->status === ('Menang, pesanan selesai')) && isset($myBid->transaksi))
                                    <a href="{{ route('transaksi.index') }}">
                                        <button class="h-full w-full bg-info rounded-lg px-3 py-2
                                                hover:bg-infohov"
                                            >
                                            Beri Penilaian
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('lelang.show', ['id' => $myBid->lelang_id]) }}">
                                        <button class="h-full w-full px-3 py-2 rounded-lg bg-white border-1 text-primer
                                                hover:bg-sekunderDark hover:text-white transition"
                                            >
                                            Lihat Lelang
                                        </button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $allBids->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const popup = document.getElementById('popupAlamat');
        const popupContent = document.getElementById('alamatContent');
        const closePopup = document.getElementById('closePopup');

        // Function to open popup
        function openPopup(pasang_lelang_id, weight, hargaBid, kodeLelang, waktuDimenangkan = null) {
            // parsing waktu backend ke Date object
            const waktuMenang = new Date(waktuDimenangkan);
            const sekarang = new Date();
            // hitung selisih dalam milidetik
            const diffMs = sekarang - waktuMenang;
            // konversi ke jam
            const diffHours = diffMs / (1000 * 60 * 60);
            if (diffHours > 3) {
                toastr.error('Waktu pembayaran telah habis. Anda tidak dapat melakukan pembayaran lagi.');
                return; // stop fungsi, popup tidak dibuka
            }

            document.getElementById('pasang_lelang_id').value = pasang_lelang_id;
            document.getElementById('weight').value = weight;
            document.getElementById('hargaBid').value = hargaBid;
            document.getElementById('beratPaket').innerText = weight;
            document.getElementById('kodeLelang').innerText = kodeLelang;
            document.getElementById('konfirmasiBiayaLelang').innerText = hargaBid.toLocaleString('id-ID');

            popup.classList.remove('hidden', 'fade-out-full');
            popupContent.classList.remove('move-up');
            popup.classList.add('fade-in-full', 'flex');
            popupContent.classList.add('move-down');
        }

        // Close popup when "Batal" is clicked
        closePopup.addEventListener('click', () => {
            popup.classList.remove('fade-in-full');
            popupContent.classList.remove('move-down');
            popup.classList.add('fade-out-full');
            popupContent.classList.add('move-up');

            // Tunggu hingga animasi selesai, lalu sembunyikan popup
            setTimeout(() => {
                popup.classList.remove('flex');
                popup.classList.add('hidden');
            }, 500);
        });
    </script>
@endsection

