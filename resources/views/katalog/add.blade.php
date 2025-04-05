<x-layout>

    <h1>{{ isset($katalog) ? 'Ubah Detail Produk' : 'Tambahkan Produk' }}</h1>

    {{-- @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @elseif (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif --}}

    @if ($errors->any())
        <script>
            alert('Ada kesalahan dalam input: \n{{ implode('\n', $errors->all()) }}');
        </script>
    @endif

    <form action="{{ isset($katalog) ? route('katalog.update', $katalog->id) : route('katalog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if (isset($katalog))
            @method('PUT')
        @endif

        <label for="nama_produk">Nama Produk:</label>
        <input type="text" name="nama_produk" value="{{ old('nama_produk', $katalog->nama_produk ?? '') }}" required><br><br>

        <label for="deskripsi_produk">Deskripsi Produk:</label>
        <textarea name="deskripsi_produk">{{ old('deskripsi_produk', $katalog->deskripsi_produk ?? '') }}</textarea><br><br>

        <label for="harga_perkilo">Harga per Kilo:</label>
        <input type="number" name="harga_perkilo" value="{{ old('harga_perkilo', $katalog->harga_perkilo ?? '') }}" required><br><br>

        <label for="foto_produk">Foto Produk:</label>
        <input type="file" name="foto_produk" accept="image/*" required><br><br>
        @if(isset($katalog) && $katalog->foto_produk)
            <p>Foto Saat Ini:</p>
            <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="Foto Produk" width="150">
        @endif

        <button type="submit">{{ isset($katalog) ? 'Simpan Perubahan' : 'Tambahkan' }}</button>
    </form>

</x-layout>
