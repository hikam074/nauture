@extends('layouts.app')

@section('title', 'Dashboard')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-5 w-full">
        <div>
            <h1 class="font-bold text-4xl">
                {{ Auth::user()->role->nama_role == 'customer' ? 'Selamat Datang, '.Auth::user()->name : 'Dashboard' }}
            </h1>
            <p class="font-light">
                {{ Auth::user()->role->nama_role == 'customer' ? 'Berikut beberapa informasi anda' : 'Ringkasan Toko & Website Anda' }}
            </p>
        </div>
        @if (Auth::check() && (Auth::user()->role->nama_role == 'pegawai' || Auth::user()->role->nama_role == 'owner'))
        <div class="flex gap-5 w-full flex-wrap lg:flex-nowrap">
            <div class="border p-4 rounded-md min-w-55 w-full shadow-lg
                animasi-fade animasi-slide-keatas"
                >
                <p class="mb-2">Penjualan berhasil Minggu Ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($incomeMingguIni, 0, ',', '.') }}</h2>
            </div>
            <div class="border p-4 rounded-md min-w-55 w-full shadow-lg
                animasi-fade animasi-slide-keatas"
                >
                <p class="mb-2">Penjualan berhasil bulan ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($incomeBulanIni, 0, ',', '.') }}</h2>
            </div>
            <div class="border p-4 rounded-md min-w-55 w-full shadow-lg
                animasi-fade animasi-slide-keatas"
                >
                <p class="mb-2">Saldo NauTure Saat Ini</p>
                <h2 class="mb-2 font-bold text-3xl">Rp. {{ number_format($saldo->saldo, 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="flex gap-5 flex-wrap justify-between w-full h-full">
            {{-- Chart Harian --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2
                chart-container animasi-fade animasi-slide-keatas"
                >
                <canvas id="dailyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($dailyIncome->pluck("date"))'
                    data-data='@json($dailyIncome->pluck("total"))'>
                </canvas>
            </div>

            {{-- Chart Mingguan --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2
                chart-container animasi-fade animasi-slide-keatas"
                >
                <canvas id="weeklyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($weeklyIncome->pluck("week_label"))'
                    data-data='@json($weeklyIncome->pluck("total"))'>
                </canvas>
            </div>

            {{-- Chart Bulanan --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2
                chart-container animasi-fade animasi-slide-keatas"
                >
                <canvas id="monthlyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($monthlyIncome->pluck("month_label"))'
                    data-data='@json($monthlyIncome->pluck("total"))'>
                </canvas>
            </div>

            {{-- Chart Tahunan --}}
            <div class="flex-1 min-w-50 max-w-[100%] h-full aspect-square border rounded shadow p-2
                chart-container animasi-fade animasi-slide-keatas"
                >
                <canvas id="yearlyChart"
                    data-width="200px"
                    data-height="200px"
                    data-labels='@json($yearlyIncome->pluck("year"))'
                    data-data='@json($yearlyIncome->pluck("total"))'>
                </canvas>
            </div>
        </div>
        @endif
        <div class="w-full border-1 border-canceled rounded shadow-lg p-4
            animasi-fade animasi-slide-keatas"
            >
            <h2 class="font-semibold text-xl py-2">
                @if (Auth::user()->role->nama_role == 'pegawai')
                Pesanan Perlu Diantar
                @elseif (Auth::user()->role->nama_role == 'owner')
                Transaksi Terbaru
                @else
                Transaksi Belum Dibayar
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
        @if (Auth::user()->role->nama_role == 'customer')
        <div class="w-full border-1 border-canceled rounded shadow-lg p-4
            animasi-fade animasi-slide-keatas"
            >
            <h2 class="font-semibold text-xl py-2">
                Lelang Belum Dibayar
            </h2>
            <div>
                <table class="w-full">
                    <thead>
                        <tr class="border-b-1 border-primer py-1">
                            <th>No.</th>
                            <th>Kode Lelang</th>
                            <th>Nama Lelang</th>
                            <th>Harga Total</th>
                            <th>Waktu Dimenangkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pasangLelangs as $index => $bid)
                            <tr @if ($index % 2 == 0) class="bg-gray-50" @else class="bg-white" @endif>
                                <td class="text-center">{{ $index + 1 }}.</td>
                                <td>{{ $bid->lelang->kode_lelang }}</td>
                                <td>{{ $bid->lelang->nama_produk_lelang }}</td>
                                <td>Rp. {{ number_format($bid->harga_pengajuan, 0, ',', '.') }}</td>
                                <td>{{ $bid->waktu_dimenangkan }}</td>
                                <td>
                                    <!-- Aksi -->
                                    <div class="flex flex-col items-center justify-center gap-2 max-w-30">
                                        <a class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer text-center hover:bg-primer hover:text-white"
                                            href="{{ route('lelang.saya') }}"
                                        >
                                            Buat Pembayaran
                                        </a>
                                    </div>
                                </td>
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
        @endif

        <!--CETAK LAPORAN-->
        @if (Auth::check() && (Auth::user()->role->nama_role == 'owner'))
        <div class="w-full border-1 border-canceled rounded shadow-lg p-4 animasi-fade animasi-slide-keatas">
            <h2 class="font-semibold text-xl py-2">Tampilkan Laporan</h2>
            <div id="laporan-form-wrapper" class="flex flex-col md:flex-row items-end gap-4">
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                    <select id="periode" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-1 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <select id="tahun" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-1 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        {{-- Pilihan tahun diisi oleh JS --}}
                    </select>
                </div>
                <div id="bulan-wrapper">
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select id="bulan" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-1 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        {{-- Pilihan bulan diisi oleh JS --}}
                    </select>
                </div>
                <button type="button" id="tampilkan-laporan-btn" class="bg-primer text-white px-4 py-2 rounded-md hover:bg-sekunderDark transition-colors h-10">
                    Tampilkan
                </button>
            </div>
            <div id="laporan-preview-container" class="mt-8">
                <!-- Preview laporan akan muncul di sini -->
            </div>
        </div>
        @endif

        <div id="pdf-popup" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-2xl w-full max-w-4xl h-[90vh] flex flex-col">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-bold">Preview Laporan PDF</h3>
                    <button onclick="closePdfPopup()" class="text-2xl font-bold text-gray-600 hover:text-red-500">&times;</button>
                </div>
                <div id="pdf-viewer-container" class="flex-grow p-2">
                    {{-- Iframe untuk menampilkan PDF akan dimasukkan di sini oleh JS --}}
                    <p id="pdf-loading-indicator" class="text-center p-8">Memuat PDF...</p>
                </div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah elemen form ada
            const laporanFormContainer = document.getElementById('laporan-form-wrapper');
            if (laporanFormContainer) {
                const periodeSelect = document.getElementById('periode');
                const tahunSelect = document.getElementById('tahun');
                const bulanWrapper = document.getElementById('bulan-wrapper');
                const bulanSelect = document.getElementById('bulan');
                const tampilkanBtn = document.getElementById('tampilkan-laporan-btn');
                const previewContainer = document.getElementById('laporan-preview-container');
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1;

                function populateTahun() {
                    tahunSelect.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const year = currentYear - i;
                        const option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        tahunSelect.appendChild(option);
                    }
                }

                function populateBulan() {
                    const selectedYear = parseInt(tahunSelect.value);
                    bulanSelect.innerHTML = '';
                    const endMonth = (selectedYear === currentYear) ? currentMonth : 12;
                    for (let i = 1; i <= endMonth; i++) {
                        const date = new Date(selectedYear, i - 1, 1);
                        const option = document.createElement('option');
                        option.value = i;
                        option.textContent = new Intl.DateTimeFormat('id-ID', { month: 'long' }).format(date);
                        bulanSelect.appendChild(option);
                    }
                    bulanSelect.value = endMonth;
                }

                function toggleBulanVisibility() {
                    bulanWrapper.style.display = (periodeSelect.value === 'tahunan') ? 'none' : 'block';
                }

                periodeSelect.addEventListener('change', () => {
                    toggleBulanVisibility();
                    populateBulan();
                });
                tahunSelect.addEventListener('change', populateBulan);

                tampilkanBtn.addEventListener('click', function() {
                    const periode = periodeSelect.value;
                    const tahun = tahunSelect.value;
                    const bulan = bulanSelect.value;

                    const previewParams = new URLSearchParams({ periode, tahun, bulan });
                    const downloadParams = new URLSearchParams({ periode, tahun, bulan });

                    previewContainer.innerHTML = '<p class="text-center p-8 text-gray-500">Memuat preview laporan...</p>';
                    tampilkanBtn.disabled = true;
                    tampilkanBtn.textContent = 'Memuat...';

                    fetch(`{{ route('dashboard.laporan.cetak') }}?${previewParams.toString()}`)
                        .then(response => {
                            if (!response.ok) return response.json().then(err => { throw err; });
                            return response.json();
                        })
                        .then(data => {
                            if (data.html) {
                                previewContainer.innerHTML = data.html;
                                const downloadBtn = document.getElementById('tombol-download-laporan');
                                if (downloadBtn) {
                                    downloadBtn.href = `{{ route('dashboard.laporan.download') }}?${downloadParams.toString()}`;
                                }
                            } else {
                                throw new Error('Respons dari server tidak valid.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            previewContainer.innerHTML = `<div class="p-4 bg-red-100 text-red-800 rounded"><strong class="font-bold">Gagal memuat laporan!</strong><p class="mt-2">Detail: ${error.message || 'Tidak ada detail.'}</p></div>`;
                        })
                        .finally(() => {
                            tampilkanBtn.disabled = false;
                            tampilkanBtn.textContent = 'Tampilkan Preview';
                        });
                });

                // Inisialisasi awal
                populateTahun();
                populateBulan();
                toggleBulanVisibility();
            }
        });
    </script>
@endsection
