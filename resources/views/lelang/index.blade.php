<x-layout>

    <section class="pb-4"> <!-- Bar Sorting & Search -->
        <div class="w-full h-[150px] relative bg-cover bg-center"
            style="background-image: url('/images/assets/lelangFill.png');">
            <div class="absolute inset-0 bg-[#242222] opacity-50 pointer-events-none"></div>
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 bg-white rounded-2xl shadow-sm p-4 flex justify-between items-center w-[90%] max-w-4xl">
                <!-- Kiri: Sort by Dropdown -->
                <form action="{{ route('lelang.index') }}" method="GET" class="space-y-3">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <input type="hidden" name="katalog_id[]" value="{{ implode(',', request('katalog_id', [])) }}">
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
                <!-- Kanan: Search Bar -->
                <form action="{{ route('lelang.index') }}" method="GET" class="w-1/2">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari lelang..." value="{{ request('search') }}"
                            class="w-full border border-gray-300 pl-4 pr-20 py-2 rounded-full focus:outline-none focus:ring focus:ring-blue-200"
                        />
                        <button type="submit" class="absolute right-2 top-1/2 transform bg-[#CEF17B] text-gray-700 px-4 py-1 rounded-full -translate-y-1/2 hover:bg-[#638B35] hover:text-white transition duration-300">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="lelang w-[90%] mx-auto pt-10"> <!-- Filters : Konten Utama -->
        <div class="flex gap-4">
            <!-- Sisi Kiri: Filter dan Tombol Tambah -->
            <div class="w-1/5 pb-12">
                {{-- Tombol Tambah Lelang --}}
                @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                    <a href="{{ route('lelang.add') }}"
                       class="w-full px-4 py-2 text-sm font-medium text-white bg-[#638B35] rounded-lg hover:bg-[#0F3714] transition block text-center my-4">
                        Tambah Lelang
                    </a>
                @endif
                {{-- Filter --}}
                <div class="mt-4">
                    <h3 class="font-semibold text-md">Filter</h3>
                    <div class="bg-[#CEED82] shadow-lg rounded-lg p-4 mt-1">
                        {{-- Filter by status --}}
                        <form action="{{ route('lelang.index') }}" method="GET" class="space-y-3">
                            <input type="hidden" name="katalog_id[]" value="{{ implode(',', request('katalog_id', [])) }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <div class="relative">
                                <label class="text-sm font-medium mb-1 block">Status</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="filter" value="active" {{ request('filter') == 'active' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                        <span>Berlangsung</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="filter" value="completed" {{ request('filter') == 'completed' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                        <span>Selesai</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="filter" value="deleted" {{ request('filter') == 'deleted' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                        <span>Dibatalkan</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="filter" value="all" {{ request('filter') == 'all' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                        <span>Semua</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                        {{-- Filter by Katalogs --}}
                        <form action="{{ route('lelang.index') }}" method="GET" class="space-y-3">
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">

                            <div class="relative mt-1">
                                <label class="text-sm font-medium mb-1 block">Pilih Katalog</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="katalog_id[]" value="" {{ in_array('', request('katalog_id', [])) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                        <span>Semua Katalog</span>
                                    </label>
                                    @foreach($allKatalogs as $katalog)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="katalog_id[]" value="{{ $katalog->id }}" {{ in_array($katalog->id, request('katalog_id', [])) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                            <span>{{ $katalog->nama_produk }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Sisi Kanan: Cards Lelang -->
            <div class="w-4/5">
                <div class="relative overflow-hidden">
                    <div class="flex sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-12">
                        @forelse ($lelangs as $lelang)
                            <div class="bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-transform transform hover:scale-102 hover:shadow-2xl flex flex-col justify-between"
                                onclick="window.location.href='{{ route('lelang.show', ['id' => $lelang->id]) }}'">
                                <div>
                                    <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="{{ $lelang->nama_produk_lelang }}" class="w-full h-40 object-cover rounded-md">
                                    <h2 class="text-center text-[#0F3714] text-lg font-semibold mt-2">{{ $lelang->nama_produk_lelang }}</h2>
                                    @if($lelang->pasangLelang->isNotEmpty())
                                        <p>Penawaran Tertinggi : Rp{{ number_format($lelang->tawaran->max('nominal_tawaran'), 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-center text-[#0F3714] font-thin text-xs">Dibuka Mulai : Rp{{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</p>
                                    @endif
                                </div>

                                <!-- Tombol item Lelang -->
                                <div class="mt-4">
                                    @if(!($lelang->trashed()))
                                        @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                            <div class="flex justify-between items-center">
                                                {{-- Tombol edit --}}
                                                <a href="{{ route('lelang.edit', $lelang->id) }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                                                    Edit
                                                </a>
                                                {{-- Tombol detail --}}
                                                <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-500 rounded-lg hover:bg-gray-600 transition">
                                                    Detail
                                                </a>
                                            </div>
                                        @else
                                            <div class="flex justify-between items-center">
                                                {{-- Tombol bid --}}
                                                <a href="{{ route('lelang.form', $lelang->id) }}" class="px-4 py-2 text-sm font-medium text-white text-center bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                                                    Pasang<br>Tawaran
                                                </a>
                                                {{-- Tombol detail --}}
                                                <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-500 rounded-lg hover:bg-gray-600 transition">
                                                    Detail
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                            {{-- Tombol restore --}}
                                            <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            {{-- DUMMY BUTTON --}}
                                            <button class="w-full px-4 py-2 bg-gray-300 text-white rounded-lg">
                                                {{ $lelang->pemenang_id ? 'Selesai' : 'Dibatalkan' }}
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
                {{ $lelangs->links() }} {{-- Pagination --}}
            </div>
        </div>
    </section>

</x-layout>
