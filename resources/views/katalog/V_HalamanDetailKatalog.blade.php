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
                            <div class="gap-4 hidden text-white font-bold text-center
                                md:flex md:flex-col lg:flex-row"
                                >
                                @if ($katalog->trashed())
                                    <!-- TOMBOL AKSI MD RESTORE-->
                                    <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                        class="px-4 py-2 bg-restore rounded
                                        hover:bg-restorehov"
                                        >
                                        Restore
                                        </button>
                                    </form>
                                @else
                                    <!-- TOMBOL AKSI MD EDIT-->
                                    <a href="{{ route('katalog.edit', $katalog->id) }}"
                                        class="px-4 py-2 text-primer bg-white rounded border-2 border-primer max-h-10
                                        hover:bg-sekunderDark hover:text-white"
                                        >
                                        Edit
                                    </a>
                                    <!-- TOMBOL AKSI MD DELETE-->
                                    <form action="{{ route('katalog.destroy', $katalog->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
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
                        <div class="flex gap-4 w-full text-center text-white font-bold my-4
                            md:hidden"
                            >
                            @if ($katalog->trashed())
                                <!-- TOMBOL AKSI XS EDIT-->
                                <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST"
                                    class="w-full"
                                    >
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-4 py-2 bg-restore rounded w-full
                                        hover:bg-restorehov"
                                        >
                                        Restore
                                    </button>
                                </form>
                            @else
                                <!-- TOMBOL AKSI XS EDIT-->
                                <a href="{{ route('katalog.edit', $katalog->id) }}"
                                    class="px-4 py-2 bg-white text-primer rounded w-full border-2 border-primer
                                    hover:bg-sekunderDark hover:text-white"
                                    >
                                    Edit
                                </a>
                                <!-- TOMBOL AKSI XS DELETE-->
                                <a class="w-full bg-red-500 rounded
                                    hover:bg-red-600"
                                    >
                                    <form action="{{ route('katalog.destroy', $katalog->id) }}" method="POST"
                                        class="w-full"
                                        >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
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
                            @php
                                $rating = 4.7;
                            @endphp
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($rating))
                                        <!-- Bintang Penuh -->
                                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.357 4.188a1 1 0 00.95.69h4.418c.969 0 1.371 1.24.588 1.81l-3.584 2.603a1 1 0 00-.364 1.118l1.357 4.188c.3.921-.755 1.688-1.539 1.118L10 14.347l-3.584 2.603c-.783.57-1.838-.197-1.539-1.118l1.357-4.188a1 1 0 00-.364-1.118L2.286 9.615c-.783-.57-.38-1.81.588-1.81h4.418a1 1 0 00.95-.69l1.357-4.188z"/>
                                        </svg>
                                    @elseif ($i - $rating < 1)
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
                                <strong>{{ $rating > 0 ? "$rating/5" : "" }}</strong>
                            </div>
                        </div>
                        <div class="font-thin text-gray-500">
                            <p>{{ $rating > 0 ? ($rating*20)."% pembeli merasa puas" : "Belum ada penilaian" }}</p>
                            <p>{{ $rating > 0 ? "XXX Rating - XXX Ulasan" : "" }}</p>
                        </div>
                    </div>
                    <div class="overflow-y-scroll max-h-52 pr-5 border-b-10 border-white" style="box-shadow: 4px 4px 6px -1px rgba(0, 0, 0, 0.2);">
                        @for ($i = 0; $i < 10; $i++)
                                <li class="py-2 border-b flex justify-between items-center"
                                    >
                                    <div class="flex items-center space-x-3">
                                        <!-- foto profil -->
                                        {{-- @if ($winner->user->foto_profil)
                                            <img src="{{ asset('storage/' . $winner->user->foto_profil) }}" alt="Foto Profil {{ $winner->user->name }}"
                                            class="w-10 h-10 rounded-full"
                                            >
                                        @else --}}
                                            <svg width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="2 2 20 20">
                                                <path d="M22 12C22 6.49 17.51 2 12 2C6.49 2 2 6.49 2 12C2 14.9 3.25 17.51 5.23 19.34C5.23 19.35 5.23 19.35 5.22 19.36C5.32 19.46 5.44 19.54 5.54 19.63C5.6 19.68 5.65 19.73 5.71 19.77C5.89 19.92 6.09 20.06 6.28 20.2C6.35 20.25 6.41 20.29 6.48 20.34C6.67 20.47 6.87 20.59 7.08 20.7C7.15 20.74 7.23 20.79 7.3 20.83C7.5 20.94 7.71 21.04 7.93 21.13C8.01 21.17 8.09 21.21 8.17 21.24C8.39 21.33 8.61 21.41 8.83 21.48C8.91 21.51 8.99 21.54 9.07 21.56C9.31 21.63 9.55 21.69 9.79 21.75C9.86 21.77 9.93 21.79 10.01 21.8C10.29 21.86 10.57 21.9 10.86 21.93C10.9 21.93 10.94 21.94 10.98 21.95C11.32 21.98 11.66 22 12 22C12.34 22 12.68 21.98 13.01 21.95C13.05 21.95 13.09 21.94 13.13 21.93C13.42 21.9 13.7 21.86 13.98 21.8C14.05 21.79 14.12 21.76 14.2 21.75C14.44 21.69 14.69 21.64 14.92 21.56C15 21.53 15.08 21.5 15.16 21.48C15.38 21.4 15.61 21.33 15.82 21.24C15.9 21.21 15.98 21.17 16.06 21.13C16.27 21.04 16.48 20.94 16.69 20.83C16.77 20.79 16.84 20.74 16.91 20.7C17.11 20.58 17.31 20.47 17.51 20.34C17.58 20.3 17.64 20.25 17.71 20.2C17.91 20.06 18.1 19.92 18.28 19.77C18.34 19.72 18.39 19.67 18.45 19.63C18.56 19.54 18.67 19.45 18.77 19.36C18.77 19.35 18.77 19.35 18.76 19.34C20.75 17.51 22 14.9 22 12ZM16.94 16.97C14.23 15.15 9.79 15.15 7.06 16.97C6.62 17.26 6.26 17.6 5.96 17.97C4.44 16.43 3.5 14.32 3.5 12C3.5 7.31 7.31 3.5 12 3.5C16.69 3.5 20.5 7.31 20.5 12C20.5 14.32 19.56 16.43 18.04 17.97C17.75 17.6 17.38 17.26 16.94 16.97Z" fill="#292D32"/>
                                                <path d="M12 6.92969C9.93 6.92969 8.25 8.60969 8.25 10.6797C8.25 12.7097 9.84 14.3597 11.95 14.4197C11.98 14.4197 12.02 14.4197 12.04 14.4197C12.06 14.4197 12.09 14.4197 12.11 14.4197C12.12 14.4197 12.13 14.4197 12.13 14.4197C14.15 14.3497 15.74 12.7097 15.75 10.6797C15.75 8.60969 14.07 6.92969 12 6.92969Z" fill="#292D32"/>
                                            </svg>
                                        {{-- @endif --}}
                                        <!-- harga pengajuan -->
                                        <div>
                                            <span class="font-semibold"> Lorem ipsum
                                                {{-- {{ $winner->user->name ?? }} --}}
                                            </span><br>
                                            <span class="text-gray-600 font-thin">
                                                {{-- Rp {{ number_format($winner->harga_pengajuan, 0, ',', '.')}} --}}
                                                Barang sampai dengan selamat
                                            </span>
                                        </div>
                                    </div>
                                    <!-- tanggal pengajuan -->
                                    <div class="text-sm text-gray-500">
                                        xx.xx
                                        {{-- mau diasih bintangnya --}}
                                        {{-- {{ $bid->updated_at ? $bid->updated_at->format('d M Y, H:i') : $bid->created_at->format('d M Y, H:i') }} --}}
                                    </div>
                                </li>
                        @endfor
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
