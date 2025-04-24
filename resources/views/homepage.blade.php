<x-layout>

    {{-- SELAMAT DATANG --}}
        <section class="welcoming w-full bg-amber-800">
         <div class="w-full mx-auto rounded-lg h-[100vh] relative bg-cover bg-center flex flex-col justify-center items-center text-white"
            style="background-image: url('/images/assets/homepageFill.png');">
            @if (Auth::user())
            <h1 class="text-5xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
            @else
            <h1 class="text-5xl font-bold mb-2">Selamat Datang, Pengunjung!</h1>
            @endif
            <p class="text-2xl mb-4 text-center">Dengan NauTure, pelelangan hasil panen dapat diakses kapan saja dan dimana saja</p>
            <a class="font-bold px-4 py-2 bg-[#CEF17B] hover:bg-white text-[#0F3714] rounded-lg shadow-lg transition duration-300 hover:shadow-xl transform hover:scale-102" href="{{ route('lelang.index') }}">
                Menuju Lelang Sekarang!
            </a>
        </div>
    </section>

    {{-- MAIN --}}

    {{-- KATALOGS --}}
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
                    <div class="flex-none w-48 bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-transform transform hover:scale-102  hover:shadow-2xl"
                        onclick="window.location.href='{{ route('katalog.show', ['id' => $katalog->id]) }}'">
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

    {{-- LELANGS --}}

    <section class="lelang w-[90%] mx-auto mt-4 text-[#0F3714]">
        <!-- Header Lelang -->
        <div class="lelang-head flex justify-between items-center">
            <!-- Judul Lelang -->
            <h2 class="text-xl font-bold pl-3">Lelang Aktif</h2>

            <div class="flex items-center space-x-4">
                <!-- Link Lihat Lebih Banyak -->
                <a href="{{ route('lelang.index') }}"  class="px-4 py-2 text-sm font-medium border border-[#0F3714] rounded-lg shadow-lg hover:bg-[#0F3714] hover:text-white transition hover:shadow-xl">
                    Lihat Lebih Banyak...
                </a>
                <!-- Tombol Tambah Lelang -->
                @if (Auth::check() && Auth::user()->email && Auth::user()->name && Auth::user()->role->nama_role == 'pegawai')
                    <a href="{{ route('lelang.add') }}" class="px-4 py-2 text-sm font-medium text-white bg-[#0F3714] rounded-lg shadow-lg hover:bg-black transition">
                        Tambah Lelang
                    </a>
                @endif
            </div>
        </div>
        <!-- Cards Lelang -->
        <div class="relative overflow-hidden p-2">
            <div class="flex sm:grid sm:grid-cols-2 md:flex md:space-x-4">
                @forelse ($lelangs->take(6) as $lelang)
                    <div class="flex-none w-48 bg-white rounded-lg shadow-lg p-4 cursor-pointer transition-transform transform hover:scale-102 hover:shadow-2xl"
                        onclick="window.location.href='{{ route('lelang.show', ['id' => $lelang->id]) }}'">
                        <img src="{{ asset('storage/' . $lelang->katalog->foto_produk) }}" alt="{{ $lelang->katalog->nama_produk }}" class="w-full h-40 object-cover rounded-md">
                        <h2 class="text-center text-[#0F3714] text-lg font-semibold mt-2">{{ $lelang->nama_produk_lelang }}</h2>
                        <p class="text-center text-[#0F3714] font-thin text-xs">Harga Awal: Rp{{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p>Produk Lelang Kosong.</p>
                @endforelse
            </div>
        </div>
    </section>

     {{-- WHY NAUTURE? --}}

     <section class="bg-[#fff] mb-12">
        <div class="w-[90%] mx-auto mt-8 flex flex-col items-top gap-12 p-6 md:flex-row">
            <div class="flex-1 flex flex-col gap-4">
                <!-- Div1 (About Us dan Logo) -->
                <div class="flex flex-col justify-between items-center">
                    <img src="/images/logos/roundLogo.png" alt="Logo" class="w-16 h-16 object-contain" />
                    <h3 class="text-5xl font-bold">Kenapa Harus NauTure?</h3>
                </div>
                <!-- Deskripsi -->
                <p class="text-base text-gray-600 text-center">
                    NauTure adalah sebuah website yang memudahkan proses pelelangan hasil panen yang dilakukan secara real-time selama 24 jam. Anda dapat melakukannya kapan saja dan dimana saja tanpa melalui perantara, sehingga proses jual beli dapat menjadi lebih efisien.
                </p>
            </div>
        </div>

        {{-- GRID KENAPA --}}

        <div class="grid grid-cols-3 place-items-center text-center gap-4 p-10">

            {{-- GRID TRANSPARANSI --}}
            <div class="h-full border-2 p-10 flex flex-col items-center gap-3 hover:scale-105 transition-transform duration-200">
                {{-- SVG SEARCH --}}
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="64" height="64" viewBox="0 0 64 64">
                    <path d="M 25 0 C 11.214844 0 0 11.214844 0 25 C 0 38.785156 11.214844 50 25 50 C 30.515625 50 35.613281 48.203125 39.75 45.167969 C 39.75 45.167969 42.527344 47.886719 42.660156 47.933594 C 42.660156 47.957031 42.65625 47.976563 42.65625 48 C 42.65625 49.335938 43.175781 50.59375 44.121094 51.535156 L 54.464844 61.878906 C 55.441406 62.855469 56.71875 63.339844 58 63.339844 C 59.28125 63.339844 60.5625 62.855469 61.535156 61.878906 L 61.878906 61.53125 C 62.824219 60.589844 63.34375 59.335938 63.34375 58 C 63.34375 56.664063 62.824219 55.40625 61.878906 54.464844 L 51.535156 44.121094 C 50.542969 43.128906 49.238281 42.648438 47.9375 42.667969 C 47.886719 42.53125 45.167969 39.75 45.167969 39.75 C 48.203125 35.613281 50 30.515625 50 25 C 50 11.214844 38.785156 0 25 0 Z M 25 2 C 37.683594 2 48 12.316406 48 25 C 48 37.683594 37.683594 48 25 48 C 12.316406 48 2 37.683594 2 25 C 2 12.316406 12.316406 2 25 2 Z M 25 6 C 19.925781 6 15.152344 7.976563 11.5625 11.5625 C 4.15625 18.972656 4.15625 31.027344 11.5625 38.433594 C 15.152344 42.023438 19.925781 44 25 44 C 30.074219 44 34.847656 42.023438 38.4375 38.4375 C 45.84375 31.027344 45.84375 18.972656 38.4375 11.5625 C 34.847656 7.976563 30.074219 6 25 6 Z M 25 8 C 29.539063 8 33.808594 9.769531 37.019531 12.980469 C 43.648438 19.605469 43.648438 30.394531 37.019531 37.019531 C 33.808594 40.230469 29.539063 42 25 42 C 20.460938 42 16.191406 40.230469 12.980469 37.019531 C 6.351563 30.394531 6.351563 19.609375 12.980469 12.980469 C 16.191406 9.769531 20.460938 8 25 8 Z M 24.808594 11 C 21.171875 11.042969 17.703125 12.496094 15.097656 15.097656 C 14.707031 15.492188 14.707031 16.121094 15.097656 16.515625 C 15.292969 16.707031 15.550781 16.808594 15.808594 16.808594 C 16.0625 16.808594 16.320313 16.707031 16.515625 16.515625 C 19.0625 13.964844 22.582031 12.703125 26.179688 13.058594 C 26.730469 13.105469 27.21875 12.710938 27.273438 12.160156 C 27.328125 11.609375 26.925781 11.121094 26.375 11.066406 C 25.851563 11.015625 25.328125 10.992188 24.808594 11 Z M 31.859375 12.875 C 31.476563 12.820313 31.074219 12.996094 30.859375 13.351563 C 30.574219 13.824219 30.726563 14.4375 31.203125 14.722656 C 32.023438 15.222656 32.792969 15.824219 33.484375 16.515625 C 33.679688 16.710938 33.933594 16.808594 34.191406 16.808594 C 34.449219 16.808594 34.703125 16.710938 34.902344 16.515625 C 35.289063 16.125 35.289063 15.492188 34.902344 15.101563 C 34.09375 14.296875 33.195313 13.59375 32.234375 13.011719 C 32.117188 12.941406 31.988281 12.894531 31.859375 12.875 Z M 11 24 C 10.449219 24 10 24.445313 10 25 C 10 25.554688 10.449219 26 11 26 L 13 26 C 13.550781 26 14 25.554688 14 25 C 14 24.445313 13.550781 24 13 24 Z M 37 24 C 36.449219 24 36 24.445313 36 25 C 36 25.554688 36.449219 26 37 26 L 39 26 C 39.550781 26 40 25.554688 40 25 C 40 24.445313 39.550781 24 39 24 Z M 13.890625 28.496094 C 13.761719 28.492188 13.625 28.515625 13.5 28.566406 L 11.644531 29.316406 C 11.132813 29.523438 10.886719 30.105469 11.09375 30.617188 C 11.25 31.007813 11.625 31.246094 12.019531 31.246094 C 12.144531 31.246094 12.273438 31.222656 12.394531 31.171875 L 14.25 30.421875 C 14.761719 30.214844 15.007813 29.632813 14.800781 29.121094 C 14.644531 28.738281 14.28125 28.5 13.890625 28.496094 Z M 36.046875 28.6875 C 35.65625 28.6875 35.285156 28.917969 35.125 29.296875 C 34.910156 29.808594 35.144531 30.394531 35.65625 30.609375 L 37.496094 31.390625 C 37.621094 31.445313 37.753906 31.46875 37.886719 31.46875 C 38.277344 31.46875 38.644531 31.242188 38.808594 30.859375 C 39.023438 30.351563 38.785156 29.765625 38.277344 29.550781 L 36.4375 28.769531 C 36.308594 28.714844 36.175781 28.6875 36.046875 28.6875 Z M 16.515625 32.484375 C 16.257813 32.484375 16.003906 32.582031 15.808594 32.777344 L 14.394531 34.191406 C 14.003906 34.582031 14.003906 35.214844 14.394531 35.605469 C 14.589844 35.800781 14.84375 35.898438 15.101563 35.898438 C 15.359375 35.898438 15.613281 35.800781 15.808594 35.605469 L 17.222656 34.191406 C 17.613281 33.800781 17.613281 33.167969 17.222656 32.777344 C 17.027344 32.582031 16.769531 32.484375 16.515625 32.484375 Z M 33.484375 32.484375 C 33.230469 32.484375 32.972656 32.582031 32.777344 32.777344 C 32.386719 33.167969 32.386719 33.800781 32.777344 34.191406 L 34.191406 35.605469 C 34.386719 35.800781 34.640625 35.898438 34.898438 35.898438 C 35.15625 35.898438 35.410156 35.800781 35.605469 35.605469 C 35.996094 35.214844 35.996094 34.582031 35.605469 34.191406 L 34.191406 32.777344 C 33.996094 32.582031 33.738281 32.484375 33.484375 32.484375 Z M 20.3125 35.046875 C 19.921875 35.046875 19.550781 35.273438 19.390625 35.65625 L 18.609375 37.496094 C 18.394531 38.003906 18.632813 38.59375 19.140625 38.808594 C 19.269531 38.863281 19.398438 38.886719 19.53125 38.886719 C 19.917969 38.886719 20.289063 38.65625 20.453125 38.277344 L 21.234375 36.4375 C 21.449219 35.929688 21.210938 35.339844 20.703125 35.125 C 20.574219 35.070313 20.441406 35.046875 20.3125 35.046875 Z M 29.511719 35.125 C 29.382813 35.125 29.246094 35.148438 29.121094 35.199219 C 28.605469 35.40625 28.359375 35.988281 28.566406 36.5 L 29.316406 38.355469 C 29.472656 38.746094 29.847656 38.980469 30.246094 38.980469 C 30.371094 38.980469 30.496094 38.957031 30.621094 38.90625 C 31.132813 38.699219 31.378906 38.117188 31.171875 37.605469 L 30.421875 35.75 C 30.265625 35.367188 29.902344 35.132813 29.511719 35.125 Z M 25 36 C 24.449219 36 24 36.445313 24 37 L 24 39 C 24 39.554688 24.449219 40 25 40 C 25.550781 40 26 39.554688 26 39 L 26 37 C 26 36.445313 25.550781 36 25 36 Z M 43.910156 41.324219 L 45.785156 43.203125 C 45.3125 43.4375 44.859375 43.730469 44.464844 44.125 L 44.121094 44.464844 C 43.730469 44.855469 43.4375 45.304688 43.199219 45.78125 L 41.324219 43.910156 C 42.25 43.113281 43.113281 42.25 43.910156 41.324219 Z M 48 44.65625 C 48.769531 44.65625 49.539063 44.949219 50.125 45.535156 L 60.46875 55.878906 C 61.035156 56.445313 61.34375 57.199219 61.34375 58 C 61.34375 58.800781 61.03125 59.554688 60.464844 60.121094 L 60.121094 60.464844 C 58.949219 61.632813 57.050781 61.632813 55.878906 60.464844 L 45.535156 50.121094 C 44.96875 49.554688 44.65625 48.800781 44.65625 48 C 44.65625 47.199219 44.96875 46.445313 45.535156 45.878906 L 45.878906 45.535156 C 46.464844 44.949219 47.234375 44.65625 48 44.65625 Z"></path>
                    </svg>
                <h1 class="text-lg font-bold mb-2">Transparansi Harga & Proses Lelang</h1>
                <p class="text-base">Semua proses lelang dilakukan secara terbuka dan real-time, sehingga petani dan pembeli bisa melihat harga pasar yang sebenarnya—tanpa manipulasi, tanpa perantara yang merugikan.</p>
            </div>

            {{-- GRID PETANI --}}
            <div class="h-full border-2 p-10 flex flex-col items-center gap-3 hover:scale-105 transition-transform duration-200">
                {{-- SVG PETANI --}}
                <?xml version="1.0" encoding="iso-8859-1"?>
                <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                <svg fill="#000000" height="64px" width="64px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 297 297" xml:space="preserve">
                <g>
                    <path d="M113.986,209.155c-2.197,0-4.361,0.889-5.909,2.447c-1.558,1.558-2.457,3.723-2.457,5.919c0,2.196,0.899,4.351,2.457,5.919c1.548,1.557,3.712,2.447,5.909,2.447c2.206,0,4.361-0.89,5.919-2.447c1.557-1.558,2.447-3.713,2.447-5.919c0-2.207-0.89-4.361-2.447-5.919C118.347,210.043,116.192,209.155,113.986,209.155z"/>
                    <path d="M267.206,185.582c-2.057-16.465-11.21-30.85-24.486-38.482l-49.029-28.182c10.546-11.153,17.033-26.182,17.033-42.707c0-1.178-0.042-2.354-0.109-3.529h58.672c5.775,0,10.458-4.682,10.458-10.458s-4.682-10.458-10.458-10.458h-46.3C211.528,20.982,181.782,0,148.5,0S85.472,20.982,74.013,51.766h-46.3c-5.775,0-10.458,4.682-10.458,10.458s4.682,10.458,10.458,10.458h58.672c-0.067,1.175-0.109,2.352-0.109,3.529c0,16.525,6.487,31.554,17.033,42.707L54.28,147.1c-13.274,7.63-22.428,22.016-24.487,38.482l-12.457,99.663c-0.372,2.976,0.553,5.969,2.537,8.218c1.985,2.249,4.841,3.537,7.84,3.537h241.574c2.999,0,5.855-1.288,7.84-3.537c1.985-2.249,2.909-5.242,2.537-8.218L267.206,185.582z M89.937,193.468h117.127v82.616H89.937V193.468z M107.192,172.553v-31.742l14.619-8.403c8.093,3.859,17.142,6.026,26.69,6.026s18.596-2.167,26.69-6.026l14.619,8.403v31.742H107.192z M148.5,20.915c21.248,0,40.54,11.606,50.806,29.456H97.694C107.96,32.521,127.252,20.915,148.5,20.915z M189.656,72.681c0.101,1.174,0.152,2.351,0.152,3.529c0,22.777-18.531,41.308-41.308,41.308s-41.308-18.531-41.308-41.308c0-1.179,0.051-2.355,0.152-3.529H189.656z M50.547,188.176c1.249-9.989,6.54-18.566,14.156-22.943l21.573-12.4v19.72h-6.798c-5.775,0-10.458,4.683-10.458,10.458v93.074H39.56L50.547,188.176z M227.979,276.085v-93.074c0-5.775-4.682-10.458-10.458-10.458h-6.798v-19.72l21.573,12.4c7.616,4.377,12.907,12.955,14.155,22.943l10.989,87.908H227.979z"/>
                    <path d="M183.007,209.155c-2.197,0-4.361,0.889-5.909,2.447c-1.558,1.558-2.457,3.712-2.457,5.919c0,2.206,0.899,4.361,2.457,5.919c1.548,1.557,3.712,2.447,5.909,2.447c2.206,0,4.361-0.89,5.919-2.447c1.557-1.558,2.447-3.724,2.447-5.919c0-2.207-0.89-4.361-2.447-5.919C187.368,210.043,185.213,209.155,183.007,209.155z"/>
                </g>
                </svg>
                <h1 class="text-lg font-bold mb-2">Langsung dari Petani</h1>
                <p class="text-base">Kami menghubungkan langsung antara petani dan pembeli besar, memotong rantai distribusi yang panjang dan memastikan hasil bumi sampai dengan segar dan harga yang adil.</p>
            </div>

            {{-- GRID PENDAPATAN --}}
            <div class="h-full border-2 p-10 flex flex-col items-center gap-3 hover:scale-105 transition-transform duration-200">
                {{-- SVG GRAPH--}}
                <?xml version="1.0" ?><svg id="Layer_1" height="64px" width="64px" style="enable-background:new 0 0 256 256;" version="1.1" viewBox="0 0 256 256" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M29.4,190.9c2.8,0,5-2.2,5-5v-52.3l30.2-16.2v42.9c0,2.8,2.2,5,5,5c2.8,0,5-2.2,5-5v-59.6l-50.1,26.9v58.3   C24.4,188.6,26.6,190.9,29.4,190.9z"/><path d="M89.6,153c2.8,0,5-2.2,5-5V59.6l30.2-16.2v143.8c0,2.8,2.2,5,5,5c2.8,0,5-2.2,5-5V26.7L84.6,53.6V148   C84.6,150.7,86.8,153,89.6,153z"/><path d="M149.8,185.7c2.8,0,5-2.2,5-5V85.4L185,69.2v86.3c0,2.8,2.2,5,5,5c2.8,0,5-2.2,5-5v-103l-50.1,26.9v101.3   C144.8,183.5,147.1,185.7,149.8,185.7z"/><path d="M250,146.2c-0.9-1.5-2.5-2.5-4.3-2.5h-34.5c-1.8,0-3.4,1-4.3,2.5c-0.9,1.5-0.9,3.4,0,5l6.2,10.7l-27.5,16.7   c-4.2,2.6-8.3,5.1-12.5,7.7c-3,1.9-6,3.7-8.9,5.6c-7.8,5-14.7,9.5-21.1,13.9c-3.3,2.2-6.5,4.5-9.8,6.7l-44.8-43.8L7.7,220.1   c-2.3,1.5-3,4.6-1.5,6.9c1,1.5,2.6,2.3,4.2,2.3c0.9,0,1.8-0.3,2.7-0.8l74-47.1l45.2,44.1l6.1-4.4c3.4-2.4,6.8-4.8,10.3-7.1   c6.3-4.3,13.1-8.7,20.9-13.7c2.9-1.9,5.9-3.7,8.8-5.6c4.1-2.6,8.3-5.1,12.4-7.7l25.3-15.5l2-1.2l6.1,10.6c0.9,1.5,2.5,2.5,4.3,2.5   s3.4-1,4.3-2.5l17.3-29.9C250.9,149.7,250.9,147.8,250,146.2z M228.4,168.6l-8.6-14.9H237L228.4,168.6z"/></g></svg>
                <h1 class="text-lg font-bold mb-2">Meningkatkan Pendapatan Petani</h1>
                <p class="text-base">Dengan sistem lelang kompetitif, petani berpeluang mendapatkan harga lebih tinggi dibanding jual ke tengkulak, mendorong pertumbuhan ekonomi lokal.</p>
            </div>
        </div>
        {{-- <img src="/images/assets/aboutFill.png" alt="About Fill" class="w-full md:w-1/4 h-auto object-contain rounded-lg shadow-[0_4px_12px_rgba(0,0,0,0.5)]" /> --}}
    </section>

</x-layout>
