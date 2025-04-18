<x-layout>
    <section class="pb-4">
        <div class="w-full h-[150px] relative bg-cover bg-center"
            style="background-image: url('/images/assets/katalogFill.png');">
            <!-- Bar Filter & Search -->
            <div class="absolute inset-0 bg-[#242222] opacity-50 pointer-events-none"></div>
            {{-- manipulasi background --}}
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 bg-white rounded-2xl shadow-sm p-4 flex justify-between items-center w-[90%] max-w-4xl">
                <!-- Kiri: Urutkan dan Dropdown -->
                <form action="{{ route('katalog.index') }}" method="GET" class="space-y-3">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <div class="flex items-center gap-2">
                        <p class="font-medium">Urutkan</p>
                        <div class="relative">
                            <select
                                name="sort_by"
                                onchange="this.form.submit()"
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
                <form action="{{ route('katalog.index') }}" method="GET" class="w-1/2">
                    <div class="relative">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari produk..."
                        class="w-full border border-gray-300 pl-4 pr-20 py-2 rounded-full focus:outline-none focus:ring focus:ring-blue-200"
                        value="{{ request('search') }}"
                    />
                    <button
                        type="submit"
                        class="absolute right-2 top-1/2 transform bg-[#CEF17B] text-gray-700 px-4 py-1 rounded-full -translate-y-1/2 hover:bg-green-600 hover:text-white transition duration-300"
                    >
                        Search
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="katalog w-[90%] mx-auto pt-10">
        <div class="flex gap-4">
            <!-- Sisi Kiri: Filter dan Tombol Tambah -->
            <div class="w-1/5 pb-12">
                <!-- Tombol Tambah Produk -->
                @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                    <a href="{{ route('katalog.add') }}"
                       class="w-full px-4 py-2 text-sm font-medium text-white bg-[#638B35] rounded-lg hover:bg-[#0F3714] transition block text-center my-4">
                        Tambah Produk
                    </a>
                @endif
                <!-- Filter -->
                <div class="mt-4">
                    <h3 class="font-semibold text-md">Filter</h3>
                <div class="bg-[#CEED82] shadow-lg rounded-lg p-4 mt-1">
                    <form action="{{ route('katalog.index') }}" method="GET" class="space-y-3">
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        <div class="relative">
                            <label for="kategori" class="text-sm font-medium mb-1 block">Kategori</label>
                            <!-- Radio Button untuk Kategori -->
                            <div class="flex flex-col space-y-2">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="kategori" value="active" {{ request('kategori') == 'active' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                    <span>Produk Tersedia</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="kategori" value="deleted" {{ request('kategori') == 'deleted' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                    <span>Produk Dihapus</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="kategori" value="all" {{ request('kategori') == 'all' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-500" onchange="this.form.submit()">
                                    <span>Semua Produk</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>

            <!-- Sisi Kanan: Cards Katalog -->
            <div class="w-4/5">
                <div class="relative overflow-hidden">
                    <div class="flex sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-12">
                        @forelse ($katalogs as $katalog)
                            <div
                                class="bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-transform transform hover:scale-102 hover:shadow-2xl"
                                onclick="window.location.href='{{ route('katalog.show', ['id' => $katalog->id]) }}'">
                                <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="{{ $katalog->nama_produk }}" class="w-full h-40 object-cover rounded-md">
                                <h2 class="text-center text-[#0F3714] text-lg font-semibold mt-2">{{ $katalog->nama_produk }}</h2>
                                <p class="text-center text-[#0F3714] font-thin text-xs">Mulai Rp{{ number_format($katalog->harga_perkilo, 0, ',', '.') }}/kg</p>

                                <!-- Kondisi Produk -->
                                @if($katalog->trashed())
                                    @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                        <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                                Restore
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                        <div class="flex justify-between items-center mt-2">
                                            <a href="{{ route('katalog.edit', $katalog->id) }}"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                                                Edit
                                            </a>
                                            <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}"
                                            class="px-4 py-2 text-sm font-medium text-white bg-gray-500 rounded-lg hover:bg-gray-600 transition">
                                                Detail
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <p class="p-4">Tidak Ada Produk.</p>
                        @endforelse
                    </div>
                </div>
                {{ $katalogs->links() }} {{-- Pagination --}}
            </div>
        </div>
    </section>

</x-layout>
