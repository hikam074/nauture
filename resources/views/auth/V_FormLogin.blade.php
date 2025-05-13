<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NauTure | Login</title>
    @vite('resources/css/global.css')
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @include('includes.toastr')
    <style>
        body {
            background-image: url("{{ asset('images/backgrounds/signinBG.png') }}") !important;
            background-repeat: no-repeat; /* Gambar tidak diulang */
            background-position: center center; /* Gambar selalu di tengah */
            background-size: auto 100%; /* Tinggi penuh, lebar menyesuaikan */
            background-size: cover; /* Fallback untuk mengisi viewport */
        }
    </style>
</head>
<body class="flex justify-center items-center h-screen text-white">

    <div class="m-6 grid place-items-center h-[90%]">
        {{-- ikon nauture : homepage --}}
        <a href="{{ route('homepage') }}">
            <img class="h-20 w-auto mb-8" src="images/logos/homeLogo.png" alt="[alt]NauTure-Home">
        </a>
        {{-- judul --}}
        <h1 id="title" class="text-2xl">Log In</h1>
        {{-- ucapan --}}
        <p id="ucapan">Selamat Datang Kembali! Silahkan Log-in ke akun anda</p>

        {{-- FORM LOGIN --}}
        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            {{-- email --}}
            <div class="email mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-[rgba(255,250,250,0.5)] text-white p-3 rounded-lg border-2 border-transparent
                    focus:outline-none focus:border-white focus:shadow-lg focus:shadow-[rgba(15,55,20,0.5)]
                    transition">
            </div>

            {{-- password --}}
            <div class="password mb-4">
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" name="password"
                        class="w-full bg-[rgba(255,250,250,0.5)] text-white p-3 rounded-lg border-2 border-transparent focus:outline-none focus:border-white transition">
                    <span id="toggleIcon" class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer flex items-center justify-center w-6 h-6 hover:text-[rgba(15,55,20,1)] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon">
                            <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
            {{-- ingat saya --}}
            <div class="pb-4">
                <label class="flex items-center gap-2 cursor-pointer text-base select-none">
                    <input
                        type="checkbox"
                        id="rememberMe"
                        name="remember"
                        class="hidden peer"
                    >
                    <span
                        class="w-5 h-5 border border-white rounded-md relative flex-shrink-0 peer-checked:after:content-[''] peer-checked:after:absolute peer-checked:after:top-[3px] peer-checked:after:left-[7px] peer-checked:after:w-[6px] peer-checked:after:h-[12px] peer-checked:after:border-[2px] peer-checked:after:border-white peer-checked:after:border-b-2 peer-checked:after:border-r-2 peer-checked:after:transform peer-checked:after:rotate-45 transition-opacity"
                    ></span>
                    <span class="text-white">Ingat Saya</span>
                </label>
            </div>
            {{-- submit --}}
            <div class="submit">
                <button
                    type="submit"
                    class="bg-[#638B35] text-white rounded-lg w-full px-5 py-3 text-base cursor-pointer border-2 border-transparent transition duration-300 ease-in-out hover:bg-[#76A74A] hover:shadow-md hover:-translate-y-1 active:bg-[#567B2A] active:shadow-sm active:translate-y-0">
                    Log in
                </button>
                {{-- pengguna baru : register --}}
                <p class="text-[#638B35] mt-3">
                    Pengguna Baru?
                    <a href="{{ route('register') }}" class="text-[#0F3714] underline">Sign-up</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleIcon.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // ubah ikon berdasarkan status
            toggleIcon.innerHTML = isPassword
                ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[rgba(15,55,20,1)]">
                        <path d="M4 12C4 12 5.6 7 12 7M12 7C18.4 7 20 12 20 12M12 7V4M18 5L16 7.5M6 5L8 7.5M15 13C15 14.6569 13.6569 16 12 16C10.3431 16 9 14.6569 9 13C9 11.3431 10.3431 10 12 10C13.6569 10 15 11.3431 15 13Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon">
                        <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`;
        });
    </script>
</body>
</html>
