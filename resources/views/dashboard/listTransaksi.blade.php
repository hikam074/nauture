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
                        <th>Aksi : Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $index => $transaksi)
                        <tr class="border-b-1 border-bsoft">
                            <!--NO.-->
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <!--NAMA USER-->
                            <td class="max-w-30">{{ $transaksi->pasangLelang->user->name }}</td>
                            <!--KODE TRANSAKSI-->
                            <td class="max-w-30">{{ $transaksi->order_id }}</td>
                            <!--KODE LELANG-->
                            <td class="max-w-20">{{ $transaksi->lelang->kode_lelang }}</td>
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
                            <td>{{ $transaksi->payment_time }}</td>
                            <!--AKSI-->
                            <td>
                                <!-- Popup -->
                                <div id="popup-resi" class="hidden fixed top-0 left-0 w-full h-full items-center justify-center">
                                    <div class=" fixed top-0 left-0 w-full h-full items-center justify-center bg-black opacity-30 z-0"></div>
                                    <div class="bg-white rounded-lg p-6 w-1/3 z-1">
                                        <h2 class="text-lg font-bold mb-4">Masukkan Nomor Resi</h2>
                                        <form id="popup-resi-form" action="" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="transaksi_id" id="popup-transaksi-id">
                                            <div class="mb-4">
                                                <label for="no_resi" class="block mb-2">Nomor Resi</label>
                                                <input type="text" id="popup-nomor-resi" name="no_resi" required
                                                    class="border px-2 py-1 w-full rounded-lg"
                                                >
                                            </div>
                                            <div class="flex justify-end gap-4">
                                                <button type="button" class="text-sm px-4 py-2 rounded-lg bg-gray-300" onclick="closePopup()">Batal</button>
                                                <button type="submit" class="text-sm px-4 py-2 rounded-lg bg-primer text-white hover:bg-opacity-90">Kirim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Tombol -->
                                @if ($transaksi->statusTransaksi->kode_status_transaksi === 'settlement')
                                <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                    <button
                                        class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                        onclick="showPopup({{ $transaksi->id }}, '{{ route('dashboard.updateStatsTransaksi') }}')"
                                    >
                                        Update<br>Telah Dikirim
                                    </button>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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





