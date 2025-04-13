<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nauture: Signup</title>
    @vite('resources/css/auth-register.css')
    @vite('resources/css/global.css')
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @include('includes.toastr')
</head>
<body>
    <div class="container">
        <div class="filler-img">
            <img src="images/backgrounds/registerFig.png" alt="[alt]Nauture-Filler-Reg">
        </div>
        <div class="form-container">
            <a href="{{ route('homepage') }}"><img id="homelink" src="images/logos/roundLogo.png" alt="[alt]NauTure-Home"></a>
            <h1 id="title">Buat Akun</h1>
            <p id="signup">Sudah memiliki akun? <a href="{{ route('login') }}" id="signup-href">Log In</a></p>
            <form action="{{ route('register.process') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="no_telp" class="form-label">Nomor Telepon</label>
                    <input type="number" class="form-input" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" placeholder="Maksimal 13 angka" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@gmail.com" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-input" id="password" name="password" placeholder="Minimal 8 karakter" required>
                </div>

                <button type="submit" id="btn-submit">Buat Akun</button>

            </form>
        </div>
    </div>
</body>
</html>
