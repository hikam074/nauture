<x-layout>

    <section class="welcoming">
        <div class="w-[90%] max-w-6xl mx-auto rounded-lg mt-4 h-[180px] relative bg-cover bg-center flex flex-col justify-center items-center text-white"
             style="background-image: url('/images/assets/homepageFill.png');">
            @if (Auth::user())
                <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
            @else
                <h1 class="text-2xl font-bold mb-2">Selamat Datang, Pengunjung!</h1>
            @endif

            <p class="text-lg mb-4 text-center">Dengan NauTure, pelelangan hasil panen dapat diakses kapan saja dan dimana saja</p>
            <a class="font-bold px-4 py-2 bg-[#CEF17B] hover:bg-white text-[#0F3714] rounded-lg shadow-lg transition duration-300 hover:shadow-xl transform hover:scale-102" href="{{ route('lelang.index') }}">
                Menuju Lelang Sekarang!
            </a>
        </div>
    </section>

    <section class="katalog w-[90%] mx-auto mt-4">
    <!-- Header Katalog -->
    <div class="katalog-head flex justify-between items-center">
        <!-- Judul Katalog -->
        <h2 class="text-xl font-bold pl-3">Produk Kami</h2>

        <div class="flex items-center space-x-4">
            <!-- Link Lihat Lebih Banyak -->
            <a href="{{ route('katalog.index') }}"
            class="px-4 py-2 text-sm font-medium border border-[#0F3714] rounded-lg shadow-lg hover:bg-[#0F3714] hover:text-white transition hover:shadow-xl">
                Lihat Lebih Banyak...
            </a>

            <!-- Tombol Tambah Produk -->
            @if (Auth::check() && Auth::user()->email && Auth::user()->name && Auth::user()->role->nama_role == 'pegawai')
                <a href="{{ route('katalog.add') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-[#0F3714] shadow-lg rounded-lg hover:bg-black transition">
                    Tambah Produk
                </a>
            @endif
        </div>
    </div>


        <!-- Cards Katalog -->
        <div class="relative overflow-hidden p-2">
            <div class="flex sm:grid sm:grid-cols-2 md:flex md:space-x-4">
                @forelse ($katalogs as $katalog)
                    <div
                        class="flex-none w-48 bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-transform transform hover:scale-102  hover:shadow-2xl"
                        onclick="window.location.href='{{ route('katalog.show', ['id' => $katalog->id]) }}'"
                    >
                        <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="{{ $katalog->nama_produk }}" class="w-full h-40 object-cover rounded-md">
                        <h2 class="text-center text-[#0F3714] text-lg font-semibold mt-2">{{ $katalog->nama_produk }}</h2>
                        <p class="text-center text-[#0F3714] font-thin text-xs">Mulai Rp{{ number_format($katalog->harga_perkilo, 0, ',', '.') }}/kg</p>
                    </div>
                @empty
                    <p>Produk Kosong.</p>
                @endforelse
            </div>
        </div>


    </section>

    <section class="lelang w-[90%] mx-auto mt-4 text-[#0F3714]">
        <!-- Header Lelang -->
        <div class="lelang-head flex justify-between items-center">
            <!-- Judul Lelang -->
            <h2 class="text-xl font-bold pl-3">Lelang Aktif</h2>

            <div class="flex items-center space-x-4">
                <!-- Link Lihat Lebih Banyak -->
                <a href="{{ route('lelang.index') }}"
                   class="px-4 py-2 text-sm font-medium border border-[#0F3714] rounded-lg shadow-lg hover:bg-[#0F3714] hover:text-white transition hover:shadow-xl">
                    Lihat Lebih Banyak...
                </a>

                <!-- Tombol Tambah Lelang -->
                @if (Auth::check() && Auth::user()->email && Auth::user()->name && Auth::user()->role->nama_role == 'pegawai')
                    <a href="{{ route('lelang.add') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-[#0F3714] rounded-lg shadow-lg hover:bg-black transition">
                        Tambah Lelang
                    </a>
                @endif
            </div>
        </div>

        <!-- Cards Lelang -->
        <div class="relative overflow-hidden p-2">
            <div class="flex sm:grid sm:grid-cols-2 md:flex md:space-x-4">
                @forelse ($lelangs->take(6) as $lelang)
                    <div
                        class="flex-none w-48 bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-transform transform hover:scale-102 hover:shadow-2xl"
                        onclick="window.location.href='{{ route('lelang.show', ['id' => $lelang->id]) }}'"
                    >
                        <img src="{{ asset('storage/' . $lelang->katalog->foto_produk) }}" alt="{{ $lelang->katalog->nama_produk }}" class="w-full h-40 object-cover rounded-md">
                        <h2 class="text-center text-[#0F3714] text-lg font-semibold mt-2">{{ $lelang->nama_produk_lelang }}</h2>
                        <p class="text-center text-[#0F3714] font-thin text-xs">Harga Awal: Rp{{ number_format($lelang->harga_awal, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p>Produk Lelang Kosong.</p>
                @endforelse
            </div>
        </div>

    </section>

    <section class="bg-[#BEE0C7] mb-12">
        <div class="w-[90%] mx-auto mt-8 flex flex-col items-top gap-12 p-6 md:flex-row">
            <!-- Gambar Fill -->
            <img src="/images/assets/aboutFill.png" alt="About Fill" class="w-full md:w-1/4 h-auto object-contain rounded-lg shadow-[0_4px_12px_rgba(0,0,0,0.5)]" />

            <!-- Div 2 (Gabungan Div1 dan Deskripsi) -->
            <div class="flex-1 flex flex-col gap-4">
            <!-- Div1 (About Us dan Logo) -->
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">About Us</h3>
                <img src="/images/logos/roundLogo.png" alt="Logo" class="w-16 h-16 object-contain" />
            </div>

            <!-- Deskripsi -->
            <p class="text-base text-gray-600">
                NauTure adalah sebuah website yang memudahkan proses pelelangan hasil panen yang dilakukan secara real-time selama 24 jam. Anda dapat melakukannya kapan saja dan dimana saja tanpa melalui perantara, sehingga proses jual beli dapat menjadi lebih efisien.
            </p>
            </div>
        </div>
      </section>

</x-layout>
