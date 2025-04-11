<x-layout>

    <h1>{{ isset($lelang) ? 'Ubah Detail Lelang' : 'Tambahkan Lelang' }}</h1>

    @if ($errors->any())
        <script>
            alert('Ada kesalahan dalam input: \n{{ implode("\n", $errors->all()) }}');
        </script>
    @endif

    <form action="{{ isset($lelang) ? route('lelang.update', $lelang->id) : route('lelang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if (isset($lelang))
            @method('PUT')
        @endif

        <!-- Nama Produk Lelang -->
        <label for="nama_produk_lelang">Nama Produk Lelang:</label>
        <input type="text" name="nama_produk_lelang" value="{{ old('nama_produk_lelang', $lelang->nama_produk_lelang ?? '') }}" required><br><br>

        <!-- Keterangan -->
        <label for="keterangan">Keterangan:</label>
        <textarea name="keterangan">{{ old('keterangan', $lelang->keterangan ?? '') }}</textarea><br><br>

        <!-- Berat -->
        <label for="jumlah_kg">Berat:</label>
        <input type="number" name="jumlah_kg" value="{{ old('jumlah_kg', $lelang->jumlah_kg ?? '') }}" required><br><br>

        <!-- Harga Dibuka -->
        <label for="harga_dibuka">Harga Dibuka:</label>
        <input type="number" name="harga_dibuka" value="{{ old('harga_dibuka', $lelang->harga_dibuka ?? '') }}" required><br><br>

        <!-- Tanggal Dibuka -->
        <label for="tanggal_dibuka">Tanggal Dibuka:</label>
        <input type="datetime-local" name="tanggal_dibuka" value="{{ old('tanggal_dibuka', isset($lelang->tanggal_dibuka) ? $lelang->tanggal_dibuka->format('Y-m-d\TH:i') : '') }}" required><br><br>

        <!-- Tanggal Ditutup -->
        <label for="tanggal_ditutup">Tanggal Ditutup:</label>
        <input type="datetime-local" name="tanggal_ditutup" value="{{ old('tanggal_ditutup', isset($lelang->tanggal_ditutup) ? $lelang->tanggal_ditutup->format('Y-m-d\TH:i') : '') }}" required><br><br>

        <!-- Referensi Produk Katalog -->
        <label for="katalog_id">Produk Katalog:</label>
        <select name="katalog_id" id="katalog_id" required>
            <option value="">Pilih Produk</option>
            @foreach($katalogs as $katalog)
                <option value="{{ $katalog->id }}" {{ old('katalog_id', $lelang->katalog_id ?? '') == $katalog->id ? 'selected' : '' }}>
                    {{ $katalog->nama_produk }}
                </option>
            @endforeach
        </select><br><br>

        <!-- Foto Produk -->
        <label for="foto_produk">Foto Produk:</label>
        <input type="file" name="foto_produk" id="foto_produk" accept="image/*"><br><br>
        <p id="selected-file-name">Silahkan masukkan/pilih foto</p>

        <!-- Preview Foto -->
        <div id="preview-container" style="display: flex; gap: 10px;">
            {{-- Dari katalogs --}}
            @if (isset($lelang->katalog->foto_produk))
                {{-- bila mode edit --}}
                <div>
                    <p>Foto dari Katalog:</p>
                    <img id="katalog-foto" class="selectable-img" data-origin="Dari katalog" src="{{ asset('storage/' . $lelang->katalog->foto_produk) }}" alt="[alt]Foto Katalog">
                </div>
            @else
                {{-- bila mode add --}}
                <div>
                    <p id="judul-dari-katalog-add"></p>
                    <img id="katalog-foto" class="selectable-img" src="" alt="" data-origin="Dari katalog">
                </div>
            @endif
            {{-- dari lelangs --}}
            @if (isset($lelang->foto_produk))
                <div>
                    <p>Foto dari Lelang::</p>
                    <img id="default-lelang-img" class="selectable-img" data-origin="Dari lelang" src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="[alt]Foto Lelang">
                </div>
            @endif
            {{-- dari upload --}}
            <div id="uploaded-preview" style="display: none;">
                <p>Foto Baru:</p>
                <img id="uploaded-img" class="selectable-img" data-origin="Dari unggahan baru" src="#" alt="[alt]Foto Baru">
            </div>
        </div>

        <input type="hidden" name="current_img" id="selected-image-origin" value="" required>

        <button type="submit">{{ isset($lelang) ? 'Simpan Perubahan' : 'Tambahkan' }}</button>
    </form>

    <script>
        // PREVIEW FOTO DIUNGGAH
        
        document.getElementById('foto_produk').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById('uploaded-img');
                    img.src = e.target.result;
                    img.dataset.origin = 'foto unggahan baru';
                    document.getElementById('uploaded-preview').style.display = 'block';

                    // Update tampilan: otomatis pilih foto unggahan baru
                    selectImage(img);

                    // Update teks "Foto yang dipilih"
                    document.getElementById('selected-file-name').textContent = 'Foto yang dipilih: foto unggahan baru';
                    document.getElementById('selected-image-origin').value = 'foto unggahan baru';
                };
                reader.readAsDataURL(file);
            }
        });

        // PREVIEW FOTO DARI KATALOG
        document.getElementById('katalog_id').addEventListener('change', function () {
            const selectedKatalogId = this.value;
            if (selectedKatalogId) {
                fetch(`/api/katalog/${selectedKatalogId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.foto_produk) {
                            // Mengubah atribut src pada elemen <img>
                            document.getElementById('katalog-foto').src = `/storage/${data.foto_produk}`;
                            document.getElementById('katalog-foto').alt = "[alt]Foto Katalog"; // Tambahkan deskripsi alt
                            document.getElementById('judul-dari-katalog-add').innerText = 'Foto dari katalog:';
                            document.getElementById('selected-image-origin').value = document.getElementById('katalog-foto').src;
                            selectImage(document.getElementById('katalog-foto'));
                        } else {
                            // Jika foto tidak tersedia, kosongkan src dan alt
                            document.getElementById('katalog-foto').src = '';
                            document.getElementById('katalog-foto').alt = '[alt]Foto tidak tersedia';
                            document.getElementById('judul-dari-katalog-add').innerText = '';
                            document.getElementById('selected-image-origin').value = '';
                        }
                    })
                .catch(error => console.error('Error fetching katalog foto:', error));
            } else {
                // Reset src dan alt jika tidak ada katalog yang dipilih
                document.getElementById('katalog-foto').src = '';
                document.getElementById('katalog-foto').alt = '';
                document.getElementById('judul-dari-katalog-add').innerText = '';
            }
        });

        // PEMILIHAN GAMBAR
        document.querySelectorAll('.selectable-img').forEach(img => {
            img.addEventListener('click', function () {
                selectImage(this);
            });
        });
        // Fungsi untuk memilih gambar (memperbarui border dan input)
        function selectImage(img) {
            // Reset border untuk semua gambar
            document.querySelectorAll('.selectable-img').forEach(img => img.style.border = '2px solid transparent');

            // Tambahkan border biru ke gambar yang dipilih
            img.style.border = '2px solid blue';

            // Perbarui teks dan input
            const selectedSrc = img.src; // Ambil URL/path gambar dari atribut src
            document.getElementById('selected-image-origin').value = img.src; // Perbarui input hidden dengan src gambar
            document.getElementById('selected-file-name').textContent = `Foto yang dipilih: ${img.dataset.origin}`; // Perbarui tampilan nama file
        }


        // KALAU FOTO DARI LELANG ADA KETIKA DILOAD MAKA ITU JADI DEFAULT
        document.addEventListener('DOMContentLoaded', function () {
            const defaultLelangImg = document.getElementById('default-lelang-img');
            if (defaultLelangImg) {
                selectImage(defaultLelangImg);
            }
        });

    </script>

</x-layout>
