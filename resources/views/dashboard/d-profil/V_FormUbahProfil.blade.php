@extends('layouts.app')

@section('title', 'Ubah Profil')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')

    <div class="mb-5 flex flex-col gap-5 w-full">
        <h1 class="font-bold text-4xl">Ubah Profil Anda</h1>
        <div class="flex justify-between items-center w-full py-5">
            <div class="flex gap-5 items-center">
                <div>
                    <p class="mb-2">Foto Profil</p>
                    <div id="fotoProfil" class="h-24 w-24">
                        <img
                            src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('images/icons/defaultAvatarDark.svg') }}"
                            class="h-full w-full object-cover rounded-full border-2 border-white
                                 animasi-slide-kekanan
                            ">
                    </div>
                </div>
                <div class="flex gap-5">
                    <form action="{{ route('profil.update', ['field' => 'foto_profil']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="foto_profil"
                            class="p-5 flex items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed text-gray-500
                            hover:border-blue-500
                             animasi-slide-kekanan
                            ">
                            <span id="uploadPlaceholder" class="text-sm text-center">Klik untuk Upload<br>Foto Profil Baru</span>
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>
                    <form action="{{ route('profil.update', ['field' => 'foto_profil']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="reset" value="true">
                        <button type="submit"
                            class="p-5 flex items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed text-gray-500
                            hover:border-blue-500 text-sm text-center
                             animasi-slide-kekanan
                            ">
                            Reset<br>Foto Profil
                        </button>
                    </form>
                </div>
            </div>
            <a href="{{ route('profil.index') }}"
                class="border-2 border-dashed p-5 text-center h-[50%]
                    hover:bg-gray-300"
                >
                Kembali ke Profil
            </a>
        </div>

        @foreach (['name' => 'Nama lengkap', 'email' => 'Email', 'no_telp' => 'Nomor Telepon'] as $field => $label)
        <div class=" animasi-slide-kekanan">
            <form action="{{ route('profil.update', ['field' => $field]) }}" method="POST">
                @csrf
                <label for="{{ $field }}">{{ $label }}</label>
                <div class="flex items-center gap-2">
                    <input
                        type="text"
                        name="value"
                        id="{{ $field }}"
                        value="{{ old($field, Auth::user()->$field) }}"
                        class="border-1 p-2 w-full border-canceledhov rounded"
                        {{ $field === 'email' ? 'type=email' : '' }}>
                    <button type="button" class="confirm-save-btn bg-edit text-white px-4 py-2 rounded
                        hover:bg-edithov transition"
                    >
                        Simpan
                    </button>
                </div>
            </form>
            @error($field)
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>
        @endforeach

        <!--ALAMAT-->
        <div class="border-1 p-5 border-canceledhov rounded
             animasi-slide-kekanan
            ">
            <span class="absolute -mt-9 bg-white p-1 font-medium">Alamat</span>

            <form action="{{ route('profil.update', ['field' => 'alamat']) }}" method="POST" id="alamatForm" class="flex flex-col gap-4">
                @csrf

                <div class="flex flex-col w-full gap-5 mt-1
                    md:flex-row">
                    <div class="w-full">
                        <label for="provinsi">Provinsi</label>

                        <select name="provinsi_id" id="provinsi" class="border-1 p-2 w-full rounded" required>
                            <option value="{{ $profil->alamat_id ? $profil->alamat->city->provinsi->id : '' }}"
                            >
                                {{ $profil->alamat_id ? $profil->alamat->city->provinsi->nama_provinsi : 'Pilih Provinsi' }}
                            </option>
                            @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->id }}">{{ $provinsi->nama_provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full">
                        <label for="city">Kabupaten/Kota</label>
                        <select name="city_id" id="city" class="border-1 p-2 w-full rounded" disabled required>
                            <option value="" {{ $profil->alamat && $profil->alamat->city ? '' : 'selected'}} disabled>Pilih Kota</option>
                            @if ($profil->alamat_id)
                            <option value="{{ $profil->alamat->city->id }}" selected>{{ $profil->alamat->city->nama_city }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div>
                    <label for="detail">Detail Alamat</label>
                    @if ($profil->alamat_id)
                    <input type="text" name="detail_alamat" id="detail" class="border-1 p-2 w-full rounded" value="{{ $profil->alamat->detail_alamat }}"
                        placeholder="Hingga tingkat kecamatan, contoh : Jl. MT. Haryono No.169, Ketawanggede, Kecamatan Lowokwaru" required
                    >
                    @else
                    <input type="text" name="detail_alamat" id="detail" class="border-1 p-2 w-full rounded"
                        placeholder="Hingga tingkat kecamatan, contoh : Jl. MT. Haryono No.169, Ketawanggede, Kecamatan Lowokwaru" required
                    >
                    @endif
                </div>
                <div>
                    <label for="kodePos">Kode Pos</label>
                    @if ($profil->alamat_id)
                    <input type="text" name="kode_pos" id="kodePos" class="border-1 p-2 w-full rounded" value="{{ $profil->alamat->kode_pos }}"
                        placeholder="Contoh : 68123" required
                    >
                    @else
                    <input type="text" name="kode_pos" id="kodePos" class="border-1 p-2 w-full rounded"
                        placeholder="Contoh : 68123" required
                    >
                    @endif
                </div>
                <div>
                    <button type="button" id="submitBtn" disabled
                        class="confirm-save-btn bg-edit text-white px-4 py-2 rounded
                            hover:bg-edithov transition"
                    >
                        Simpan
                    </button>
                </div>


            </form>
        </div>

        <!--PASSWORD-->
        <div class="border-1 p-5 border-canceledhov rounded
            animasi-slide-kekanan
            ">
            <span class="absolute -mt-9 bg-white p-1 font-medium">Password</span>

            <form action="{{ route('profil.update') }}" method="POST" class="mt-1 flex flex-col gap-4">
                @csrf
                <input type="hidden" name="field" value="password">
                <!-- Password Lama (readonly) -->
                <div>
                    <label for="password">Password Saat Ini</label>
                    <input type="password" name="current_password" id="password" value="********" readonly
                        class="border-1 p-2 w-full rounded bg-canceled cursor-not-allowed"
                    >
                </div>
                <!-- Password Baru -->
                <div>
                    <label for="new_password">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="new_password" required
                            class="border-1 p-2 w-full rounded"
                        >
                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer flex items-center justify-center w-6 h-6 hover:text-[rgba(15,55,20,1)] transition"
                            id="toggleIcon"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[#638B35]">
                                <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                            class="border-1 p-2 w-full rounded"
                        >
                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer flex items-center justify-center w-6 h-6 hover:text-[rgba(15,55,20,1)] transition"
                            id="toggleIconConfirm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[#638B35]">
                                <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <!-- Tombol Simpan -->
                <div>
                    <button
                        type="button"
                        class="confirm-save-btn bg-edit text-white px-4 py-2 rounded
                            hover:bg-edithov transition"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('new_password');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleIcon.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // ubah ikon berdasarkan status
            toggleIcon.innerHTML = isPassword
                ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[rgba(15,55,20,1)]">
                        <path d="M4 12C4 12 5.6 7 12 7M12 7C18.4 7 20 12 20 12M12 7V4M18 5L16 7.5M6 5L8 7.5M15 13C15 14.6569 13.6569 16 12 16C10.3431 16 9 14.6569 9 13C9 11.3431 10.3431 10 12 10C13.6569 10 15 11.3431 15 13Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon">
                        <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`;
        });

        const confirmPasswordInput = document.getElementById('new_password_confirmation');
        const confirmToggleIcon = document.getElementById('toggleIconConfirm');

        confirmToggleIcon.addEventListener('click', () => {
            const isPassword = confirmPasswordInput.type === 'password';
            confirmPasswordInput.type = isPassword ? 'text' : 'password';

            // ubah ikon berdasarkan status
            confirmToggleIcon.innerHTML = isPassword
                ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon text-[rgba(15,55,20,1)]">
                        <path d="M4 12C4 12 5.6 7 12 7M12 7C18.4 7 20 12 20 12M12 7V4M18 5L16 7.5M6 5L8 7.5M15 13C15 14.6569 13.6569 16 12 16C10.3431 16 9 14.6569 9 13C9 11.3431 10.3431 10 12 10C13.6569 10 15 11.3431 15 13Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="eye-icon">
                        <path d="M4 10C4 10 5.6 15 12 15M12 15C18.4 15 20 10 20 10M12 15V18M18 17L16 14.5M6 17L8 14.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>`;
        });

        document.addEventListener('DOMContentLoaded', function () {
            const provinsiSelect = document.getElementById('provinsi');
            const citySelect = document.getElementById('city');
            const detailInput = document.getElementById('detail');
            const kodeposInput = document.getElementById('kodePos');
            const submitBtn = document.getElementById('submitBtn');

            // Enable city dropdown and fetch cities when province changes
            provinsiSelect.addEventListener('change', function () {
                const provinsiId = this.value;
                citySelect.disabled = true;
                citySelect.innerHTML = '<option value="" selected disabled>Loading...</option>';

                fetch(`/api/cari-city/${provinsiId}`) // Ganti URL ini dengan endpoint API Anda
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="" selected disabled>Pilih Kota</option>';
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.nama_city;
                            citySelect.appendChild(option);
                        });
                        citySelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                        citySelect.innerHTML = '<option value="" selected disabled>Gagal memuat kota</option>';
                    });
            });

            // Enable/Disable submit button based on validation
            [provinsiSelect, citySelect, detailInput, kodeposInput].forEach(input => {
                input.addEventListener('input', function () {
                    submitBtn.disabled = !(provinsiSelect.value && citySelect.value && detailInput.value.trim() &&kodeposInput.value.trim());
                });
            });

            // Seleksi semua tombol simpan
            const saveButtons = document.querySelectorAll('.confirm-save-btn');

            // Tambahkan event listener ke setiap tombol
            saveButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Cegah pengiriman formulir langsung

                    const form = this.closest('form'); // Ambil formulir terkait tombol

                    // Tampilkan SweetAlert
                    showAlert({
                        title: 'Apakah Anda Yakin?',
                        text: 'Perubahan akan disimpan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                        onConfirm: function () {
                            form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                        }
                    });
                });
            });
        });

    </script>

@endsection





