@extends('layouts.app')

@section('title', 'Transaksi')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-10 w-full">
        <div>
            <h1 class="font-bold text-4xl">Transaksi Anda</h1>
            <p class="font-thin text-sm">Semua transaksi yang anda lakukan</p>
        </div>
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-1 border-primer py-1">
                        <th>No.</th>
                        <th>Kode Transaksi</th>
                        <th>Kode Lelang</th>
                        <th>Harga</th>
                        <th>Status Saat Ini</th>
                        <th>Waktu Dibayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $index => $transaksi)
                        <tr class="border-b-1 border-bsoft">
                            <!--NO.-->
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <!--KODE TRANSAKSI-->
                            <td class="max-w-30">{{ $transaksi->order_id }}</td>
                            <!--KODE LELANG-->
                            <td class="max-w-20">{{ $transaksi->lelang->kode_lelang }}</td>
                            <!--HARGA.-->
                            <td>Rp. {{ number_format($transaksi->gross_amount, 0, ',', '.') }}</td>
                            <!--STATUS-->
                            @if ($transaksi->statusTransaksi->kode_status_transaksi === 'capture')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-edithov"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'settlement')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-success"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'pending')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-restore"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'deny')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-hapus"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'cancel')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-canceledhov"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'expire')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-canceled"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'failure')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-hapus"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @endif
                            <!--PAYMENT TIME-->
                            <td>{{ $transaksi->payment_time }}</td>
                            <!--AKSI-->
                            <td class="flex items-center justify-center ">
                                @if ($transaksi->statusTransaksi->kode_status_transaksi === 'pending')
                                    <a href="{{ route('transaksi.checkout', ['id' => $transaksi->id]) }}"
                                        class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center
                                            hover:bg-primer hover:text-white"
                                        >
                                        Lanjutkan<br>Pembayaran
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


        {{-- 'order_id',
        'lelang_id',
        'pasang_lelang_id',
        'gross_amount',
        'alamat',
        'snap_token',
        'url_midtrans',
        'payment_time',
        'payment_method_id',
        'status_transaksi_id', --}}




