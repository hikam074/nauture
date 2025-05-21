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
                        <th>Kode Lelang</th>
                        <th>Judul Lelang</th>
                        <th>Tawaran Anda</th>
                        <th>Status Saat Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allBids as $index => $myBid)
                        @include('dashboard.popup_alamat')
                        <tr class="border-b-1 border-bsoft">
                            <!-- No. -->
                            <td class="text-center">{{ $index + 1 }}.</td>
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
                            <!-- Aksi -->
                            <td class="text-white font-semibold text-sm">
                                @if ($myBid->status ==='Menang, belum dibayar')
                                    <a class="">
                                        <button type="button" onclick="openPopup({{ $myBid->id }}, {{ $myBid->lelang->jumlah_kg }}, {{ $myBid->harga_pengajuan }}, {{ $myBid->lelang->jumlah_kg }}, '{{ $myBid->lelang->kode_lelang }}')"
                                            class="h-full w-full px-3 py-2 rounded-lg bg-sekunderDark
                                                hover:bg-primer"
                                            >
                                            Bayar
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
                                @elseif ($myBid->status == ('Menang, selesai dibayar' || 'Menang, segera selesaikan pembayaran'))
                                    <a href="{{ route('transaksi.index') }}">
                                        <button class="h-full w-full bg-info rounded-lg px-3 py-2
                                                hover:bg-infohov"
                                            >
                                            Lihat Transaksi
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
        </div>
    </div>

    <script>
        const popup = document.getElementById('popupAlamat');
        const closePopup = document.getElementById('closePopup');
        // const content = document.getElementById('alamatContent');

        // Function to open popup
        function openPopup(pasang_lelang_id, weight, hargaBid, beratPaket, kodeLelang) {
            document.getElementById('pasang_lelang_id').value = pasang_lelang_id;
            document.getElementById('weight').value = weight;
            document.getElementById('hargaBid').value = hargaBid;
            document.getElementById('beratPaket').value = beratPaket;
            document.getElementById('kodeLelang').value = kodeLelang;
            document.getElementById('konfirmasiBiayaLelang').innerText = hargaBid.toLocaleString('id-ID');
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        // Close popup when "Batal" is clicked
        closePopup.addEventListener('click', () => {
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        });
    </script>





@endsection





