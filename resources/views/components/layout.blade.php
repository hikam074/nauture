<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NauTure</title>
    @vite('resources/css/global.css')
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @include('includes.toastr')
</head>
<body class="flex flex-col min-h-screen">

    {{-- NAVBAR --}}

    <header>
        <nav class="fixed top-0 left-0 right-0 z-50 bg-[#fff] pl-8 py-3 flex items-center justify-between shadow-lg ">

            <!-- Logo -->
            <a href="{{ route('homepage') }}" class="flex items-center">
                <img id="homelink" src="/images/logos/homeLogo.png" alt="[alt]NauTure-Home" class="h-8">
            </a>

            <!-- Tengah (Nav Links) -->
            <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-6 text-[#0F3714] font-semibold">
                <a href="{{ route('homepage') }}" class="hover:text-black hover:[transform:translateY(-2px)_scale(1.1)] transition-transform duration-200">Beranda</a>
                {{-- katalogs link --}}
                <a href="{{ route('katalog.index') }}" class="hover:text-black hover:[transform:translateY(-2px)_scale(1.1)] transition-transform duration-200">Katalog</a>
                {{-- lelangs link --}}
                <a href="{{ route('lelang.index') }}" class="hover:text-black hover:[transform:translateY(-2px)_scale(1.1)] transition-transform duration-200">Lelang</a>
            </div>
            <!-- profile links -->
            <div class="flex items-center space-x-6  text-[#0F3714] pr-8 gap-x-3 font-semibold">
                @if ((Auth::check()) && Auth::user()->email && Auth::user()->name)
                    <div class="relative">
                        <!-- profile dropdown -->
                        <button id="dropdownInformationButton" type="button" class="text-white bg-[#0F3714] focus:ring-4 focus:outline-none focus:ring-white font-medium rounded-full text-sm px-5 py-2.5 text-center inline-flex items-center hover:bg-black hover:shadow-xl transition duration-200">
                            Profil
                            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <!-- profile dropdown menu -->
                        <div id="dropdownInformation" class="absolute z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600 top-full mt-2 right-0">
                            {{-- personal info --}}
                            <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="font-medium truncate text-gray-700">{{ Auth::user()->email }}</div>
                            </div>
                            {{-- tools --}}
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Profil anda
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    Dashboard
                                    </a>
                                </li>
                            </ul>
                            {{-- logout --}}
                            <div class="py-2">
                                <form action="{{ route('logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    @csrf
                                    <button type="submit" class="w-[100%] hover:text-gray-300 focus:outline-none">
                                        Log out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="relative flex items-center bg-white rounded-full">
                        <!-- login button -->
                        <a href="{{ route('login') }}" class="relative z-10 bg-[#0F3714] text-white px-6 py-2 rounded-full hover:bg-black hover:shadow-xl transition duration-200">
                            Login
                        </a>
                        <!-- register button -->
                        <a href="{{ route('register') }}" class="bg-[#638B35] text-white px-6 py-2 pl-10 -ml-8 rounded-full shadow-lg z-0 hover:bg-white hover:text-[#0F3714] hover:shadow-xl transition duration-200">
                            Register
                        </a>
                    </div>
                @endif
            </div>

        </nav>
    </header>

    {{-- CONTENT --}}

    <main class="w-full pt-[4rem] text-[#0F3714] flex-grow">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}

    <footer class="bg-[#C8E6C9] p-5 mt-auto flex flex-col items-center w-full">
        {{-- logo --}}
        <a href="{{ route('homepage') }}" class="flex items-center">
            <img id="homelink" src="/images/logos/homeLogo.png" alt="[alt]NauTure-Home" class="h-20">
        </a>
        {{-- description --}}
        <p class="text-center w-150 py-5">Sistem Lelang Hasil Panen © 2025 - Platform terpercaya untuk perdagangan hasil pertanian secara adil dan efisien.</p>

        <div class="flex justify-between px-4 py-2 w-full">
            {{-- location --}}
            <p class="leading-10 ">
                Location :<br>
                Jl. Tidar No.27 68124 Jember Jawa
            </p>
            {{-- quick links --}}
            <div class="flex gap-8">
                <ul class="flex flex-col gap-5 text-[#0000009d]">
                    <li><a href="{{ route('homepage') }}" class="hover:text-black hover:scale-110 transition-transform duration-200">Beranda</a></li>
                    <li><a href="{{ route('katalog.index') }}" class="hover:text-black hover:scale-110 transition-transform duration-200">Katalog</a></li>
                    <li><a href="{{ route('lelang.index') }}" class="hover:text-black hover:scale-110 transition-transform duration-200">Lelang</a></li>
                </ul>
                {{-- contact --}}
                <div class="flex items-center justify-center">
                    <p class="text-center">
                        Contact Us<br>
                        <a href="" class="text-[#0000009d]">+62 852-2972-8848</a>
                    </p>
                </div>
            </div>
        </div>
        
    </footer>

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

</body>
</html>
