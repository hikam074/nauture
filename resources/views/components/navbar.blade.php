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
                    <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier"> <path d="M22 12C22 6.49 17.51 2 12 2C6.49 2 2 6.49 2 12C2 14.9 3.25 17.51 5.23 19.34C5.23 19.35 5.23 19.35 5.22 19.36C5.32 19.46 5.44 19.54 5.54 19.63C5.6 19.68 5.65 19.73 5.71 19.77C5.89 19.92 6.09 20.06 6.28 20.2C6.35 20.25 6.41 20.29 6.48 20.34C6.67 20.47 6.87 20.59 7.08 20.7C7.15 20.74 7.23 20.79 7.3 20.83C7.5 20.94 7.71 21.04 7.93 21.13C8.01 21.17 8.09 21.21 8.17 21.24C8.39 21.33 8.61 21.41 8.83 21.48C8.91 21.51 8.99 21.54 9.07 21.56C9.31 21.63 9.55 21.69 9.79 21.75C9.86 21.77 9.93 21.79 10.01 21.8C10.29 21.86 10.57 21.9 10.86 21.93C10.9 21.93 10.94 21.94 10.98 21.95C11.32 21.98 11.66 22 12 22C12.34 22 12.68 21.98 13.01 21.95C13.05 21.95 13.09 21.94 13.13 21.93C13.42 21.9 13.7 21.86 13.98 21.8C14.05 21.79 14.12 21.76 14.2 21.75C14.44 21.69 14.69 21.64 14.92 21.56C15 21.53 15.08 21.5 15.16 21.48C15.38 21.4 15.61 21.33 15.82 21.24C15.9 21.21 15.98 21.17 16.06 21.13C16.27 21.04 16.48 20.94 16.69 20.83C16.77 20.79 16.84 20.74 16.91 20.7C17.11 20.58 17.31 20.47 17.51 20.34C17.58 20.3 17.64 20.25 17.71 20.2C17.91 20.06 18.1 19.92 18.28 19.77C18.34 19.72 18.39 19.67 18.45 19.63C18.56 19.54 18.67 19.45 18.77 19.36C18.77 19.35 18.77 19.35 18.76 19.34C20.75 17.51 22 14.9 22 12ZM16.94 16.97C14.23 15.15 9.79 15.15 7.06 16.97C6.62 17.26 6.26 17.6 5.96 17.97C4.44 16.43 3.5 14.32 3.5 12C3.5 7.31 7.31 3.5 12 3.5C16.69 3.5 20.5 7.31 20.5 12C20.5 14.32 19.56 16.43 18.04 17.97C17.75 17.6 17.38 17.26 16.94 16.97Z" fill="#ffffff"/> <path d="M12 6.92969C9.93 6.92969 8.25 8.60969 8.25 10.6797C8.25 12.7097 9.84 14.3597 11.95 14.4197C11.98 14.4197 12.02 14.4197 12.04 14.4197C12.06 14.4197 12.09 14.4197 12.11 14.4197C12.12 14.4197 12.13 14.4197 12.13 14.4197C14.15 14.3497 15.74 12.7097 15.75 10.6797C15.75 8.60969 14.07 6.92969 12 6.92969Z" fill="#ffffff"/> </g>
                    </svg>
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
                            <a href="#" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" stroke="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                    <g id="SVGRepo_iconCarrier"> <title>Dashboard</title> <g id="Dashboard" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <rect id="Container" x="0" y="0" width="24" height="24"> </rect> <rect id="shape-1" stroke="#000" stroke-width="2" stroke-linecap="round" x="4" y="4" width="16" height="16" rx="2"> </rect> <line x1="4" y1="9" x2="20" y2="9" id="shape-2" stroke="#000" stroke-width="2" stroke-linecap="round"> </line> <line x1="9" y1="10" x2="9" y2="20" id="shape-3" stroke="#000" stroke-width="2" stroke-linecap="round"> </line> </g> </g>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profil.index') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <svg width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="2 2 20 20">
                                    <path d="M22 12C22 6.49 17.51 2 12 2C6.49 2 2 6.49 2 12C2 14.9 3.25 17.51 5.23 19.34C5.23 19.35 5.23 19.35 5.22 19.36C5.32 19.46 5.44 19.54 5.54 19.63C5.6 19.68 5.65 19.73 5.71 19.77C5.89 19.92 6.09 20.06 6.28 20.2C6.35 20.25 6.41 20.29 6.48 20.34C6.67 20.47 6.87 20.59 7.08 20.7C7.15 20.74 7.23 20.79 7.3 20.83C7.5 20.94 7.71 21.04 7.93 21.13C8.01 21.17 8.09 21.21 8.17 21.24C8.39 21.33 8.61 21.41 8.83 21.48C8.91 21.51 8.99 21.54 9.07 21.56C9.31 21.63 9.55 21.69 9.79 21.75C9.86 21.77 9.93 21.79 10.01 21.8C10.29 21.86 10.57 21.9 10.86 21.93C10.9 21.93 10.94 21.94 10.98 21.95C11.32 21.98 11.66 22 12 22C12.34 22 12.68 21.98 13.01 21.95C13.05 21.95 13.09 21.94 13.13 21.93C13.42 21.9 13.7 21.86 13.98 21.8C14.05 21.79 14.12 21.76 14.2 21.75C14.44 21.69 14.69 21.64 14.92 21.56C15 21.53 15.08 21.5 15.16 21.48C15.38 21.4 15.61 21.33 15.82 21.24C15.9 21.21 15.98 21.17 16.06 21.13C16.27 21.04 16.48 20.94 16.69 20.83C16.77 20.79 16.84 20.74 16.91 20.7C17.11 20.58 17.31 20.47 17.51 20.34C17.58 20.3 17.64 20.25 17.71 20.2C17.91 20.06 18.1 19.92 18.28 19.77C18.34 19.72 18.39 19.67 18.45 19.63C18.56 19.54 18.67 19.45 18.77 19.36C18.77 19.35 18.77 19.35 18.76 19.34C20.75 17.51 22 14.9 22 12ZM16.94 16.97C14.23 15.15 9.79 15.15 7.06 16.97C6.62 17.26 6.26 17.6 5.96 17.97C4.44 16.43 3.5 14.32 3.5 12C3.5 7.31 7.31 3.5 12 3.5C16.69 3.5 20.5 7.31 20.5 12C20.5 14.32 19.56 16.43 18.04 17.97C17.75 17.6 17.38 17.26 16.94 16.97Z" fill="#292D32"/>
                                    <path d="M12 6.92969C9.93 6.92969 8.25 8.60969 8.25 10.6797C8.25 12.7097 9.84 14.3597 11.95 14.4197C11.98 14.4197 12.02 14.4197 12.04 14.4197C12.06 14.4197 12.09 14.4197 12.11 14.4197C12.12 14.4197 12.13 14.4197 12.13 14.4197C14.15 14.3497 15.74 12.7097 15.75 10.6797C15.75 8.60969 14.07 6.92969 12 6.92969Z" fill="#292D32"/>
                                    </svg>
                                Profil anda
                            </a>
                        </li>
                        @if (Auth::user()->role->nama_role == 'customer')
                        <li>
                            <a href="{{ route('lelang.saya') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                    <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M4.58968 11.411L12.1067 3.894C12.3654 3.63533 12.7848 3.63533 13.0435 3.89401L17.2196 8.07013C17.4783 8.32881 17.4783 8.7482 17.2196 9.00687L9.70255 16.5239C9.44388 16.7826 9.02448 16.7826 8.76581 16.5239L4.58968 12.3478C4.33101 12.0891 4.33101 11.6697 4.58968 11.411ZM3.3876 13.5499C2.46504 12.6273 2.46504 11.1315 3.3876 10.209L10.9046 2.69192C11.8272 1.76936 13.323 1.76936 14.2455 2.69192L18.4217 6.86805C19.3442 7.79062 19.3442 9.28639 18.4217 10.209L16.9298 11.7008L20.2756 15.0466C21.5274 16.2984 21.5274 18.328 20.2756 19.5798C19.0237 20.8316 16.9942 20.8316 15.7423 19.5798L12.3966 16.234L10.9046 17.726C9.98207 18.6486 8.48629 18.6485 7.56373 17.726L3.3876 13.5499ZM13.5986 15.032L16.9444 18.3777C17.5323 18.9657 18.4856 18.9657 19.0735 18.3777C19.6614 17.7898 19.6614 16.8366 19.0735 16.2487L15.7277 12.9029L13.5986 15.032Z" fill="#000"/> <path d="M2.89832 21.1017H7.62306" stroke="#000" stroke-width="1.7" stroke-linecap="round"/> </g>
                                </svg>
                                Lelang Anda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transaksi.index') }}" class="flex gap-2 px-4 py-2 hover:bg-gray-200">
                                <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                    <g id="SVGRepo_iconCarrier">
                                    <path d="M914.29 219.43c0-80.66-65.62-146.29-146.29-146.29H274.29c-84.57 0-155.45 64.11-164.57 146.29v475.43h146.29v256h585.14V346.08c43.69-25.34 73.14-72.62 73.14-126.65z m-585.14 73.14c0-19.55 15.43-35.57 34.75-36.53a90.403 90.403 0 0 1-16.44 36.53h-18.31zM182.87 621.71v-384c0-50.41 41.02-91.43 91.43-91.43 30.46 0 57.48 14.97 74.1 37.94-52.3 8.33-92.39 53.74-92.39 108.34v329.15h-73.14z m585.14 256H329.15V365.72h438.86v511.99z m0-585.14H429.54c6.12-17.37 9.32-35.86 9.32-54.86 0-33.81-10.25-65.26-27.8-91.43H768c40.34 0 73.14 32.8 73.14 73.14s-32.79 73.15-73.13 73.15z" fill="#000"/>
                                    <path d="M416.392 762.75L635.83 449.3l44.933 31.456-219.438 313.45zM457.15 588.27c42.34 0 76.8-34.46 76.8-76.8s-34.46-76.8-76.8-76.8c-42.34 0-76.8 34.46-76.8 76.8s34.46 76.8 76.8 76.8z m0-109.72c18.14 0 32.91 14.77 32.91 32.91 0 18.14-14.77 32.91-32.91 32.91-18.14 0-32.91-14.77-32.91-32.91 0-18.14 14.77-32.91 32.91-32.91zM563.2 731.46c0 42.34 34.46 76.8 76.8 76.8s76.8-34.46 76.8-76.8-34.46-76.8-76.8-76.8-76.8 34.46-76.8 76.8z m109.72 0c0 18.14-14.77 32.91-32.91 32.91s-32.91-14.77-32.91-32.91c0-18.14 14.77-32.91 32.91-32.91s32.91 14.77 32.91 32.91z" fill="#000"/>
                                    </g>
                                </svg>
                                Transaksi Anda
                            </a>
                        </li>
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
