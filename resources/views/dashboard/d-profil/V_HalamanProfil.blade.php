@extends('layouts.app')

@section('title', 'Profil')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')

    <div class="mb-5 flex flex-col gap-5 w-full">
        <h1 class="font-bold text-4xl">Profil Anda</h1>

        <div class="flex items-center justify-between my-5">
            <div class="w-24 h-24
                 animasi-slide-kekanan
                ">
                <img
                    src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('images/icons/defaultAvatarDark.svg') }}"
                    class="h-full w-full object-cover rounded-full border-2 border-white">
            </div>
            @if (Auth::check() && Auth::user()->role->nama_role != 'owner')
            <div class="">
                <a href="{{ route('profil.edit') }}"
                    class="border-2 border-dashed p-5 text-center
                        hover:bg-gray-300"
                    >
                    Ubah Profil
                </a>
            </div>
            @endif
        </div>

        <div class=" animasi-slide-kekanan">
            <label>Nama Lengkap</label>
            <input type="text" value="{{ $profil->name }}" disabled
                class="border-1 p-2 w-full border-primer"
            >
        </div>
        <div class=" animasi-slide-kekanan">
            <label>Email</label>
            <input type="text" value="{{ $profil->email }}" disabled
                class="border-1 p-2 w-full border-primer"
            >
        </div>
        <div class=" animasi-slide-kekanan">
            <label>Nomor Telepon</label>
            <input type="text" value="{{ $profil->no_telp }}" disabled
                class="border-1 p-2 w-full border-primer"
            >
        </div>
        <div class=" animasi-slide-kekanan">
            <label>Role/Jenis Akun</label>
            <input type="text" value="{{ $profil->role->nama_role }}" disabled
                class="border-1 p-2 w-full border-primer"
            >
        </div>
        <div class=" animasi-slide-kekanan">
            <label>Alamat</label>
            @if($profil->alamat_id)
            <input type="text" value="{{ $profil->alamat->detail_alamat.', '.$profil->alamat->city->nama_city.', '.$profil->alamat->city->provinsi->nama_provinsi }}" disabled
                class="border-1 p-2 w-full border-primer"
            >
            @else
            <input type="text" value="" disabled
                class="border-1 p-2 w-full border-primer"
            >
            @endif
        </div>
        @if (Auth::check() && Auth::user()->role->nama_role != 'owner')
        <div class=" animasi-slide-kekanan">
            <label>Suspend Point</label>
            <input type="text" value="{{ $profil->suspend_point }}" disabled
                class="border-1 p-2 w-full border-primer"
            >
        </div>
        @endif
    </div>

@endsection
