@props(['showSidebar' => false])

<nav class="fixed top-0 left-0 right-0 z-1000 bg-white px-1 py-3 text-primer w-full
    flex items-center justify-between gap-4 shadow-lg
    sm:px-8">

    <!-- Tombol Sidebar (Hanya jika show-sidebar ada) -->
    @if ($showSidebar)
        <div class="flex flex-row">
            <button
                class="flex h-full p-2 bg-aksen text-black rounded-lg cursor-pointer sm:hidden"
                onclick="toggleSidebar()"
                >
                ☰
            </button>
            <!-- LOGO -->
            <a href="{{ route('homepage') }}"
                class="hidden sm:inline sm:transform sm:translate-x-[25px]"
                >
                <img id="homelink" src="{{ asset('/images/logos/homeLogo.png') }}" alt="[NauTure-Home]"
                class="h-8">
            </a>
        </div>
    @else
    <!-- LOGO -->
    <a href="{{ route('homepage') }}" class="">
        <img id="homelink" src="{{ asset('/images/logos/homeLogo.png') }}" alt="[NauTure-Home]"
        class="h-8">
    </a>
    @endif


    <!-- CENTER -->
    <div class="flex gap-2 font-semibold
        sm:gap-5">
        <a href="{{ route('homepage') }}"
            class="hover:text-black hover:[transform:translateY(-2px)_scale(1.1)] transition-transform duration-200"
            >
            Beranda
        </a>
        <a href="{{ route('katalog.index') }}"
            class="hover:text-black hover:[transform:translateY(-2px)_scale(1.1)] transition-transform duration-200"
            >
            Katalog
        </a>
        <a href="{{ route('lelang.index') }}"
            class="hover:text-black hover:[transform:translateY(-2px)_scale(1.1)] transition-transform duration-200"
            >
            Lelang
        </a>
    </div>

    <!-- PROFILE -->
    <div class="flex items-center space-x-6  text-primer gap-x-3 font-semibold">
        @if ((Auth::check()) && Auth::user()->email && Auth::user()->name)
            <div>
                <a href="{{ route('dashboard.notifikasi') }}">
                    <img src="{{ asset('images/icons/navbar/notif-icon.svg') }}" alt="Nav-Notif"
                        class="w-5 h-5"
                    >
                </a>
            </div>
            <div class="relative">
                <!-- IKON PROFIL -->
                <button id="dropdownInformationButton" type="button"
                    class="text-white bg-sekunder font-medium rounded-full text-sm text-center flex gap-2 items-center
                        focus:ring-4 focus:outline-none focus:ring-white
                        transition duration-200 hover:bg-black hover:shadow-xl
                        sm:pl-4"
                    >
                    <span class="hidden sm:inline md:hidden">
                        {{ \Illuminate\Support\Str::limit(Auth::user()->name, 20, '...') }}
                    </span>
                    <span class="hidden md:inline">
                        {{ Auth::user()->name }}
                    </span>
                    <div class="h-10 w-10 p-0.5">
                        <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('images/icons/defaultAvatarLight.png') }}"
                            class="h-full w-full object-cover rounded-full border-2 border-white"
                        >
                    </div>

                </button>
                <!-- MENU PROFIL -->
                <div id="dropdownInformation"
                    class="absolute z-1000 hidden bg-white divide-y divide-gray-200 rounded-lg shadow-lg w-44 right-0 mt-0.5 border-t-10">
                    <!-- INFO -->
                    <div class="px-4 py-3 text-sm bg-white rounded-t-lg cursor-default">
                        <p class="font-bold">{{ Auth::user()->name }}</p>
                        <p class="text-xs break-all text-gray-400 font-light">{{ Auth::user()->email }}</p>
                        <p class="capitalize pt-2">{{ Auth::user()->role->nama_role }}</p>
                    </div>
                    <!-- TOOLS -->
                    <ul class="text-sm text-gray-700">
                        <li>
                            <a href="{{ route('dashboard.index') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <img src="{{ asset('images/icons/navbar/dashboardDark-icon.svg') }}" alt="Nav-Dashboard"
                                    class="h-5 w-5"
                                >
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profil.index') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <img src="{{ asset('images/icons/defaultAvatarDark.svg') }}" alt="Nav-Profil"
                                    class="h-5 w-5"
                                >
                                Profil anda
                            </a>
                        </li>
                        @if (Auth::user()->role->nama_role == 'customer')
                        <li>
                            <a href="{{ route('lelang.saya') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <img src="{{ asset('images/icons/navbar/lelangDark-icon.svg') }}" alt="Nav-Lelang"
                                    class="h-5 w-5"
                                >
                                Lelang Anda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transaksi.index') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <img src="{{ asset('images/icons/navbar/transaksiDark-icon.svg') }}" alt="Nav-Transaksi"
                                    class="h-5 w-5"
                                >
                                Transaksi Anda
                            </a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('dashboard.katalog') }}" class="flex gap-2 px-4 py-2 items-center hover:bg-gray-200">
                                <img src="{{ asset('images/icons/navbar/katalogDark-icon.svg') }}" alt="Nav-Katalog"
                                    class="h-6 w-6"
                                >
                                <span>Manajemen Katalog</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.lelang') }}" class="flex gap-2 px-4 py-2 items-center hover:bg-gray-200">
                                <img src="{{ asset('images/icons/navbar/lelangDark-icon.svg') }}" alt="Nav-Lelang"
                                    class="h-5 w-5"
                                >
                                <span>Manajemen Lelang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.transaksi') }}" class="flex gap-2 px-4 py-2 items-center hover:bg-gray-200">
                                <img src="{{ asset('images/icons/navbar/transaksiDark-icon.svg') }}" alt="Nav-Transaksi"
                                    class="h-5 w-5"
                                >
                                <span>Manajemen Transaksi</span>
                            </a>
                        </li>
                            @if (Auth::user()->role->nama_role == 'owner')
                            <li>
                                <a href="{{ route('pegawai.index') }}" class="flex gap-2 px-4 py-2 items-center hover:bg-gray-200">
                                    <img src="{{ asset('images/icons/navbar/employeeDark-icon.svg') }}" alt="Nav-Pegawai"
                                        class="h-5 w-5"
                                    >
                                    <span>Pegawai Anda</span>
                                </a>
                            </li>

                            @endif

                        @endif
                    </ul>
                    <!-- LOG OUT -->
                    <div class="text-sm text-gray-700">
                        <a href="{{ route('logout') }}">
                            <button
                                class="w-[100%] py-2 text-red-700 rounded-b-lg cursor-pointer
                                    hover:text-white hover:bg-red-700"
                                >
                                Log out
                            </button>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <div class="flex items-center gap-2">
                <!-- login button -->
                <a href="{{ route('login') }}"
                    class="py-2 px-4 rounded-lg border-1
                    hover:bg-primer hover:text-white hover:shadow-xl transition duration-200"
                    >
                    Login
                </a>
                <!-- register button -->
                <a href="{{ route('register') }}"
                    class="hidden sm:inline py-2 px-4 rounded-lg border-1
                    hover:bg-primer hover:text-white hover:shadow-xl transition duration-200"
                    >
                    Register
                </a>
            </div>
        @endif
    </div>

</nav>

<script>
    const dropdownButton = document.getElementById('dropdownInformationButton');
    const dropdownMenu = document.getElementById('dropdownInformation');

    if (dropdownButton && dropdownMenu) {
    dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden'); // Menampilkan atau menyembunyikan menu
    });

    // Menutup dropdown saat klik di luar
    window.addEventListener('click', (event) => {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden'); // Menyembunyikan menu
        }
    });}
</script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");

        sidebar.classList.toggle("-translate-x-full");
    }
</script>
