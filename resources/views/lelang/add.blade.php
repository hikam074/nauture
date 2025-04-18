<x-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold text-center mb-6">{{ isset($lelang) ? 'Ubah Detail Lelang' : 'Tambahkan Lelang' }}</h1>

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

            <div class="space-y-4">
                <!-- Nama Produk Lelang -->
                <div>
                    <label for="nama_produk_lelang" class="block font-medium text-gray-700">Nama Produk Lelang :</label>
                    <input type="text" name="nama_produk_lelang" value="{{ old('nama_produk_lelang', $lelang->nama_produk_lelang ?? '') }}" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block font-medium text-gray-700">Keterangan :</label>
                    <textarea name="keterangan" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('keterangan', $lelang->keterangan ?? '') }}</textarea>
                </div>

                <!-- Berat -->
                <div>
                    <label for="jumlah_kg" class="block font-medium text-gray-700">Berat (Kg) :</label>
                    <input type="number" name="jumlah_kg" value="{{ old('jumlah_kg', $lelang->jumlah_kg ?? '') }}" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Harga Dibuka -->
                <div>
                    <label for="harga_dibuka" class="block font-medium text-gray-700">Harga Dibuka :</label>
                    <input type="number" name="harga_dibuka" value="{{ old('harga_dibuka', $lelang->harga_dibuka ?? '') }}" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Dibuka -->
                <div>
                    <label for="tanggal_dibuka" class="block font-medium text-gray-700">Tanggal Dibuka :</label>
                    <input type="datetime-local" name="tanggal_dibuka" value="{{ old('tanggal_dibuka', isset($lelang->tanggal_dibuka) ? $lelang->tanggal_dibuka->format('Y-m-d\TH:i') : '') }}" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Ditutup -->
                <div>
                    <label for="tanggal_ditutup" class="block font-medium text-gray-700">Tanggal Ditutup :</label>
                    <input type="datetime-local" name="tanggal_ditutup" value="{{ old('tanggal_ditutup', isset($lelang->tanggal_ditutup) ? $lelang->tanggal_ditutup->format('Y-m-d\TH:i') : '') }}" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- Referensi Produk Katalog -->
                <div>
                    <label for="katalog_id" class="block font-medium text-gray-700">Produk Katalog :</label>
                    <select name="katalog_id" id="katalog_id" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Produk</option>
                        @foreach($katalogs as $katalog)
                            <option
                                value="{{ $katalog->id }}"
                                data-foto="{{ asset('storage/' . $katalog->foto_produk) }}"
                                {{ old('katalog_id', $lelang->katalog_id ?? '') == $katalog->id ? 'selected' : '' }}>
                                {{ $katalog->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Foto Produk -->
                <div>
                    <label for="foto_produk" class="block text-gray-700 font-medium mb-2">Foto Produk : <a id="teksFotoDipilih"></a></label>
                    <div class="flex items-start mt-4 space-x-4">
                        <!-- Kotak Input untuk Upload -->
                        <div class="flex flex-col space-y-2">
                            <p class="text-sm text-gray-500"><br></p>
                            <label for="foto_produk"
                                class="w-32 h-32 flex items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed text-gray-500 hover:border-blue-500">
                                <span id="uploadPlaceholder" class="text-sm text-center">Klik untuk Upload</span>
                                <input type="file" name="foto_produk" id="foto_produk" accept="image/*" class="hidden"
                                    onchange="previewImage(event)">
                            </label>
                        </div>

                        <!-- Foto Saat Ini (Hanya di Mode Edit) -->
                        @if(isset($lelang->foto_produk) && $lelang->foto_produk)
                        <div class="flex flex-col space-y-2">
                            <p class="text-sm text-gray-500">Foto Saat Ini:</p>
                            <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="Foto Produk"
                                class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300"
                                onclick="selectImage(this, 'Foto Saat Ini')" data-origin="foto saat ini">
                        </div>
                        @endif

                        <!-- Foto dari Katalog -->
                        <div class="flex flex-col space-y-2">
                            <p class="text-sm text-gray-500">Foto dari Katalog:</p>
                            <img id="katalogFoto"
                                src="{{ isset($lelang->katalog->foto_produk) ? asset('storage/' . $lelang->katalog->foto_produk) : '' }}"
                                alt="Foto Katalog"
                                class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300 hidden"
                                onclick="selectImage(this, 'Foto dari Katalog')" data-origin="foto dari katalog">
                        </div>

                        <!-- Pratinjau Gambar Baru -->
                        <div class="flex flex-col space-y-2">
                            <p class="text-sm text-gray-500">Pratinjau Upload Baru:</p>
                            <img id="uploadedImg" alt="Pratinjau Foto Baru"
                                class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300 hidden"
                                onclick="selectImage(this, 'Foto Upload Baru')" data-origin="foto unggahan baru">
                        </div>
                    </div>
                    <input type="hidden" name="selected_image" id="selectedImage" value="{{ $lelang->foto_produk ?? '' }}">
                </div>

                <div class="text-center">
                    <button type="button" onclick="window.history.back();"
                        class="px-4 py-2 mt-4 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-1">
                        Kembali
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const katalogSelect = document.getElementById('katalog_id');
            const katalogFoto = document.getElementById('katalogFoto');
            const uploadedImg = document.getElementById('uploadedImg');
            const selectedImageInput = document.getElementById('selectedImage');
            const defaultImage = document.querySelector('[data-origin="foto saat ini"]');

            // Logika: Pilih foto default saat halaman dimuat
            if (defaultImage) {
                selectImage(defaultImage, 'Foto Saat Ini');
            } else if (katalogSelect.value) {
                const selectedOption = katalogSelect.options[katalogSelect.selectedIndex];
                const fotoURL = selectedOption.getAttribute('data-foto') || '';
                katalogFoto.src = fotoURL;
                selectImage(katalogFoto, 'Foto dari Katalog');
            } else if (uploadedImg.src !== '') {
                selectImage(uploadedImg, 'Pratinjau Upload Baru');
            }
            katalogFoto.classList.remove('hidden');
            if (katalogSelect.value == '') {
                katalogFoto.classList.add('hidden');
            }

            // Event Listener untuk Select Katalog
            katalogSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const fotoURL = selectedOption.getAttribute('data-foto') || '';
                katalogFoto.src = fotoURL;
                if (fotoURL == '') {
                    katalogFoto.classList.add('hidden');
                    if (defaultImage) {
                        selectImage(defaultImage, 'Foto Saat Ini');
                    } else {
                        document.getElementById('teksFotoDipilih').innerText = null;
                    }
                } else  {
                    katalogFoto.classList.remove('hidden');
                    selectImage(katalogFoto, 'Foto Dari Katalog');
                }
            });
        });

        // Fungsi untuk memilih gambar
        function selectImage(imageElement, teks) {
            document.querySelectorAll('.w-32.h-32').forEach(img => {
                img.classList.remove('ring-4', 'ring-blue-500');
            });

            imageElement.classList.add('ring-4', 'ring-blue-500');
            const selectedImage = document.getElementById('selectedImage');
            selectedImage.value = imageElement.dataset.origin || '';
            document.getElementById('teksFotoDipilih').innerText = teks;
        }

        // Pratinjau Foto Baru
        function previewImage(event) {
            const uploadedImg = document.getElementById('uploadedImg');
            const defaultImage = document.querySelector('[data-origin="foto saat ini"]');
            // Cek jika ada file yang dipilih
            if (event.target.files && event.target.files[0]) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Menampilkan pratinjau gambar baru
                    uploadedImg.src = e.target.result;
                    uploadedImg.classList.remove('hidden');  // Menampilkan gambar pratinjau

                    // Pilih gambar yang diunggah
                    selectImage(uploadedImg, 'Pratinjau Upload Baru');
                };
                reader.readAsDataURL(file);
            } else {
                // Jika tidak ada file yang dipilih, sembunyikan pratinjau
                uploadedImg.classList.add('hidden');
                uploadedImg.src = '#';
                if (defaultImage) {
                    selectImage(defaultImage, 'Foto Saat Ini');
                } else  {
                    document.getElementById('selected_image').value = '';
                    document.getElementById('teksFotoDipilih').innerText = null;
                }
            }
        }

    </script>

</x-layout>
