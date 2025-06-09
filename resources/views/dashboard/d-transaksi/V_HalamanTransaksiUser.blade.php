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
                        {{-- <th>No.</th> --}}
                        {{-- <th>ID Tawaran</th> --}}
                        <th>Kode Transaksi</th>
                        <th>Kode Lelang</th>
                        <th>Harga</th>
                        <th>Status Saat Ini</th>
                        <th>Waktu Dibayar</th>
                        <th>No. Resi</th>
                        <th>Metode pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $index => $transaksi)
                        <tr class="border-b-1 border-bsoft">
                            {{-- <!--NO.-->
                            <td class="text-center">{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1 }}.</td> --}}
                            {{-- <!--ID TAWARAN-->
                            <td class="max-w-30">{{ $transaksi->pasang_lelang_id }}</td> --}}
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
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivering')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-restore"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivered')
                                <td class="flex items-center max-w-60">
                                    <span class="h-3 w-3 rounded-full mr-2 flex-shrink-0 bg-info"></span>
                                    {{ $transaksi->statusTransaksi->nama_status_transaksi }}
                                </td>
                            @endif
                            <!--PAYMENT TIME-->
                            <td><span class="w-full text-center">{{ $transaksi->payment_time ? $transaksi->payment_time : '-' }}</span></td>
                            <!--NO RESI-->
                            <td class="text-center">{{ $transaksi->no_resi ? $transaksi->no_resi : '-' }}</td>
                            <!--METODE PEMBAYARAN-->
                            <td class="text-center">{{ $transaksi->payment_method_id ? $transaksi->paymentMethod->nama_payment_method : 'Belum Dibayar' }}</td>
                            <!--AKSI-->
                            <td >
                                <div class="flex items-center justify-center ">
                                    @if ($transaksi->statusTransaksi->kode_status_transaksi === 'pending')
                                        <a href="{{ route('transaksi.checkout', ['id' => $transaksi->id]) }}"
                                            class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center
                                                hover:bg-primer hover:text-white"
                                            >
                                            Lanjutkan<br>Pembayaran
                                        </a>
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivering')
                                    <form id="selesaikanPesananForm" action="{{ route('dashboard.pesananSelesai') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="order_id" id="orderId" value="{{ $transaksi->order_id }}">
                                        <button id="selesaikanPesananButton" type="button"
                                            class="text-sm px-4 py-2 rounded-lg bg-white border text-primer text-center
                                                hover:bg-primer hover:text-white"
                                        >
                                            Selesaikan<br>Pesanan
                                        </button>
                                    </form>
                                    @elseif ($transaksi->statusTransaksi->kode_status_transaksi === 'delivered')
                                        <a href="{{ route('rating.add', ['id' => $transaksi->id]) }}"
                                            class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center
                                                hover:bg-primer hover:text-white"
                                            >
                                            Beri<br>Penilaian
                                        </a>
                                    @endif
                                </div>
                            </td>
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

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('selesaikanPesananButton');
            const form = document.getElementById('selesaikanPesananForm');

            button.addEventListener('click', function () {
                showAlert({
                    title: 'Konfirmasi Selesaikan Pesanan',
                    text: 'Apakah Anda yakin ingin menyelesaikan pesanan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Selesaikan!',
                    cancelButtonText: 'Batal',
                    onConfirm: function () {
                        form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                    }
                });
            });
        });
</script>
@endsection


