<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nauture: Signup</title>
    @vite('resources/css/global.css')
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @include('includes.toastr')
</head>
<body class="h-screen flex items-center justify-center text-[#0F3714] bg-white box-border text-sm overflow-hidden">
    <div class="flex items-center justify-between h-[90vh] max-w-[80%] mx-auto">
        {{-- flex1-imgfiller --}}
        <div class="hidden md:flex flex-1 items-center justify-center h-full w-auto">
            <img src="/images/backgrounds/registerFig.png" alt="[alt]Nauture-Filler-Reg" class="h-full p-8 opacity-80">
        </div>

        {{-- flex2-input --}}
        <div class="flex-1 flex flex-col items-center justify-center w-full md:w-auto">
            {{-- ikon nauture : homepage --}}
            <a href="{{ route('homepage') }}">
                <img src="/images/logos/roundLogo.png" alt="[alt]NauTure-Home" class="h-12 w-auto mb-4">
            </a>
            {{-- title --}}
            <h1 class="text-2xl font-bold text-center mb-0">Buat Akun</h1>
            {{-- ucapan, : login --}}
            <p class="text-[#638B35] text-center mb-4">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-[#A6DE21] hover:underline">Log In</a>
            </p>
            {{-- FORM REGISTER --}}
            <form action="{{ route('register.process') }}" method="POST">
                @csrf
                {{-- nama lengkap --}}
                <div class="mb-1">
                    <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full bg-[rgba(206,241,123,0.6)] p-3 rounded-lg border-2 border-transparent
                        focus:outline-none focus:border-[#CEF17B]
                        focus:shadow-[0_0_8px_rgba(99,139,53,0.5)] transition duration-300"
                    >
                </div>
                {{-- nomor telepon --}}
                <div class="mb-1">
                    <label for="no_telp" class="block text-sm font-medium mb-1">Nomor Telepon</label>
                    <input type="number" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" placeholder="Maksimal 13 angka" required
                        class="w-full bg-[rgba(206,241,123,0.6)] p-3 rounded-lg border-2 border-transparent
                        focus:outline-none focus:border-[#CEF17B]
                        focus:shadow-[0_0_8px_rgba(99,139,53,0.5)] transition duration-300"
                    >
                </div>
                {{-- email --}}
                <div class="mb-1">
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@gmail.com" required
                        class="w-full bg-[rgba(206,241,123,0.6)] p-3 rounded-lg border-2 border-transparent
                        focus:outline-none focus:border-[#CEF17B]
                        focus:shadow-[0_0_8px_rgba(99,139,53,0.5)] transition duration-300"
                    >
                </div>
                {{-- password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            class="w-full bg-[rgba(206,241,123,0.6)] p-3 rounded-lg border-2 border-transparent
                            focus:outline-none focus:border-[#CEF17B]
                            focus:shadow-[0_0_8px_rgba(99,139,53,0.5)] transition duration-300"
                        >
                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer flex items-center justify-center w-6 h-6 hover:text-[rgba(15,55,20,1)] transition"
                            id="toggleIcon"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[#638B35]">
                            <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </span>
                    </div>
                </div>
                {{-- submit --}}
                <button
                    type="submit"
                    class="w-full bg-[#76A74A] text-white rounded-lg border-2 border-transparent
                    px-5 py-3 cursor-pointer transition duration-300 ease-in-out
                    hover:bg-[#567B2A] hover:shadow-md hover:-translate-y-1
                    active:bg-[#0F3714] active:translate-y-0 active:shadow-sm">
                    Buat Akun
                </button>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleIcon.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // ubah ikon berdasarkan status
            toggleIcon.innerHTML = isPassword
                ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-color text-[rgba(15,55,20,1)]">
                        <path d="M4 12C4 12 5.6 7 12 7M12 7C18.4 7 20 12 20 12M12 7V4M18 5L16 7.5M6 5L8 7.5M15 13C15 14.6569 13.6569 16 12 16C10.3431 16 9 14.6569 9 13C9 11.3431 10.3431 10 12 10C13.6569 10 15 11.3431 15 13Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[#638B35]">
                        <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`;
        });
    </script>
</body>
</html>
