@extends('layouts.app')

@section('title')
    @if(isset($lelang))
        Ubah Lelang
    @else
        Tambah Lelang
    @endif
@endsection

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg my-10">
        <h1 class="text-2xl font-semibold text-center mb-6 p-5 bg-[#CEED82]">{{ isset($lelang) ? 'Ubah Detail Lelang' : 'Tambahkan Lelang' }}</h1>

        @if ($errors->any())
            <script>
                alert('Ada kesalahan dalam input: \n{{ implode("\n", $errors->all()) }}');
            </script>
        @endif

        <div class="px-6 pb-6">

            <form id="lelangForm" action="{{ isset($lelang) ? route('lelang.update', ['id' => $lelang->id]) : route('lelang.store') }}"
                method="POST"
                enctype="multipart/form-data"
                >
                @csrf
                @if (isset($lelang))
                    @method('PUT')
                    <input id="lelangID" type="hidden" name="lelang_id" value="{{ $lelang->id }}">
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
                    <div class="flex gap-2 w-full justify-between">
                        <div class="w-full">
                            <label for="tanggal_dibuka" class="block font-medium text-gray-700">Tanggal Dibuka:</label>
                            <input type="date" name="tanggal_dibuka"
                                value="{{ old('tanggal_dibuka', $tanggalDibuka ?? '') }}"
                                required
                                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="w-50">
                            <div>
                                <label for="waktu_dibuka" class="block font-medium text-gray-700">Waktu Dibuka:</label>
                                <select name="waktu_dibuka" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach (range(0, 23) as $hour)
                                        <option value="{{ sprintf('%02d:00', $hour) }}"
                                                {{ old('waktu_dibuka', $lelang->waktu_dibuka ?? '') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                            {{ sprintf('%02d:00', $hour) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Tanggal Ditutup -->
                    <div class="flex gap-2 w-full justify-between">
                        <div class="w-full">
                            <label for="tanggal_ditutup" class="block font-medium text-gray-700">Tanggal Ditutup:</label>
                            <input type="date" name="tanggal_ditutup"
                                value="{{ old('tanggal_ditutup', $tanggalDitutup ?? '') }}"
                                required
                                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="w-50">
                            <label for="waktu_ditutup" class="block font-medium text-gray-700">Waktu Ditutup:</label>
                            <select name="waktu_ditutup" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach (range(0, 23) as $hour)
                                    <option value="{{ sprintf('%02d:00', $hour) }}"
                                            {{ old('waktu_ditutup', $lelang->waktu_ditutup ?? '') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                        {{ sprintf('%02d:00', $hour) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                        <div class="flex flex-col items-center mt-4 space-x-4
                            sm:items-start sm:flex-row"
                            >
                            <!-- Kotak Input untuk Upload -->
                            <div class="flex flex-col space-y-2">
                                <p class="text-sm text-gray-500"><br></p>
                                <label for="foto_produk" class="w-32 h-32 flex items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed text-gray-500 hover:border-blue-500">
                                    <span id="uploadPlaceholder" class="text-sm text-center">Klik untuk Upload</span>
                                    <input type="file" name="foto_produk" id="foto_produk" accept="image/*" class="hidden" onchange="previewImage(event)">
                                </label>
                            </div>
                            <!-- Foto Saat Ini (Hanya di Mode Edit) -->
                            @if(isset($lelang->foto_produk) && $lelang->foto_produk)
                                <div class="flex flex-col space-y-2">
                                    <p class="text-sm text-gray-500">Foto Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="Foto Produk" class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300" onclick="selectImage(this, 'Foto Saat Ini')" data-origin="foto saat ini">
                                </div>
                                <input type="hidden" name="currentEdit_img" id="currentEditImage" value="{{ $lelang->foto_produk }}">
                            @endif
                            <!-- Foto dari Katalog -->
                            <div class="flex flex-col space-y-2">
                                <p class="text-sm text-gray-500">Foto dari Katalog:</p>
                                <img id="katalogFoto" src="{{ isset($lelang->katalog->foto_produk) ? asset('storage/' . $lelang->katalog->foto_produk) : '' }}" alt="Foto Katalog" class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300 hidden" onclick="selectImage(this, 'Foto dari Katalog')" data-origin="foto dari katalog">
                            </div>
                            <!-- Pratinjau Gambar Baru -->
                            <div class="flex flex-col space-y-2">
                                <p class="text-sm text-gray-500">Pratinjau Upload Baru:</p>
                                <img id="uploadedImg" alt="Pratinjau Foto Baru" class="w-32 h-32 object-cover cursor-pointer border-2 border-gray-300 hidden" onclick="selectImage(this, 'Foto Upload Baru')" data-origin="foto unggahan baru">
                            </div>
                            <input type="hidden" name="selected_image" id="selectedImage" value="{{ $lelang->foto_produk ?? '' }}">
                            <input type="hidden" name="current_img" id="currentImage" value="{{ $lelang->foto_produk ?? '' }}">
                        </div>
                        {{-- tombol2 --}}
                        <div class="text-center">
                            <button type="button" onclick="window.history.back();"
                                class="px-4 py-2 mt-4 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 cursor-pointer">
                                Kembali
                            </button>
                            @if (isset($lelang))
                                <button type="button" id="confirmButton"
                                    class="px-4 py-2 bg-[#255B22] text-white rounded-lg shadow hover:bg-[#1d331c] focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 cursor-pointer"
                                    >
                                    Simpan Perubahan
                                </button>
                            @else
                                <button type="submit" id="confirmButton"
                                    class="px-4 py-2 bg-[#255B22] text-white rounded-lg shadow hover:bg-[#1d331c] focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 cursor-pointer">
                                    Simpan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

        </div>

    </div>

    {{-- logika pemilihan foto --}}
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
                    document.getElementById('currentImage').value = '';
                    if (defaultImage) {
                        selectImage(defaultImage, 'Foto Saat Ini');
                    } else {
                        document.getElementById('teksFotoDipilih').innerText = null;
                    }
                } else  {
                    katalogFoto.classList.remove('hidden');
                    selectImage(katalogFoto, 'Foto Dari Katalog');
                    document.getElementById('currentImage').value = fotoURL;
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
            console.log(document.getElementById('selectedImage').value);
        }

        // Pratinjau Foto Baru
        function previewImage(event) {
            const uploadedImg = document.getElementById('uploadedImg');
            const katalogFoto = document.getElementById('katalogFoto');
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
                } else if (katalogFoto) {
                    selectImage(katalogFoto, 'Foto Dari Katalog');
                } else  {
                    document.getElementById('selectedImage').value = '';
                    document.getElementById('teksFotoDipilih').innerText = null;
                }
            }
        }
    </script>

    <script>
        document.getElementById("confirmButton").addEventListener("click", () => {
            const form = document.getElementById("lelangForm");
            const lelangId = document.getElementById("lelangID").value;

            const metadataUrl = `/lelang/${lelangId}/edit-konfirm`;
            console.log(metadataUrl);
            fetch(metadataUrl, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
            })
            .then((response) => {
                if (!response.ok) throw new Error("Gagal mengambil metadata");
                return response.json();
            })
            .then((metadata) => {
                // Panggil showAlert dengan metadata dari backend
                showAlert({
                    title: metadata.title,
                    text: metadata.text,
                    icon: metadata.icon,
                    confirmButtonText: metadata.confirmButtonText,
                    cancelButtonText: metadata.cancelButtonText,
                    onConfirm: () => {
                        // Ubah form method ke PUT dan submit
                        const methodInput = document.createElement("input");
                        methodInput.type = "hidden";
                        methodInput.name = "_method";
                        methodInput.value = "PUT";
                        form.appendChild(methodInput);

                        form.submit();
                    },
                });
            })
            .catch((error) => {
                console.error("Error:", error);
                // Tampilkan notifikasi error jika diperlukan
            });
        });
    </script>


@endsection
