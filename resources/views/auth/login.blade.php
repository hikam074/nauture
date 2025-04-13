<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NauTure: Login</title>
    @vite('resources/css/auth-login.css')
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
<body>

    <div class='container'>
        <a href="{{ route('homepage') }}"><img id="homelink" src="images/logos/homeLogo.png" alt="[alt]NauTure-Home"></a>
        <h1 id="title">Log In</h1>
        <p id="ucapan">Selamat Datang Kembali! Silahkan Log-in ke akun anda</p>
        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="email">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="password">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-input" id="password" name="password" required>
            </div>
            <div class="remember-me-container">
                <label class="remember-me-inline">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <span class="checkmark"></span>
                    <span class="label-text">Ingat Saya</span>
                </label>
            </div>
            <div class="submit">
                <button type="submit" id="btn-submit">Log in</button>
                <p id="signup">Pengguna Baru? <a href="{{ route('register') }}" id="signup-href">Sign-up</a></p>
            </div>
        </form>
    </div>
</body>
</html>
