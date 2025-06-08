@extends('layouts.app')

@section('title', 'Transaksi')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-2 w-full">
        <h1 class="font-bold text-2xl">{{ Auth::user()->role->nama_role == 'customer' ? 'Selamat Datang, '.Auth::user()->name : 'Dashboard' }}</h1>
        <p class="font-light">{{ Auth::user()->role->nama_role == 'customer' ? 'Berikut beberapa informasi anda' : 'Ringkasan Toko & Website Anda' }}</p>
        @if (Auth::check() && (Auth::user()->role->nama_role == 'pegawai' || Auth::user()->role->nama_role == 'owner'))
        <div class="flex gap-5 my-5 w-full flex-wrap lg:flex-nowrap">
            <div class="border p-4 rounded-md min-w-55 w-full">
                <p class="mb-2">Penjualan berhasil Minggu Ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($incomeMingguIni, 0, ',', '.') }}</h2>
            </div>
            <div class="border p-4 rounded-md min-w-55 w-full">
                <p class="mb-2">Penjualan berhasil bulan ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($incomeBulanIni, 0, ',', '.') }}</h2>
            </div>
            <div class="border p-4 rounded-md min-w-55 w-full">
                <p class="mb-2">Saldo NauTure Saat Ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($saldo->saldo, 0, ',', '.') }}</h2>
            </div>
        </div>
        <h2 class="font-semibold text-xl py-2">{{ Auth::user()->role->nama_role == 'pegawai' ? 'Pesanan Perlu Diantar' : 'Transaksi Terbaru' }}</h2>
        <div>
            <table class="w-full">
                <thead>
                    <tr class="border-b-1 border-primer py-1">
                        <th>No.</th>
                        <th>Kode Transaksi</th>
                        <th>Harga Total</th>
                        <th>Nama Customer</th>
                        <th>Dibayar Pada</th>
                        @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $index => $transaksi)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <td>{{ $transaksi->order_id }}</td>
                            <td>Rp. {{ number_format($transaksi->gross_amount, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->lelang->pemenang->user->name }}</td>
                            <td class="text-center">{{ $transaksi->payment_time }}</td>
                            @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                            <td>
                                <!-- Popup -->
                                @include('dashboard.d-transaksi.V_FormTambahResi')
                                <!-- Aksi -->
                                <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                    <a class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                        {{-- href="{{ route('dashboard.transaksi') }}" --}}
                                        onclick="showPopup({{ $transaksi->id }}, '{{ route('dashboard.updateStatsTransaksi') }}')"
                                    >
                                        Kirim
                                    </a>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function showPopup(transaksiId, formAction) {
            const popup = document.getElementById('popup-resi');
            const form = document.getElementById('popup-resi-form');
            const transaksiInput = document.getElementById('popup-transaksi-id');

            transaksiInput.value = transaksiId;
            form.action = formAction;

            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function closePopup() {
            const popup = document.getElementById('popup-resi');
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }
    </script>
@endsection
