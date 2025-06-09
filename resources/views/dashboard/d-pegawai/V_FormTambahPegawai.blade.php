@extends('layouts.app')

@section('title')
    @if(isset($pegawai))
        Ubah Pegawai
    @else
        Tambah Pegawai
    @endif
@endsection

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg my-10 ">
        <h1 class="text-2xl font-bold mb-6 text-center p-5 bg-[#CEED82]">
            {{ isset($pegawai) ? 'Ubah Profil Pegawai' : 'Tambahkan Pegawai' }}
        </h1>

        <div class="px-6 pb-6">
            <form id="pegawaiForm"
                action="{{ isset($pegawai) ? route('pegawai.update', $pegawai->id) : route('pegawai.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-4"
                >
                @csrf
                @if (isset($pegawai))
                    @method('PUT')
                    <input id="user_id" type="hidden" name="user_id" value="{{ $pegawai->id }}">
                @endif
                {{-- nama pegawai --}}
                <div>
                    <label for="nama_produk" class="block text-gray-700 font-medium">Nama Lengkap:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $pegawai->name ?? '') }}" required
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                {{-- email --}}
                <div>
                    <label for="deskripsi_produk" class="block text-gray-700 font-medium">Email:</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $pegawai->email ?? '') }}" required
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                {{-- password --}}
                <div>
                    <label for="harga_perkilo" class="block text-gray-700 font-medium">Password:</label>
                    <input type="text" name="password" id="password" required
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                {{-- no telp --}}
                <div>
                    <label for="harga_perkilo" class="block text-gray-700 font-medium">Nomor telepon:</label>
                    <input type="text" name="no_telp" id="no_telp" required
                        class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                {{-- tombol2 --}}
                <div class="text-center">
                    <button type="button" onclick="window.history.back();"
                        class="px-4 py-2 mt-4 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 cursor-pointer"
                        >
                        Kembali
                    </button>
                    <button type="button" id="confirmButton"
                        data-action="{{ isset($pegawai) ? 'edit' : 'add' }}"
                        class="px-4 py-2 bg-sekunderDark text-white rounded-lg shadow hover:bg-primer focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 cursor-pointer">
                        {{ isset($pegawai) ? 'Simpan Perubahan' : 'Tambahkan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('confirmButton');
            const form = document.getElementById('pegawaiForm');
            button.addEventListener('click', function () {
                showAlert({
                    title: 'Apakah Anda Yakin?',
                    text: 'Apakah Anda yakin hendak menambahkan pegawai ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Tambahkan',
                    cancelButtonText: 'Batal',
                    onConfirm: function () {
                        form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                    }
                });
            });
        });
    </script>
@endsection
