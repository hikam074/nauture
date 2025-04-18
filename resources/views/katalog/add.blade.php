<x-layout>
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-10">
        <h1 class="text-2xl font-bold mb-6 text-center">
            {{ isset($katalog) ? 'Ubah Detail Produk' : 'Tambahkan Produk' }}
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                <p><strong>Ada kesalahan dalam input:</strong></p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($katalog) ? route('katalog.update', $katalog->id) : route('katalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            @if (isset($katalog))
                @method('PUT')
            @endif

            <div>
                <label for="nama_produk" class="block text-gray-700 font-medium">Nama Produk:</label>
                <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $katalog->nama_produk ?? '') }}" required
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="deskripsi_produk" class="block text-gray-700 font-medium">Deskripsi Produk:</label>
                <textarea name="deskripsi_produk" id="deskripsi_produk"
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi_produk', $katalog->deskripsi_produk ?? '') }}</textarea>
            </div>

            <div>
                <label for="harga_perkilo" class="block text-gray-700 font-medium">Harga per Kilo:</label>
                <input type="number" name="harga_perkilo" id="harga_perkilo" value="{{ old('harga_perkilo', $katalog->harga_perkilo ?? '') }}" required
                    class="w-full mt-1 border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

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

                    <!-- Foto Saat Ini (Jika Ada) -->
                    @if(isset($katalog) && $katalog->foto_produk)
                        <div class="flex flex-col space-y-2">
                            <p class="text-sm text-gray-500">Foto Saat Ini:</p>
                            <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="Foto Produk"
                                class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300"
                                onclick="selectImage(this, '{{ $katalog->foto_produk }}', 'Foto Saat Ini')"
                                id="currentImage">
                        </div>
                    @endif

                    <!-- Pratinjau Gambar Baru -->
                    <div class="flex flex-col space-y-2">
                        <p class="text-sm text-gray-500">Pratinjau Upload Baru:</p>
                        <img id="preview" src="#" alt="Pratinjau Foto Baru"
                            class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300 hidden"
                            onclick="selectImage(this, 'new', 'Pratinjau Upload Baru')">
                    </div>
                </div>
                <input type="hidden" name="selected_image" id="selected_image" value="{{ $katalog->foto_produk ?? '' }}">
            </div>



            <div class="text-center">
                <button type="button" onclick="window.history.back();"
                    class="px-4 py-2 mt-4 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-1"
                    >
                    Kembali
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                    >
                    {{ isset($katalog) ? 'Simpan Perubahan' : 'Tambahkan' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Set the initial selected image to the current image, if available
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                selectImage(currentImage, currentImage.getAttribute('onclick').match(/'(.*?)'/)[1], 'Foto Saat Ini');
            }
        });

        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            const currentImage = document.getElementById('currentImage');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    selectImage(preview, 'new', 'Pratinjau Upload Baru'); // Automatically select the new image
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
                preview.src = '#';
                if (currentImage) {
                    selectImage(currentImage, currentImage.getAttribute('onclick').match(/'(.*?)'/)[1], 'Foto Saat Ini');
                } else {
                    document.getElementById('selected_image').value = '';
                    document.getElementById('teksFotoDipilih').innerText = null;
                }
            }
        }

        function selectImage(imageElement, value, teks) {
            // Reset styles on all images
            document.querySelectorAll('img').forEach(img => img.classList.remove('ring-4', 'ring-blue-500'));

            // Highlight selected image
            imageElement.classList.add('ring-4', 'ring-blue-500');
            document.getElementById('teksFotoDipilih').innerText = teks;

            // Set selected image value
            document.getElementById('selected_image').value = value;
            console.log(document.getElementById('selected_image').value);
        }
        console.log(document.getElementById('selected_image').value);
    </script>
</x-layout>
