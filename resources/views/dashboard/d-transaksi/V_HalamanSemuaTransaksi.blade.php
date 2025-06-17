@extends('layouts.app')

@section('title', 'Transaksi')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-10 w-full">
        <div>
            <h1 class="font-bold text-4xl">Transaksi</h1>
            <p class="font-thin text-sm">Semua transaksi NauTure</p>
        </div>
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-1 border-primer py-1">
                        <th>No.</th>
                        <th>Nama User</th>
                        <th>Kode Transaksi</th>
                        <th>Kode Lelang</th>
                        <th>Harga</th>
                        <th>Status Saat Ini</th>
                        <th>Waktu Dibayar</th>
                        <th>No. Resi</th>
                        <th class="max-w-30">Metode Pembayaran</th>
                        @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                        <th>Aksi : Ubah Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $index => $transaksi)
                        <tr class="border-b-1 border-bsoft
                             animasi-slide-kekanan"
                            >
                            <!--NO.-->
                            <td class="text-center">{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1 }}.</td>
                            <!--NAMA USER-->
                            <td class="max-w-30">{{ $transaksi->pasangLelang->user->name }}</td>
                            <!--KODE TRANSAKSI-->
                            <td class="max-w-30">{{ $transaksi->order_id }}</td>
                            <!--KODE LELANG-->
                            <td class="max-w-20">{{ $transaksi->lelang_id ? $transaksi->lelang->kode_lelang : '-' }}</td>
                            <!--HARGA.-->
                            <td>Rp. {{ number_format($transaksi->gross_amount, 0, ',', '.') }}</td>
                            <!--STATUS-->
                            <td class="max-w-60">
                                <div class="flex gap-2 items-center">
                                    @if ($transaksi->statusTransaksi->kode_status_transaksi === 'capture')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-edithov"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'settlement')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-success"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'pending')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-restore"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'deny')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-hapus"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'cancel')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-canceledhov"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'expire')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-canceled"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'failure')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-hapus"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivering')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-restore"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivered')
                                        <span class="h-3 w-3 rounded-full flex-shrink-0 bg-info"></span>
                                        {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                    @endif
                                </div>
                            </td>
                            <!--PAYMENT TIME-->
                            <td>{{ $transaksi->payment_time ? $transaksi->payment_time : '-' }}</td>
                            <!--NO RESI-->
                            <td>{{ $transaksi->no_resi ? $transaksi->no_resi : '-' }}</td>
                            <!--PAYMENT NETHOD-->
                            <td class="max-w-30">{{ $transaksi->payment_method_id ? $transaksi->paymentMethod->nama_payment_method : '-' }}</td>
                            <!--AKSI-->
                            @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                            <td>
                                <!-- Popup -->
                                @include('dashboard.d-transaksi.V_FormTambahResi')
                                <!-- Tombol -->
                                @if ($transaksi->statusTransaksi->kode_status_transaksi === 'settlement')
                                <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                    <button
                                        class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                        onclick="showPopup({{ $transaksi->id }}, '{{ route('dashboard.updateStatsTransaksi') }}')"
                                    >
                                        Ubah Pesanan<br>Menjadi Dikirim
                                    </button>
                                </div>
                                @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivering')
                                <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                    <button
                                        class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                        onclick="showPopup({{ $transaksi->id }}, '{{ route('dashboard.updateStatsTransaksi') }}')"
                                    >
                                        Ubah<br>Nomor Resi
                                    </button>
                                </div>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>

    <script>
        function showPopup(transaksiId, formAction) {
            const popup = document.getElementById('popup-resi');
            popup.classList.add('flex');
            const form = popup.querySelector('.popup-form');
            const background = popup.querySelector('.popup-bg');

            // Update form action and value
            document.getElementById('popup-transaksi-id').value = transaksiId;
            document.getElementById('popup-resi-form').action = formAction;

            // Add animation classes
            background.classList.remove('fade-out');
            background.classList.add('fade-in');
            form.classList.remove('move-up');
            form.classList.add('move-down');
        }

        function closePopup() {
            const popup = document.getElementById('popup-resi');
            const form = popup.querySelector('.popup-form');
            const background = popup.querySelector('.popup-bg');

            background.classList.remove('fade-in');
            background.classList.add('fade-out');
            form.classList.remove('move-down');
            form.classList.add('move-up');

            setTimeout(() => {
                popup.classList.remove('flex');
                popup.classList.add('hidden');
            }, 300);
        }
    </script>


@endsection





