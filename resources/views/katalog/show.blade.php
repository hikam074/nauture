<x-layout>

    <h3>Detail Produk</h3>

    <p><strong>Nama Produk:</strong> {{ $katalog->nama_produk }}</p>
    <p><strong>Deskripsi:</strong> {{ $katalog->deskripsi_produk }}</p>
    <p><strong>Harga per Kilo:</strong> Rp {{ number_format($katalog->harga_perkilo, 0, ',', '.') }}</p>

    @if ($katalog->foto_produk)
        <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="Foto Produk" style="max-width: 300px;">
    @endif

    <a href="/katalog">Kembali ke Daftar Produk</a>

</x-layout>
