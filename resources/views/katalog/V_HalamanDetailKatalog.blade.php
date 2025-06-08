@extends('layouts.app')

@section('title', 'Detail Katalog')

@section('content')
    <div class="bg-white rounded-lg p-6 w-full mx-auto mb-12 text-black">
        <div class="flex flex-col gap-5
            md:flex-row">


            <!-- FLEX KIRI -->
            <div class="flex gap-5 h-full justify-center
                md:w-100 md:flex-col md:h-auto md:gap-4 md:justify-start"
                >
                <!-- Foto Produk -->
                <div class="w-100 max-h-100 max-w-100">
                    @if ($katalog->foto_produk)
                        <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="Foto Produk"
                            class="w-full rounded-lg max-h-100 aspect-square object-cover border-5 border-gray-200"
                        >
                    @else
                        <p class="text-gray-500">Tidak ada foto produk tersedia.</p>
                    @endif
                </div>
            </div>


            <!-- FLEX KANAN -->
            <div class=" flex flex-col gap-8 shadow-xs rounded-sm px-4 w-full">

                <!-- Div 1: Informasi -->

                <div class="flex flex-col gap-4">
                    <!-- nama -->
                    <h2 class="text-3xl">{{ $katalog->nama_produk }}</h2>
                    <!-- harga -->
                    <div class="flex flex-row justify-between gap-10">
                        <p class="font-light text-gray-500">
                            Kisaran Harga: Rp {{ number_format($katalog->harga_perkilo, 0, ',', '.') }}/kg
                        </p>
                        <!-- TOMBOL AKSI MD-->
                        @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                            <div class="gap-4 hidden text-white text-center
                                md:flex md:flex-col lg:flex-row"
                                >
                                @if ($katalog->trashed())
                                    <!-- TOMBOL AKSI MD RESTORE-->
                                    <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST" id="formRestoreKatalog">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" id="btnRestoreKatalog"
                                        class="px-4 py-2 bg-restore rounded
                                        hover:bg-restorehov"
                                        >
                                        Restore
                                        </button>
                                    </form>
                                @else
                                    <!-- TOMBOL AKSI MD EDIT-->
                                    <a href="{{ route('katalog.edit', $katalog->id) }}"
                                        class="px-4 py-2 text-primer bg-white rounded border-1 border-primer max-h-10
                                        hover:bg-sekunderDark hover:text-white"
                                        >
                                        Edit
                                    </a>
                                    <!-- TOMBOL AKSI MD DELETE-->
                                    <form action="{{ route('katalog.destroy', $katalog->id) }}" method="POST" id="formHapusKatalog">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="btnHapusKatalog"
                                            class="px-4 py-2 bg-hapus rounded
                                                hover:bg-hapushov"
                                            >
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                    <!-- Deskripsi MD -->
                    <div class="bg-gray-100 p-2">
                        <h3 class="text-lg">
                            Deskripsi Produk
                        </h3>
                        <p class="text-gray-600">
                            <pre>{{ $katalog->deskripsi_produk ?? 'Tidak ada keterangan' }}</pre>
                        </p>
                    </div>
                    @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                        <div class="flex gap-4 w-full text-center text-white my-4
                            md:hidden"
                            >
                            @if ($katalog->trashed())
                                <!-- TOMBOL AKSI XS EDIT-->
                                <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST" id="formRestoreKatalog"
                                    class="w-full"
                                    >
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" id="btnRestoreKatalog"
                                        class="px-4 py-2 bg-restore rounded w-full
                                        hover:bg-restorehov"
                                        >
                                        Restore
                                    </button>
                                </form>
                            @else
                                <!-- TOMBOL AKSI XS EDIT-->
                                <a href="{{ route('katalog.edit', $katalog->id) }}"
                                    class="px-4 py-2 bg-white text-primer rounded w-full border-1 border-primer
                                    hover:bg-sekunderDark hover:text-white"
                                    >
                                    Edit
                                </a>
                                <!-- TOMBOL AKSI XS DELETE-->
                                <a class="w-full bg-red-500 rounded
                                    hover:bg-red-600"
                                    >
                                    <form action="{{ route('katalog.destroy', $katalog->id) }}" method="POST" id="formHapusKatalog"
                                        class="w-full"
                                        >
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="btnHapusKatalog"
                                            class="px-4 py-2 rounded w-full"
                                            >
                                            Hapus
                                        </button>
                                    </form>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Div 2: rating -->

                <div class="my-5">
                    <div>
                        <h3 class="text-xl">Rating Lelang Produk <span class="font-bold">{{ $katalog->nama_produk }}</span></h3>
                        <div class="flex gap-5 py-1.5">

                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($avgRating))
                                        <!-- Bintang Penuh -->
                                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.357 4.188a1 1 0 00.95.69h4.418c.969 0 1.371 1.24.588 1.81l-3.584 2.603a1 1 0 00-.364 1.118l1.357 4.188c.3.921-.755 1.688-1.539 1.118L10 14.347l-3.584 2.603c-.783.57-1.838-.197-1.539-1.118l1.357-4.188a1 1 0 00-.364-1.118L2.286 9.615c-.783-.57-.38-1.81.588-1.81h4.418a1 1 0 00.95-.69l1.357-4.188z"/>
                                        </svg>
                                    @elseif ($i - $avgRating < 1)
                                        <!-- Bintang Setengah -->
                                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <linearGradient id="half">
                                                    <stop offset="50%" stop-color="currentColor"/>
                                                    <stop offset="50%" stop-color="#d1d5dc "/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.357 4.188a1 1 0 00.95.69h4.418c.969 0 1.371 1.24.588 1.81l-3.584 2.603a1 1 0 00-.364 1.118l1.357 4.188c.3.921-.755 1.688-1.539 1.118L10 14.347l-3.584 2.603c-.783.57-1.838-.197-1.539-1.118l1.357-4.188a1 1 0 00-.364-1.118L2.286 9.615c-.783-.57-.38-1.81.588-1.81h4.418a1 1 0 00.95-.69l1.357-4.188z"/>
                                        </svg>
                                    @else
                                        <!-- Bintang Kosong -->
                                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.357 4.188a1 1 0 00.95.69h4.418c.969 0 1.371 1.24.588 1.81l-3.584 2.603a1 1 0 00-.364 1.118l1.357 4.188c.3.921-.755 1.688-1.539 1.118L10 14.347l-3.584 2.603c-.783.57-1.838-.197-1.539-1.118l1.357-4.188a1 1 0 00-.364-1.118L2.286 9.615c-.783-.57-.38-1.81.588-1.81h4.418a1 1 0 00.95-.69l1.357-4.188z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <div>
                                <strong>{{ $avgRating > 0 ? "$avgRating / 5.0" : "" }}</strong>
                            </div>
                        </div>
                        <div class="font-thin text-gray-500">
                            <p>{{ $avgRating > 0 ? ($avgRating*20)."% pembeli merasa puas" : "Belum ada penilaian" }}</p>
                            <p>{{ $barisRatings > 0 ? $barisRatings." Rating - ".$barisRatingsWithUlasan." Ulasan" : "" }}</p>
                        </div>
                    </div>
                    <div class="overflow-y-scroll max-h-52 pr-5 border-b-10 border-white" style="box-shadow: 4px 4px 6px -1px rgba(0, 0, 0, 0.2);">
                        @foreach($ratings as $rating)
                        <!-- LI BARIS ULASAN -->
                            <li class="py-2 border-b flex justify-between items-center"
                                >
                                <div class="flex items-center space-x-3">
                                    <!-- foto profil -->
                                    <img
                                        src="{{ $rating->transaksi->lelang->pemenang->user->foto_profil ? asset('storage/' . $rating->transaksi->lelang->pemenang->user->foto_profil) : asset('images/icons/defaultAvatarDark.svg') }}"
                                        alt="Foto Profil {{ $rating->transaksi->lelang->pemenang->user->name}}"
                                        class="w-10 h-10 rounded-full"
                                    >
                                    <div>
                                        <!-- nama pengulas -->
                                        <span class="font-semibold">
                                            {{ $rating->transaksi->lelang->pemenang->user->name }}
                                        </span><br>
                                            <!-- ulasan -->
                                        <span class="text-gray-600 font-thin">
                                            {{ $rating->ulasan ? $rating->ulasan : '-' }}
                                        </span>
                                    </div>
                                </div>
                                <!-- tombol edit hapus -->
                                <div class="text-sm text-gray-500 flex items-center space-x-1">
                                    @if ((Auth::check()) && Auth::user()->id == $rating->transaksi->lelang->pemenang->user->id)
                                    <div class="flex gap-1">
                                        <!-- tombol edit -->
                                        <a href="{{ route('rating.add', ['id' => $rating->transaksi_id]) }}" class="text-xs text-edit border-edit border-1 p-1 rounded">Ubah</a>
                                        <!-- tombol hapus -->
                                        <form id="hapusKomenForm" action="{{ route('rating.destroy', $rating->transaksi_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs text-hapus border-hapus border-1 p-1 rounded" id="hapusKomen" type="button">Hapus</button>
                                        </form>
                                    </div>
                                    @endif
                                    <!-- skor -->
                                    <div>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $rating->rating)
                                                <span class="text-yellow-400">&#9733;</span>
                                            @else
                                                <span class="text-gray-300">&#9734;</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="ml-2">
                                        {{ $rating->rating }}.0 / 5.0
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>


        <!-- LELANG TERKAIT -->
        <div class="mt-6">
            <h2 class="text-xl font-base py-2 border-t-1 border-gray-300">Lelang Terkait Produk <span class="font-semibold">"{{ $katalog->nama_produk }}"</span></h2>

            <!-- KARDS Lelang -->
            <div class="relative">

                <div class="flex gap-1 justify-between flex-wrap sm:justify-start sm:gap-5">
                    @if ($lelangTerkaits->isNotEmpty())
                    @foreach ($lelangTerkaits->take(5) as $lelangTerkait)
                        <div class="flex flex-col w-[49%] bg-white rounded-lg shadow-lg p-4 cursor-pointer text-center gap-1
                            transform transition-transform hover:scale-102 hover:shadow-2xl
                            sm:w-48"
                            onclick="window.location.href='{{ route('lelang.show', ['id' => $lelangTerkait->id]) }}'"
                            >
                            <img src="{{ asset('storage/' . $lelangTerkait->foto_produk) }}" alt="[{{ $lelangTerkait->nama_produk }}]"
                                class="w-full h-40 object-cover rounded-md"
                            >
                            <h2 class="text-lg font-semibold mt-2">{{ $lelangTerkait->nama_produk_lelang }}</h2>
                            <p class="text-xs font-thin border-t-1 border-primer mt-auto">Harga Awal: Rp{{ number_format($lelangTerkait->harga_dibuka, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                    <div class="flex flex-col justify-center items-center w-[49%] h-auto bg-white rounded-lg shadow-lg p-4 cursor-pointer text-center gap-1
                        transform transition-transform hover:scale-102 hover:shadow-2xl hover:border-1
                        sm:w-48"
                        onclick="window.location.href='{{ route('lelang.index') }}'"
                        >
                        <a class="text-lg font-semibold text-gray-400">lihat lebih banyak</a>
                        <div class="mt-2">
                            <svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="#99a1af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 12H7M17 12L13 8M17 12L13 16" stroke="#99a1af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    @else
                    <p class="p-6">Tidak ada lelang dengan jenis produk ini</p>
                    @endif
                </div>
            </div>
        </div>


        <!-- TOMBOL KEMBALI-->
        <div class="mt-6 hidden
            md:flex"
            >
            <a href="{{ route('katalog.index') }}" class="text-blue-500 hover:underline"><- Kembali ke Daftar Produk</a>
        </div>
    </div>

@endsection

@section('scripts')
    @if((Auth::check()))
        @if (Auth::user()->role->nama_role == 'customer')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const button = document.getElementById('hapusKomen');
                const form = document.getElementById('hapusKomenForm');

                button.addEventListener('click', function () {
                    showAlert({
                        title: 'Batalkan Penilaian?',
                        text: 'Apakah Anda yakin ingin menghapus penilaian ini? Tindakan tidak dapat diurungkan',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus penilaian saya',
                        cancelButtonText: 'Batal',
                        onConfirm: function () {
                            form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                        }
                    });
                });
            });
        </script>
        @elseif (Auth::user()->role->nama_role == 'pegawai')
            @if (!$katalog->trashed())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const buttons = document.querySelectorAll('#btnHapusKatalog');
                    buttons.forEach(button => {
                        button.addEventListener('click', function () {
                            const form = this.closest('form');
                            showAlert({
                                title: 'Hapus Produk?',
                                text: 'Apakah Anda yakin ingin menghapus produk ini?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Batal',
                                onConfirm: function () {
                                    form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                                }
                            });
                        });
                    });
                });
            </script>
            @else
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const buttons = document.querySelectorAll('#btnRestoreKatalog');
                    buttons.forEach(button => {
                        button.addEventListener('click', function () {
                            const form = this.closest('form');
                            showAlert({
                                title: 'Restore Produk?',
                                text: 'Apakah Anda yakin ingin memunculkan kembali produk ini?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Restorasi Kembali',
                                cancelButtonText: 'Batal',
                                onConfirm: function () {
                                    form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                                }
                            });
                        });
                    });
                });
            </script>
            @endif
        @endif
    @endif
@endsection
