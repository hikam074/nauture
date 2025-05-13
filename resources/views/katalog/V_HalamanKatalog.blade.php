@extends('layouts.app')

@section('title', 'Katalog')

@section('content')

    <!-- TOP -->
    <section class="pb-4">
        <div class="w-full h-[150px] relative bg-cover bg-center"
            style="background-image: url('/images/assets/katalogFill.png');">
            <!-- manipulasi background -->
            <div class="absolute inset-0 bg-[#242222] opacity-50 pointer-events-none"></div>

            <!-- BAR SORT & SEARCH -->
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 bg-white rounded-2xl shadow-sm p-4 flex justify-between items-center w-[90%] max-w-4xl">
                <!-- BAR : SORT BY -->
                <form action="{{ route('katalog.index') }}" method="GET" class="space-y-3">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <div class="flex items-center gap-2">
                        <p class="font-medium">Urutkan</p>
                        <div class="relative">
                            <select name="sort_by" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg pl-4 pr-8 py-2 focus:outline-none focus:ring focus:ring-blue-200 appearance-none"
                                >
                                <option value="date_added" {{ request('sort_by') == 'date_added' ? 'selected' : '' }}>Default</option>
                                <option value="alphabetical" {{ request('sort_by') == 'alphabetical' ? 'selected' : '' }}>Per Abjad</option>
                            </select>
                            <span class="absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-500">
                                ▼
                            </span>
                        </div>
                    </div>
                </form>
                <!-- BAR : SEARCH BAR -->
                <form action="{{ route('katalog.index') }}" method="GET" class="w-1/2">
                    <div class="relative">
                    <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}"
                        class="w-full border border-gray-300 pl-4 pr-20 py-2 rounded-full focus:outline-none focus:ring focus:ring-blue-200"
                    />
                    <button type="submit" class="absolute right-2 top-1/2 transform bg-kuarter text-gray-700 px-4 py-1 rounded-full -translate-y-1/2
                        hover:bg-sekunder hover:text-white transition duration-300"
                        >
                        Search
                    </button>
                    </div>
                </form>

            </div>
        </div>
    </section>

    <!-- MAIN -->
    <section class="katalog mx-1 pt-10
        sm:mx-10"
        >
        <div class="flex flex-col gap-4
            sm:flex-row"
            >
            <!-- CONTAINER FILTER & TOMBOL ADD -->
            <div class="flex flex-row gap-1 w-full justify-center
                sm:flex-col sm:gap-3 sm:w-auto sm:justify-start"
                >
                <!-- TOMBOL TAMBAH PRODUK -->
                @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                <div class="flex items-center text-center text-white p-1 bg-primer rounded-lg">
                    <a href="{{ route('katalog.add') }}"
                       class="w-20 block text-sm font-medium
                        hover:bg-primer transition
                        sm:w-full sm:h-auto sm:p-2"
                        >
                        Tambah Produk
                    </a>
                </div>
                @endif
                <!-- FILTER -->
                @if (Auth::check() && ((Auth::user()->role->nama_role == 'pegawai') || (Auth::user()->role->nama_role == 'owner')))
                <div class="w-auto sm:max-w-45 md:w-45">
                    <h3 class="font-semibold text-md hidden
                        sm:inline sm:w-full"
                        >
                        Filter
                    </h3>
                    <div class="bg-white shadow-lg rounded-lg p-1 h-full border-1 border-primer
                        sm:p-4 sm:bg-bsoft sm:border-bsoft"
                        >
                        <form action="{{ route('katalog.index') }}" method="GET" class="space-y-3 h-full">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <div class="relative h-full">
                                <label for="kategori" class="hidden text-sm font-medium mb-1
                                    sm:block"
                                    >
                                    Kategori
                                </label>
                                <!-- KATEGORI TIAP FILTER -->
                                <div class="flex flex-row gap-2 items-center text-center
                                    sm:flex-col sm:items-start sm:text-start"
                                    >
                                    <label class="flex flex-col items-center gap-2 bg-bsoft rounded-lg p-1 text-sm border-1 border-primer
                                        sm:flex-row sm:bg-transparent sm:text-md sm:border-transparent sm:p-0"
                                        >
                                        <input type="radio" name="kategori" value="active" {{ request('kategori') == 'active' ? 'checked' : '' }} onchange="this.form.submit()"
                                            class="form-radio h-4 w-4 text-blue-500"
                                            >
                                        <span>Produk Tersedia</span>
                                    </label>
                                    <label class="flex flex-col items-center gap-2 bg-bsoft rounded-lg p-1 text-sm border-1 border-primer
                                        sm:flex-row sm:bg-transparent sm:text-md sm:border-transparent sm:p-0"
                                        >
                                        <input type="radio" name="kategori" value="deleted" {{ request('kategori') == 'deleted' ? 'checked' : '' }} onchange="this.form.submit()"
                                            class="form-radio h-4 w-4 text-blue-500"
                                            >
                                        <span>Produk Dihapus</span>
                                    </label>
                                    <label class="flex flex-col items-center gap-2 bg-bsoft rounded-lg p-1 text-sm border-1 border-primer
                                        sm:flex-row sm:bg-transparent sm:text-md sm:border-transparent sm:p-0"
                                        >
                                        <input type="radio" name="kategori" value="all" {{ request('kategori') == 'all' ? 'checked' : '' }} onchange="this.form.submit()"
                                            class="form-radio h-4 w-4 text-blue-500"
                                            >
                                        <span>Semua Produk</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- CONTAINER KARDS -->
            <div class="w-full pb-12">
                <div class="relative">
                    <div class="flex gap-2 flex-wrap
                        sm:gap-4"
                        >
                        <!-- KARDS GENERATE -->
                        @forelse ($katalogs as $katalog)
                            <div onclick="window.location.href='{{ route('katalog.show', ['id' => $katalog->id]) }}'"
                                class="flex flex-col justify-between gap-4 w-[48%] text-center text-primer bg-white rounded-lg shadow-lg p-4 cursor-pointer
                                transition-transform transform hover:scale-102 hover:shadow-2xl
                                sm:w-48"
                                >
                                <!-- KARDS : FOTO, JUDUL, HARGA -->
                                <div>
                                    <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="{{ $katalog->nama_produk }}"
                                        class="w-full h-40 object-cover rounded-md"
                                    >
                                    <h2 class="text-lg font-semibold my-2 border-b-1">
                                        {{ $katalog->nama_produk }}
                                    </h2>
                                    <p class="font-thin text-xs">
                                        Mulai Rp{{ number_format($katalog->harga_perkilo, 0, ',', '.') }}/kg
                                    </p>
                                </div>

                                <!-- KARDS : TOMBOL2 -->
                                <div class="flex w-full text-sm justify-between gap-2 font-medium text-white">
                                    @if($katalog->trashed())
                                        @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                        <!-- PEGAWAI : RESTORE -->
                                        <a class="w-full px-4 py-2 bg-yellow-500 rounded-lg
                                                hover:bg-yellow-600 transition"
                                            >
                                            <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                    <button type="submit">
                                                        Restore
                                                    </button>
                                            </form>
                                        </a>
                                        @endif
                                    @else
                                        @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                            <!-- PEGAWAI : EDIT -->
                                            <a href="{{ route('katalog.edit', $katalog->id) }}"
                                                class="w-full py-2 bg-blue-500 rounded-lg
                                                hover:bg-blue-600 transition"
                                                >
                                                Edit
                                            </a>
                                            <!-- DETAIL -->
                                            <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}"
                                                class="w-full py-2 bg-gray-500 rounded-lg
                                                hover:bg-gray-600 transition"
                                                >
                                                Detail
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="p-4">Tidak Ada Produk.</p>
                        @endforelse

                    </div>
                </div>
                <!-- pagination -->
                {{ $katalogs->links() }}
            </div>
        </div>
    </section>
@endsection
