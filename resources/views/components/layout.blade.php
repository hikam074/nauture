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
            <span>NauTure</span>
            <a href="/katalog/">Katalog</a>
            <a href="/lelang/">Lelang</a>
        </nav>
    </header>

    <main class="container">
        {{ $slot }}
    </main>
    
</body>
</html>
