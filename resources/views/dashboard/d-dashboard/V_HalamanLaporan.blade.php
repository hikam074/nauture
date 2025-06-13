@extends('layouts.app')

@section('title', 'Dashboard')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-5 w-full">
        <div>
            <h1 class="font-bold text-2xl">
                {{ Auth::user()->role->nama_role == 'customer' ? 'Selamat Datang, '.Auth::user()->name : 'Dashboard' }}
            </h1>
            <p class="font-light">
                {{ Auth::user()->role->nama_role == 'customer' ? 'Berikut beberapa informasi anda' : 'Ringkasan Toko & Website Anda' }}
            </p>
        </div>
        @if (Auth::check() && (Auth::user()->role->nama_role == 'pegawai' || Auth::user()->role->nama_role == 'owner'))
        <div class="flex gap-5 w-full flex-wrap lg:flex-nowrap">
            <div class="border p-4 rounded-md min-w-55 w-full shadow-lg">
                <p class="mb-2">Penjualan berhasil Minggu Ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($incomeMingguIni, 0, ',', '.') }}</h2>
            </div>
            <div class="border p-4 rounded-md min-w-55 w-full shadow-lg">
                <p class="mb-2">Penjualan berhasil bulan ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($incomeBulanIni, 0, ',', '.') }}</h2>
            </div>
            <div class="border p-4 rounded-md min-w-55 w-full shadow-lg">
                <p class="mb-2">Saldo NauTure Saat Ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($saldo->saldo, 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="flex gap-5 flex-wrap justify-between w-full h-full">
            {{-- Chart Harian --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2 chart-container">
                <canvas id="dailyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($dailyIncome->pluck("date"))'
                    data-data='@json($dailyIncome->pluck("total"))'>
                </canvas>
            </div>

            {{-- Chart Mingguan --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2 chart-container">
                <canvas id="weeklyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($weeklyIncome->pluck("week_label"))'
                    data-data='@json($weeklyIncome->pluck("total"))'>
                </canvas>
            </div>

            {{-- Chart Bulanan --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2 chart-container">
                <canvas id="monthlyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($monthlyIncome->pluck("month_label"))'
                    data-data='@json($monthlyIncome->pluck("total"))'>
                </canvas>
            </div>

            {{-- Chart Tahunan --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2 chart-container">
                <canvas id="yearlyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($yearlyIncome->pluck("year"))'
                    data-data='@json($yearlyIncome->pluck("total"))'>
                </canvas>
            </div>
        </div>
        @endif
        <div class="w-full border-1 border-canceled rounded shadow-lg p-4">
            <h2 class="font-semibold text-xl py-2">
                @if (Auth::user()->role->nama_role == 'pegawai')
                Pesanan Perlu Diantar
                @elseif (Auth::user()->role->nama_role == 'owner')
                Transaksi Terbaru
                @else
                Lelang Belum Dibayar
                @endif
            </h2>
            <div>
                <table class="w-full">
                    <thead>
                        <tr class="border-b-1 border-primer py-1">
                            <th>No.</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Lelang</th>
                            <th>Harga Total</th>
                            <th>Nama Customer</th>
                            @if (Auth::check() && Auth::user()->role->nama_role != 'customer')
                            <th>Dibayar Pada</th>
                            @endif
                            @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                            <th>Aksi</th>
                            @elseif (Auth::check() && Auth::user()->role->nama_role == 'customer')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksis as $index => $transaksi)
                            <tr @if ($index % 2 == 0) class="bg-gray-50" @else class="bg-white" @endif>
                                <td class="text-center">{{ $index + 1 }}.</td>
                                <td>{{ $transaksi->order_id }}</td>
                                <td>{{ $transaksi->lelang->nama_produk_lelang }}</td>
                                <td>Rp. {{ number_format($transaksi->gross_amount, 0, ',', '.') }}</td>
                                <td>{{ $transaksi->lelang->pemenang->user->name }}</td>
                                @if (Auth::check() && Auth::user()->role->nama_role != 'customer')
                                <td class="text-center">{{ $transaksi->payment_time }}</td>
                                @endif
                                @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                                <td>
                                    <!-- Popup -->
                                    @include('dashboard.d-transaksi.V_FormTambahResi')
                                    <!-- Aksi -->
                                    <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                        <a class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                            onclick="showPopup({{ $transaksi->id }}, '{{ route('dashboard.updateStatsTransaksi') }}')"
                                        >
                                            Kirim
                                        </a>
                                    </div>
                                </td>
                                @elseif (Auth::check() && Auth::user()->role->nama_role == 'customer')
                                <td>
                                    <!-- Aksi -->
                                    <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                        <a class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                            href="{{ route('transaksi.index') }}"
                                        >
                                            Lanjut Bayar
                                        </a>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="w-full flex justify-end mt-5">
                <a href="" class="text-xs py-2 px-4 bg-primer text-white rounded border-1 border-primer
                    hover:bg-white hover:text-primer transition"
                    >
                    Lihat lebih banyak...
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showPopup(transaksiId, formAction) {
            const popup = document.getElementById('popup-resi');
            const background = popup.querySelector('.popup-bg');
            const form = popup.querySelector('.popup-form');
            const transaksiInput = document.getElementById('popup-transaksi-id');

            transaksiInput.value = transaksiId;
            document.getElementById('popup-resi-form').action = formAction;

            popup.classList.remove('hidden');
            popup.classList.add('flex');

            setTimeout(() => {
                background.classList.add('fade-in');
                background.classList.remove('fade-out');
                form.classList.add('show');
            }, 10); // Delay untuk memastikan transisi diterapkan
        }

        function closePopup() {
            const popup = document.getElementById('popup-resi');
            const background = popup.querySelector('.popup-bg');
            const form = popup.querySelector('.popup-form');

            background.classList.remove('fade-in');
            background.classList.add('fade-out');
            form.classList.remove('show');

            form.addEventListener(
                'transitionend',
                () => {
                    popup.classList.add('hidden');
                    popup.classList.remove('flex');
                },
                { once: true }
            );
        }
    </script>
@endsection
