<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nauture | @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('includes.toastr')
    @include('includes.sweetalert')
</head>
<body>
    @unless (View::hasSection('hide-navbar'))
        <x-navbar />
    @endunless

    <main class="mt-16">
        @if (View::hasSection('show-sidebar'))
            <div class="flex gap-5">
                @include('components.sidebar')
                <div class="">
                    @yield('content')
                </div>
            </div>
        @else
            @yield('content')
        @endif
    </main>

    @unless (View::hasSection('hide-footer'))
        <x-footer />
    @endunless
</body>
</html>
