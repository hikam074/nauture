<x-layout>

    <section>
        @if ((Auth::check()) && (session('email') && session('name')))
        <p>Selamat datang, {{ session('name') }}!</p>
        @else
        <p>Selamat datang, Pengunjung!</p>
        @endif
    </section>

    <section class="katalog">
        <div class="katalog-head flex justify-between items-center mb-4">
            <h2>Katalog Produk</h2>
            <a href="{{ route('katalog.index') }}" class="text-blue-500 hover:underline">Lihat Lebih Banyak...</a>
            @if (Auth::check() && Auth::user()->email && Auth::user()->name && Auth::user()->role->nama_role == 'pegawai')
                <a href="{{ route('katalog.add') }}">Tambah Produk</a>
            @endif
        </div>
        {{-- cards katalogs --}}
        <div class="flex sm:grid sm:grid-cols-2 md:flex md:space-x-4 overflow-x-scroll scrollbar-hide p-4">
            @forelse ($katalogs as $katalog)
                <div class="flex-none w-64 bg-white rounded-lg shadow p-4">
                    <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="{{ $katalog->nama_produk }}" class="w-full h-40 object-cover rounded-md">
                    <h2 class="text-lg font-semibold mt-2">{{ $katalog->nama_produk }}</h2>
                    <p class="text-gray-600">{{ Str::limit($katalog->description, 50) }}</p>
                    <p class="text-green-500 font-bold mt-2">Kisaran harga : Rp{{ number_format($katalog->harga_perkilo, 0, ',', '.') }}/kg</p>
                    <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}" class="mt-4 inline-block px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Lihat Produk
                    </a>
                </div>
            @empty
                <p>Produk Kosong.</p>
            @endforelse
        </div>
    </section>

    <section class="lelang">
        <div class="lelang-head flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Produk Lelang</h2>
            <a href="{{ route('lelang.index') }}" class="text-blue-500 hover:underline">Lihat Lebih Banyak...</a>
        </div>

        <!-- cards lelangs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($lelangs->take(6) as $lelang)
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="{{ asset('storage/' . $lelang->katalog->foto_produk) }}" alt="{{ $lelang->katalog->nama_produk }}" class="w-full h-40 object-cover rounded-md">
                    <p class="text-lg font-semibold mt-2">{{ $lelang->nama_produk_lelang }}</p>
                    <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}" class="mt-4 inline-block px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Detail
                    </a>
                </div>
            @endforeach
        </div>
    </section>

</x-layout>
