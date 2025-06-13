@extends('layouts.app')

@section('title', 'Kelola Lelang')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-2 w-full">
        <div>
            <h1 class="font-bold text-4xl">Lelang NauTure</h1>
            <p class="font-thin text-sm">Semua lelang yang diselenggarakan</p>
        </div>
        @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
        <div class="w-full flex justify-end">
            <a href="{{ route('lelang.add') }}"
                class="text-sm font-medium text-white bg-primer px-4 py-2 shadow-lg rounded-lg
                hover:bg-black transition
                block w-20 sm:inline sm:w-auto"
                >
                Tambah Lelang
            </a>
        </div>
        @endif
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-1 border-primer py-1 w-full">
                        <th>No.</th>
                        <th class="">Nama Lelang</th>
                        <th class="">Waktu Pelaksanaan</th>
                        <th class="">Harga Dibuka</th>
                        <th class="">Foto Produk</th>
                        <th class="">Status</th>
                        <th class="">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lelangs as $index => $lelang)
                    <tr class="border-b-1 border-bsoft w-full">
                        <!--NO.-->
                        <td class="text-center">{{ ($lelangs->currentPage() - 1) * $lelangs->perPage() + $index + 1 }}.</td>
                        <!--NAMA PRODUK-->
                        <td class="max-w-20">{{ $lelang->nama_produk_lelang }}</td>
                        <!--WAKTU-->
                        <td class="">{{ $lelang->tanggal_dibuka }}<br>s.d<br>{{ $lelang->tanggal_ditutup }}</td>
                        <!--HARGA.-->
                        <td class="">Rp. {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</td>
                        <!--FOTO PRODUK-->
                        <td class="">
                            <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="[{{ $lelang->nama_produk_lelang }}]"
                            class="w-full h-20 object-cover rounded-md"
                            >
                        </td>
                        <!--STATUS-->
                            <td >
                                <div class="flex flex-col items-center gap-2">
                                    <div class="flex items-center">
                                        @if($lelang->status == 'Dibatalkan')
                                        <span class="h-3 w-3 rounded-full mr-2 bg-hapus"></span>
                                        @elseif($lelang->status == 'Selesai, ada pemenang')
                                        <span class="h-3 w-3 rounded-full mr-2 bg-info"></span>
                                        @elseif($lelang->status == 'Berlangsung')
                                        <span class="h-3 w-3 rounded-full mr-2 bg-success"></span>
                                        @elseif($lelang->status == 'Selesai, tidak ada pemenang')
                                        <span class="h-3 w-3 rounded-full mr-2 bg-hapus"></span>
                                        @elseif($lelang->status == 'Belum dibuka')
                                        <span class="h-3 w-3 rounded-full mr-2 bg-canceled"></span>
                                        @endif
                                        <span class=" flex-1">{{ $lelang->status }}</span>
                                    </div>

                                    @if($lelang->status == 'Selesai, ada pemenang')
                                    <div class="flex items-center text-center">
                                        <span class="w-full p-1 border-1 rounded border-canceled">
                                            Pemenang :<br>
                                            {{ $lelang->pemenang->user->name }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        <!--AKSI-->
                        <td class="text-white">
                            <div class="flex flex-col items-center justify-start text-center gap-2">
                                <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                    class="text-sm px-4 py-1 rounded-lg bg-canceledhov w-full
                                    hover:bg-primer hover:text-white"
                                    >
                                    Lihat
                                </a>
                                @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                                    @if ($lelang->trashed())
                                    <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                        class="text-sm px-4 py-1 rounded-lg bg-restore w-full
                                            hover:bg-restorehov"
                                        >
                                        Restore
                                    </a>
                                    @elseif(now()->lessThan($lelang->tanggal_dibuka) )
                                    <a href="{{ route('lelang.edit', ['id' => $lelang->id]) }}"
                                        class="text-sm px-4 py-1 rounded-lg bg-edit w-full
                                            hover:bg-edithov"
                                        >
                                        Edit
                                    </a>
                                    <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}"
                                        class="text-sm px-4 py-1 rounded-lg bg-hapus w-full
                                            hover:bg-hapushov"
                                        >
                                        Hapus
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $lelangs->links() }}
            </div>
        </div>
    </div>
@endsection
