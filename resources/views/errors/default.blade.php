@extends('layouts.app')

@section('title', 'Terjadi Kesalahan')

@section('hide-footer')
@endsection

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-4rem)] text-center">
    <div class="max-w-[70%] px-4">

        @php
            $statusCode = $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException ? $exception->getStatusCode() : 500;
        @endphp
        <h1 class="text-9xl font-bold text-primer">{{ $statusCode }}</h1>

        <p class="text-2xl md:text-3xl font-light text-gray-800 mt-4">
            @switch($statusCode)
                @case(404)
                    Halaman Tidak Ditemukan
                    @break
                @case(403)
                    Akses Ditolak
                    @break
                @case(503)
                    Layanan Tidak Tersedia
                    @break
                @case(500)
                @default
                    Terjadi Kesalahan Pada Server
            @endswitch
        </p>

        <p class="mt-4 text-gray-500">
            Maaf, kami sedang mengalami kendala. Silakan coba lagi nanti atau kembali ke halaman utama.
        </p>

        <div class="fex gap-5">
            <a type="button" onclick="window.history.back()"
               class="inline-block mt-8 px-6 py-3 text-sm font-semibold text-white bg-sekunder rounded-lg shadow-md
                      hover:bg-primer transition-colors duration-300">
                Kembali ke Sebelumnya
            </a>
            <a href="{{ route('homepage') }}"
               class="inline-block mt-8 px-6 py-3 text-sm font-semibold text-white bg-sekunder rounded-lg shadow-md
                      hover:bg-primer transition-colors duration-300">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(isset($debugInfo) && $debugInfo)
<script>
    console.group("Laravel Exception Details (for debugging)");
    console.error("Message: ", {!! json_encode($debugInfo['message']) !!});
    console.warn("File: ", {!! json_encode($debugInfo['file'] . ':' . $debugInfo['line']) !!});
    console.groupCollapsed("Stack Trace");
    console.log({!! json_encode($debugInfo['trace']) !!});
    console.groupEnd();
    console.groupEnd();
</script>
@endif
@endsection
