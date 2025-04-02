<x-layout>

    <h1>Tambah Produk</h1>

    {{-- @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @elseif (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    @if ($errors->any())
        <script>
            alert('Ada kesalahan dalam input: \n{{ implode('\n', $errors->all()) }}');
        </script>
    @endif --}}

    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="nama_produk">Nama Produk:</label>
        <input type="text" name="nama_produk" required><br><br>

        <label for="deskripsi_produk">Deskripsi Produk:</label>
        <textarea name="deskripsi_produk"></textarea><br><br>

        <label for="harga_perkilo">Harga per Kilo:</label>
        <input type="number" name="harga_perkilo" required><br><br>

        <label for="foto_produk">Foto Produk:</label>
        <input type="file" name="foto_produk" accept="image/*" required><br><br>

        <button type="submit">Simpan Produk</button>
    </form>

</x-layout>
