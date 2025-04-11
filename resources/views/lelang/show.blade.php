<x-layout>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Detail Lelang</h3>

        <div class="mb-4">
            <p><strong>Kode Lelang:</strong> {{ $lelang->kode_lelang }}</p>
            <p><strong>Nama Produk Lelang:</strong> {{ $lelang->nama_produk_lelang }}</p>
            <p><strong>Berat:</strong> {{ $lelang->jumlah_kg }}</p>
            <p><strong>Keterangan:</strong> {{ $lelang->keterangan ?? 'Tidak ada keterangan' }}</p>
            <p><strong>Harga Dibuka:</strong> Rp {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</p>
            <p><strong>Tanggal Dibuka:</strong> {{ $lelang->tanggal_dibuka->format('d M Y, H:i') }}</p>
            <p><strong>Tanggal Ditutup:</strong> {{ $lelang->tanggal_ditutup->format('d M Y, H:i') }}</p>
        </div>

        <div class="mb-4">
            <h4 class="text-lg font-medium mb-2">Foto Produk</h4>
            @if ($lelang->foto_produk)
                <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="Foto Produk Lelang" class="rounded-lg shadow-md w-full max-w-md">
            @else
                <p class="text-gray-500">Tidak ada foto produk tersedia.</p>
            @endif
        </div>

        <div class="mb-4">
            <h4 class="text-lg font-medium mb-2">Referensi Produk Katalog</h4>
            @if ($lelang->katalog)
                <p><strong>Nama Produk:</strong> {{ $lelang->katalog->nama_produk }}</p>
                <p><strong>Harga per Kilo:</strong> Rp {{ number_format($lelang->katalog->harga_perkilo, 0, ',', '.') }}</p>
                @if ($lelang->katalog->foto_produk)
                    <img src="{{ asset('storage/' . $lelang->katalog->foto_produk) }}" alt="Foto Produk Katalog" class="rounded-lg shadow-md w-full max-w-md mt-2">
                @endif
            @else
                <p class="text-gray-500">Tidak ada referensi katalog.</p>
            @endif
        </div>

        <div class="flex items-center justify-between">
            <div>
                @if ($lelang->trashed())
                    <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            Restore
                        </button>
                    </form>
                @else
                    <a href="{{ route('lelang.edit', $lelang->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Edit
                    </a>

                    <form action="{{ route('lelang.destroy', $lelang->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>

            <a href="/lelang" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Kembali ke Daftar Lelang
            </a>
        </div>
    </div>

</x-layout>
