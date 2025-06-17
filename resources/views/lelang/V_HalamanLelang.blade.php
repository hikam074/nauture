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
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 bg-white rounded-2xl shadow-sm p-4 flex justify-between items-center w-[90%] max-w-4xl animasi-slide-keatas">
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
                                <option value="closed_soon" {{ request('sort_by') == 'closed_soon' ? 'selected' : '' }}>Segera Berakhir</option>
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
                <div class="animasi-slide-kekanan">
                    <h3 class="font-semibold text-md hidden
                        sm:inline sm:w-full"
                        >
                        Filter
                    </h3>
                    <div class="bg-white shadow-lg rounded-lg p-2 h-full border-1 border-primer flex flex-row
                        sm:p-4 sm:bg-bsoft sm:border-bsoft sm:flex-col"
                        >
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
                                sm:w-48
                                animasi-slide-keatas
                                "
                                >
                                <!-- KARDS : FOTO, JUDUL, HARGA -->
                                <div class="flex flex-col gap-2">
                                    <!-- KARDS : FOTO -->
                                    <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="{{ $lelang->nama_produk_lelang }}"
                                    class="w-full h-40 object-cover rounded-md"
                                    >
                                    <!-- KARDS : JUDUL -->
                                    <h2 class="text-lg font-semibold border-b-1">
                                        {{ $lelang->nama_produk_lelang }}
                                    </h2>
                                    <!-- KARDS : HARGA -->
                                    <div class="w-full flex items-center gap-1">
                                        <img src="{{ asset('images/icons/hargaIcon.svg') }}" class="w-5">
                                        <span class="font-thin text-xs">Mulai Rp.
                                            {{
                                                $lelang->pasangLelang->isNotEmpty()
                                                ? number_format($lelang->pasangLelang->max('harga_pengajuan'), 0, ',', '.')
                                                : number_format($lelang->harga_dibuka, 0, ',', '.')
                                            }}
                                        </span>
                                    </div>
                                    <!-- KARDS : TENGGAT -->
                                    <div class="w-full flex items-center gap-1">
                                        <img src="{{ asset('images/icons/jamIcon.svg') }}" class="w-5">
                                        <span class="font-thin text-xs text-start">
                                            {{
                                                (now()->greaterThanOrEqualTo($lelang->tanggal_dibuka) && now()->lessThan($lelang->tanggal_ditutup))
                                                // ongoing
                                                ? 'Berakhir : '.$lelang->tanggal_ditutup
                                                : ( now()->lessThan($lelang->tanggal_dibuka)
                                                    // belum dibuka
                                                    ? 'Dibuka : '.$lelang->tanggal_dibuka
                                                    // selesai
                                                    : 'Selesai : '.$lelang->tanggal_ditutup
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- KARDS : TOMBOL2 -->
                                <div class="flex w-full text-sm justify-between gap-2 font-medium text-white">

                                    <!-- PEGAWAI : RESTORE -->
                                    @if ($lelang->trashed() && (Auth::check() && Auth::user()->role->nama_role == 'pegawai') && (now()->lessThan($lelang->tanggal_ditutup)))
                                    <a class="w-full bg-yellow-500 rounded-lg
                                        hover:bg-yellow-600 transition"
                                        >
                                        <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST" id="formRestoreKatalog">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" id="btnRestoreKatalog" onclick="event.stopPropagation();"
                                                class="w-full px-4 py-2"
                                                >
                                                Restore
                                            </button>
                                        </form>
                                    </a>

                                    <!-- ALL : DIHAPUS -->
                                    @elseif ($lelang->trashed())
                                    <button class="w-full px-4 py-2 bg-gray-300 text-white rounded-lg flex items-center justify-center">
                                        <span>Dihapus</span>
                                    </button>

                                    <!-- ALL : SELESAI -->
                                    @elseif ($lelang->pemenang_id || now()->greaterThanOrEqualTo($lelang->tanggal_ditutup))
                                    <button class="w-full px-4 py-2 bg-gray-300 text-white rounded-lg flex items-center justify-center">
                                        <span>Selesai</span>
                                    </button>

                                    <!-- CUSTOMER && GUEST : PASANG BID -->
                                    @elseif ((!Auth::check() || (Auth::check() && Auth::user()->role->nama_role == 'customer')) && (now()->greaterThanOrEqualTo($lelang->tanggal_dibuka) && now()->lessThan($lelang->tanggal_ditutup)))
                                    <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                        class="w-full py-2 bg-blue-500 rounded-lg flex items-center justify-center
                                        hover:bg-blue-600 transition"
                                        >
                                        <span>Pasang Tawaran</span>
                                    </a>

                                    <!-- PEGAWAI : EDIT -->
                                    @elseif (Auth::check() && Auth::user()->role->nama_role == 'pegawai' && now()->lessThan($lelang->tanggal_dibuka))
                                    <a href="{{ route('lelang.edit', $lelang->id) }}"
                                        class="w-full py-2 bg-blue-500 rounded-lg flex items-center justify-center
                                        hover:bg-blue-600 transition"
                                        >
                                        <span>Edit</span>
                                    </a>

                                    <!-- ALL : MENUNGGU DIBUKA -->
                                    @elseif (now()->lessThan($lelang->tanggal_dibuka))
                                    <a class="w-full py-2 bg-gray-500 rounded-lg flex items-center justify-center
                                        hover:bg-gray-600 transition"
                                        >
                                        <span>Menunggu Dibuka</span>
                                    </a>

                                    <!-- ALL : DETAIL -->
                                    @else
                                    <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                        class="w-full py-2 bg-gray-500 rounded-lg flex items-center justify-center
                                        hover:bg-gray-600 transition"
                                        >
                                        <span>Detail</span>
                                    </a>

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

@section('scripts')
    @if ((Auth::check()) && (Auth::user()->role->nama_role == 'pegawai'))
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
@endsection
