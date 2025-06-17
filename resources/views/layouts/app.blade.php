<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nauture | @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    @if(session('success')) data-toastr-success='{{ json_encode(session('success')) }}' @endif
    @if(session('error')) data-toastr-error='{{ json_encode(session('error')) }}' @endif
    @if(session('info')) data-toastr-info='{{ json_encode(session('info')) }}' @endif
    @if(session('warning')) data-toastr-warning='{{ json_encode(session('warning')) }}' @endif
    @if($errors->any()) data-toastr-errors='{{ json_encode($errors->all()) }}' @endif
    @if(session('alert')) data-sweetalert='{{ json_encode(session('alert')) }}' @endif
>

    @unless (View::hasSection('hide-navbar'))
        <x-navbar :showSidebar="View::hasSection('show-sidebar')" />
    @endunless

    @if (View::hasSection('show-sidebar'))
        <main class="mt-16">
            <div class="flex gap-5 mt-4">
                <div class="z-100">
                    @include('components.sidebar')
                    <div class="hidden sm:block sm:w-55"></div>
                </div>
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
