@extends('layouts.app')

@section('title', 'Pegawai Saya')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-3 w-full">
        <div>
            <h1 class="font-bold text-4xl">Pegawai NauTure</h1>
            <p class="font-thin text-sm">Semua pegawai yang bekerja disini</p>
        </div>
        <div class="w-full flex justify-end">
            <a href="{{ route('pegawai.add') }}"
                class="text-sm font-medium text-white bg-primer px-4 py-2 shadow-lg rounded-lg
                hover:bg-black transition
                block w-20 sm:inline sm:w-auto"
                >
                Tambah Pegawai
            </a>
        </div>
        <div>
            <table class="w-full">
                <thead>
                    <tr class="border-b-1 border-primer py-1">
                        <th>No.</th>
                        <th>Nama</th>
                        <th>No. Telp.</th>
                        <th>Email</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawais as $index => $pegawai)
                        <tr class="border-b-1 border-bsoft
                            animasi-slide-kekanan
                            ">
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <td>
                                <div class="flex gap-3 items-center">
                                    <div class="h-10 w-10 p-0.5">
                                        <img src="{{ $pegawai->foto_profil ? asset('storage/' . $pegawai->foto_profil) : asset('images/icons/defaultAvatarDark.svg') }}"
                                            class="h-full w-full object-cover rounded-full shadow-lg"
                                        >
                                    </div>
                                    {{ $pegawai->name }}
                                </div>
                            </td>
                            <td>{{ $pegawai->no_telp }}</td>
                            <td>{{ $pegawai->email }}</td>
                            <td>{{ $pegawai->alamat_id ? 'ada' : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $pegawais->links() }}
            </div>
        </div>
    </div>

@endsection


