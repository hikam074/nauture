<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nauture | @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/global.css'])
    @include('includes.toastr')
    @include('includes.sweetalert')
</head>
<body>
    @unless (View::hasSection('hide-navbar'))
        <x-navbar :showSidebar="View::hasSection('show-sidebar')" />
    @endunless

    @if (View::hasSection('show-sidebar'))
        <main class="mt-16">
            <div class="flex gap-5 mt-4">
                @include('components.sidebar')
                <div class="w-full mt-8 mr-5 text-primer">
                    @yield('content')
                </div>
            </div>
        </main>
    @else
        <main class="mt-16">
            @yield('content')
        </main>
    @endif


    @unless (View::hasSection('hide-footer'))
        <x-footer />
    @endunless

    @yield('scripts')
    @include('includes.onesignal')
</body>
</html>
