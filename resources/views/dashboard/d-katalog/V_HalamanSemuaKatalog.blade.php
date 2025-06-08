@extends('layouts.app')

@section('title', 'Kelola Katalog')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-2 w-full">
        <div>
            <h1 class="font-bold text-4xl">Katalog NauTure</h1>
            <p class="font-thin text-sm">Semua katalog produk kita</p>
        </div>
        @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
        <div class="w-full flex justify-end">
            <a href="{{ route('katalog.add') }}"
                class="text-sm font-medium text-white bg-primer px-4 py-2 shadow-lg rounded-lg
                hover:bg-black transition
                block w-20 sm:inline sm:w-auto"
                >
                Tambah Katalog
            </a>
        </div>
        @endif
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-1 border-primer py-1">
                        <th>No.</th>
                        <th class="">Nama Produk</th>
                        <th class="max-w-40">Deskripsi</th>
                        <th class="">Estimasi Harga Perkilo</th>
                        <th class="">Foto Produk</th>
                        <th class="">Status Katalog</th>
                        <th class="">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($katalogs as $index => $katalog)
                        <tr class="border-b-1 border-bsoft">
                            <!--NO.-->
                            <td class="text-center">{{ ($katalogs->currentPage() - 1) * $katalogs->perPage() + $index + 1 }}.</td>
                            <!--NAMA PRODUK-->
                            <td class="">{{ $katalog->nama_produk }}</td>
                            <!--DESKRIPSI-->
                            <td class="max-w-40">{{ $katalog->deskripsi_produk ? $katalog->deskripsi_produk : '-' }}</td>
                            <!--HARGA-->
                            <td class="">Rp. {{ number_format($katalog->harga_perkilo, 0, ',', '.') }}</td>
                            <!--FOTO-->
                            <td class="">
                                <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="[{{ $katalog->nama_produk }}]"
                                class="w-full h-20 object-cover rounded-md"
                                >
                            </td>
                            <!--AKSI-->
                            <td class="">
                                <div class="flex flex-row items-center justify-center">
                                    @if($katalog->status == 'Aktif')
                                    <span class="h-3 w-3 rounded-full mr-2 bg-success"></span>
                                    {{ $katalog->status }}
                                    @else
                                    <span class="h-3 w-3 rounded-full mr-2 bg-hapus"></span>
                                    {{ $katalog->status }}
                                    @endif
                                </div>
                            </td>
                            <!--AKSI-->
                            <td class="flex items-center justify-end text-center gap-2 text-white">
                                <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}"
                                    class="text-sm px-4 py-2 rounded-lg bg-white border-1 text-primer
                                        hover:bg-primer hover:text-white"
                                    >
                                    Lihat
                                </a>
                                @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                                    @if ($katalog->trashed())
                                    <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}"
                                        class="text-sm px-4 py-2 rounded-lg bg-restore
                                            hover:bg-restorehov"
                                        >
                                        Restore
                                    </a>
                                    @else
                                    <a href="{{ route('katalog.edit', ['id' => $katalog->id]) }}"
                                        class="text-sm px-4 py-2 rounded-lg bg-edit
                                            hover:bg-edithov"
                                        >
                                        Edit
                                    </a>
                                    <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}"
                                        class="text-sm px-4 py-2 rounded-lg bg-hapus
                                            hover:bg-hapushov"
                                        >
                                        Hapus
                                    </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $katalogs->links() }}
            </div>
        </div>
    </div>
@endsection
