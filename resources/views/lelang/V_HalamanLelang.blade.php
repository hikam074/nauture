@extends('layouts.app')

@section('title', 'Lelang')

@section('content')

    <!-- TOP -->
    <section class="pb-4">
        <div class="w-full h-[150px] relative bg-cover bg-center"
            style="background-image: url('/images/assets/lelangFill.png');">
            <!-- manipulasi background -->
            <div class="absolute inset-0 bg-[#242222] opacity-50 pointer-events-none"></div>

            <!-- BAR SORT & SEARCH -->
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 bg-white rounded-2xl shadow-sm p-4 flex justify-between items-center w-[90%] max-w-4xl">
                <!-- BAR : SORT BY -->
                <form action="{{ route('lelang.index') }}" method="GET" class="space-y-3">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <input type="hidden" name="katalog_id[]" value="{{ implode(',', request('katalog_id', [])) }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
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
                <form action="{{ route('lelang.index') }}" method="GET" class="w-1/2">
                    <div class="relative">
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                        <input type="hidden" name="katalog_id[]" value="{{ implode(',', request('katalog_id', [])) }}">
                        <input type="text" name="search" placeholder="Cari lelang..." value="{{ request('search') }}"
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
    <section class="lelang mx-1 pt-10 text-primer
        sm:mx-5 md:mx-10"
        >
        <div class="flex flex-col gap-4
            sm:flex-row"
            >
            <!-- CONTAINER FILTER & TOMBOL ADD -->
            <div class="flex flex-row gap-3 w-full justify-center
                sm:flex-col sm:w-auto sm:justify-start"
                >
                <!-- TOMBOL TAMBAH LELANG -->
                @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                <div class="flex items-center text-center text-white p-1 bg-primer rounded-lg
                    sm:mb-4"
                    >
                    <a href="{{ route('lelang.add') }}"
                       class="w-20 p-2 block text-sm font-medium
                        hover:bg-primer transition
                            sm:w-full sm:h-auto"
                        >
                        Tambah Lelang
                    </a>
                </div>
                @endif
                <!-- FILTER -->
                <div class="">
                    <h3 class="font-semibold text-md hidden
                        sm:inline sm:w-full"
                        >
                        Filter
                    </h3>
                    <div class="bg-white shadow-lg rounded-lg p-2 h-full border-1 border-primer flex flex-row
                        sm:p-4 sm:bg-bsoft sm:border-bsoft sm:flex-col"
                        >
                        {{-- <div class="w-full
                            sm:hidden"
                            >
                            <button onclick=""
                                class="w-full h-full px-2"
                                >
                                Filters
                            </button>
                        </div> --}}
                        @if (Auth::check() && ((Auth::user()->role->nama_role == 'pegawai') || (Auth::user()->role->nama_role == 'owner')))
                        <form action="{{ route('lelang.index') }}" method="GET"
                            class="space-y-3 h-full
                                sm:inline"
                            >
                            <input type="hidden" name="katalog_id[]" value="{{ implode(',', request('katalog_id', [])) }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <div class="relative h-full">
                                <label class="hidden text-sm font-medium mb-1
                                    sm:block"
                                    >
                                    Status
                                </label>
                                <!-- FILTER BY STATUS -->
                                <div class="flex flex-row space-x-2
                                    sm:flex-col sm:space-y-2"
                                    >
                                    <div class="space-y-2">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="filter" onchange="this.form.submit()"
                                                value="active" {{ request('filter') == 'active' ? 'checked' : '' }}
                                                class="form-radio h-4 w-4 text-blue-500"
                                                >
                                            <span>Berlangsung</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="filter" onchange="this.form.submit()"
                                                value="completed" {{ request('filter') == 'completed' ? 'checked' : '' }}
                                                class="form-radio h-4 w-4 text-blue-500"
                                                >
                                            <span>Selesai</span>
                                        </label>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="filter" onchange="this.form.submit()"
                                                value="deleted" {{ request('filter') == 'deleted' ? 'checked' : '' }}
                                                class="form-radio h-4 w-4 text-blue-500"
                                                >
                                            <span>Dibatalkan</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="filter" onchange="this.form.submit()"
                                                value="all" {{ request('filter') == 'all' ? 'checked' : '' }}
                                                class="form-radio h-4 w-4 text-blue-500"
                                                >
                                            <span>Semua</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endif
                        <form action="{{ route('lelang.index') }}" method="GET"
                            class="space-y-3 h-full hidden
                                sm:inline"
                            >
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <div class="relative">
                                <label class="hidden text-sm font-medium mb-1
                                    sm:block"
                                    >
                                    Pilih Katalog
                                </label>
                                <!-- FILTER BY STATUS -->
                                <div class="flex flex-col space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="katalog_id[]" onchange="this.form.submit()"
                                            value="" {{ in_array('', request('katalog_id', [])) ? 'checked' : '' }}
                                            class="form-checkbox h-4 w-4 text-blue-500"
                                            >
                                        <span>Semua Katalog</span>
                                    </label>
                                    @foreach($allKatalogs as $katalog)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="katalog_id[]"
                                            value="{{ $katalog->id }}" {{ in_array($katalog->id, request('katalog_id', [])) ? 'checked' : '' }}
                                            class="form-checkbox h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                            <span>{{ $katalog->nama_produk }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- CONTAINER CARDS -->
            <div class="w-full pb-12">
                <div class="relative">
                    <div class="flex gap-2 flex-wrap
                        sm:gap-4"
                        >
                        <!-- KARDS GENERATE -->
                        @forelse ($lelangs as $lelang)
                            <div onclick="window.location.href='{{ route('lelang.show', ['id' => $lelang->id]) }}'"
                                class="flex flex-col justify-between gap-4 w-[48%] text-center text-primer bg-white rounded-lg shadow-lg p-4 cursor-pointer
                                transition-transform transform hover:scale-102 hover:shadow-2xl
                                sm:w-48"
                                >
                                <!-- KARDS : FOTO, JUDUL, HARGA -->
                                <div>
                                    <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="{{ $lelang->nama_produk_lelang }}"
                                        class="w-full h-40 object-cover rounded-md"
                                    >
                                    <h2 class="text-lg font-semibold my-2 border-b-1">
                                        {{ $lelang->nama_produk_lelang }}
                                    </h2>
                                    @if($lelang->pasangLelang->isNotEmpty())
                                        <p class="font-thin text-xs">
                                            Penawaran Tertinggi : Rp{{ number_format($lelang->pasangLelang->max('harga_pengajuan'), 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="font-thin text-xs">
                                            Dibuka Mulai : Rp{{ number_format($lelang->harga_dibuka, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>

                                <!-- KARDS : TOMBOL2 -->
                                <div class="flex w-full text-sm justify-between gap-2 font-medium text-white">
                                    @if(!($lelang->trashed()))
                                        @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                            <!-- PEGAWAI : EDIT -->
                                            <a href="{{ route('lelang.edit', $lelang->id) }}"
                                                class="w-full py-2 bg-blue-500 rounded-lg flex items-center justify-center
                                                hover:bg-blue-600 transition"
                                                >
                                                <span>Edit</span>
                                            </a>
                                            <!-- DETAIL -->
                                            <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                                class="w-full py-2 bg-gray-500 rounded-lg flex items-center justify-center
                                                hover:bg-gray-600 transition"
                                                >
                                                <span>Detail</span>
                                            </a>
                                        @else
                                            <!-- PASANG BID -->
                                            <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                                class="w-full py-2 bg-blue-500 rounded-lg flex items-center justify-center
                                                hover:bg-blue-600 transition"
                                                >
                                                <span>Pasang<br>Tawaran</span>
                                            </a>
                                            <!-- DETAIL -->
                                            <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                                class="w-full py-2 bg-gray-500 rounded-lg flex items-center justify-center
                                                hover:bg-gray-600 transition"
                                                >
                                                <span>Detail</span>
                                            </a>
                                        @endif
                                    @else
                                        @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                            @if ($lelang->pemenang_id)
                                                <!-- DUMMY BUTTON SELESAI -->
                                                <button class="w-full px-4 py-2 bg-gray-300 text-white rounded-lg flex items-center justify-center">
                                                    <span>Selesai</span>
                                                </button>
                                            @else
                                                <!-- PEGAWAI : RESTORE -->
                                                <a class="w-full px-4 py-2 bg-yellow-500 rounded-lg
                                                    hover:bg-yellow-600 transition"
                                                    >
                                                    <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit">
                                                            Restore
                                                        </button>
                                                    </form>
                                                </a>
                                            @endif
                                        @else
                                            <!-- DUMMY BUTTON -->
                                            <button class="w-full px-4 py-4.5 bg-gray-300 text-white rounded-lg flex items-center justify-center">
                                                <span>{{ $lelang->pemenang_id ? 'Selesai' : 'Dibatalkan' }}</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="p-4">Tidak Ada Lelang.</p>
                        @endforelse
                    </div>
                </div>
                <!-- pagination -->
                {{ $lelangs->links() }}
            </div>
        </div>
    </section>

@endsection
