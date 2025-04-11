<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NauTure</title>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('homepage') }}">NauTure</a>
            <a href="{{ route('katalog.index') }}">Katalog</a>
             <a href="{{ route('lelang.index') }}">Lelang</a>
            @if (!Auth::check())
                <a href="{{ route('login') }}">Login/Register</a>
            @else

                <a href="{{-- {{ route('profile.show') }} --}}">Profil</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: blue; cursor: pointer;">Logout</button>
                </form>
            @endif
        </nav>
    </header>

    <main class="container">
        {{ $slot }}
    </main>

</body>
</html>
