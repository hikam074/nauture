@extends('layouts.app')

@section('title', 'Detail Lelang')

@section('content')
    @include('lelang.V_FormPasangLelang')

    <div class="bg-white rounded-lg p-6 w-full mx-auto mb-12 text-black">
        <div class="flex flex-col gap-5
            md:flex-row">

            <!-- FLEX KIRI -->
            <div class="md:w-70 flex gap-5 md:flex-col">
                <!-- Foto Produk -->
                <div class="w-100 h-60 max-w-60">
                    @if ($lelang->foto_produk)
                        <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="Foto Produk Lelang"
                            class="w-full rounded-lg max-h-60 aspect-square object-cover border-5 border-gray-200"
                        >
                    @else
                        <p class="text-gray-500">Tidak ada foto produk tersedia.</p>
                    @endif
                </div>
                <div>
                    <!-- Deskripsi -->
                    <div>
                        <p class="mt-4 text-xl">Deskripsi</p>
                        <p class="text-gray-600">{{ $lelang->keterangan ?? 'Tidak ada keterangan' }}</p>
                    </div>
                    <!-- kode lelang -->
                    <div>
                        <p class="mt-4 text-xl">Kode Lelang</p>
                        <p class="text-gray-600">{{ $lelang->kode_lelang }}</p>
                    </div>
                </div>
            </div>

            <!-- FLEX KANAN -->
            <div class=" flex flex-col gap-8 shadow-xs rounded-sm px-4 w-full">
                <!-- Div 1: Informasi -->
                <div>
                    <div>
                        <!-- nama -->
                        <p class="text-3xl mb-2">{{ $lelang->nama_produk_lelang }}</p>
                        <!-- start from -->
                        <p class="font-light mb-3 text-gray-500">Dimulai Dari Rp {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</p>
                        <!-- berat -->
                        <p class="mb-5">Kuantitas : {{ $lelang->jumlah_kg }} kg</p>
                        <div>
                            <!-- penawar tertinggi -->
                            @if ($topBid)
                                <p class="p-2 flex items-center space-x-3 bg-warning">
                                    @if ($topBid->user->foto_profil)
                                        <img src="{{ asset('storage/' . $topBid->user->foto_profil) }}" alt="Foto Profil {{ $topBid->user->name }}"
                                        class="w-10 h-10 rounded-full"
                                        >
                                    @else
                                        <svg width="40px" height="40px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="2 2 20 20">
                                            <path d="M22 12C22 6.49 17.51 2 12 2C6.49 2 2 6.49 2 12C2 14.9 3.25 17.51 5.23 19.34C5.23 19.35 5.23 19.35 5.22 19.36C5.32 19.46 5.44 19.54 5.54 19.63C5.6 19.68 5.65 19.73 5.71 19.77C5.89 19.92 6.09 20.06 6.28 20.2C6.35 20.25 6.41 20.29 6.48 20.34C6.67 20.47 6.87 20.59 7.08 20.7C7.15 20.74 7.23 20.79 7.3 20.83C7.5 20.94 7.71 21.04 7.93 21.13C8.01 21.17 8.09 21.21 8.17 21.24C8.39 21.33 8.61 21.41 8.83 21.48C8.91 21.51 8.99 21.54 9.07 21.56C9.31 21.63 9.55 21.69 9.79 21.75C9.86 21.77 9.93 21.79 10.01 21.8C10.29 21.86 10.57 21.9 10.86 21.93C10.9 21.93 10.94 21.94 10.98 21.95C11.32 21.98 11.66 22 12 22C12.34 22 12.68 21.98 13.01 21.95C13.05 21.95 13.09 21.94 13.13 21.93C13.42 21.9 13.7 21.86 13.98 21.8C14.05 21.79 14.12 21.76 14.2 21.75C14.44 21.69 14.69 21.64 14.92 21.56C15 21.53 15.08 21.5 15.16 21.48C15.38 21.4 15.61 21.33 15.82 21.24C15.9 21.21 15.98 21.17 16.06 21.13C16.27 21.04 16.48 20.94 16.69 20.83C16.77 20.79 16.84 20.74 16.91 20.7C17.11 20.58 17.31 20.47 17.51 20.34C17.58 20.3 17.64 20.25 17.71 20.2C17.91 20.06 18.1 19.92 18.28 19.77C18.34 19.72 18.39 19.67 18.45 19.63C18.56 19.54 18.67 19.45 18.77 19.36C18.77 19.35 18.77 19.35 18.76 19.34C20.75 17.51 22 14.9 22 12ZM16.94 16.97C14.23 15.15 9.79 15.15 7.06 16.97C6.62 17.26 6.26 17.6 5.96 17.97C4.44 16.43 3.5 14.32 3.5 12C3.5 7.31 7.31 3.5 12 3.5C16.69 3.5 20.5 7.31 20.5 12C20.5 14.32 19.56 16.43 18.04 17.97C17.75 17.6 17.38 17.26 16.94 16.97Z" fill="#292D32"/>
                                            <path d="M12 6.92969C9.93 6.92969 8.25 8.60969 8.25 10.6797C8.25 12.7097 9.84 14.3597 11.95 14.4197C11.98 14.4197 12.02 14.4197 12.04 14.4197C12.06 14.4197 12.09 14.4197 12.11 14.4197C12.12 14.4197 12.13 14.4197 12.13 14.4197C14.15 14.3497 15.74 12.7097 15.75 10.6797C15.75 8.60969 14.07 6.92969 12 6.92969Z" fill="#292D32"/>
                                        </svg>
                                    @endif
                                    <span>
                                        Penawaran tertinggi oleh <strong>{{ $topBid->user->name }}</strong><br>
                                        Tawaran : <strong>Rp {{ number_format($topBid->harga_pengajuan, 0, ',', '.') }}</strong>
                                    </span>
                                </p>
                            @else
                                <p class="text-gray-500">Belum ada penawaran.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Div 2: Countdown dan Tombol Aksi -->
                <div class="">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-lg font-medium">Lelang berakhir pada</p>
                        @if (!is_null($userBids))
                            <p class="text-sm text-right font-medium text-gray-700">
                                Tawaran terbaru anda :<br>
                                <strong>Rp {{ number_format($userBids->harga_pengajuan, 0, ',', '.') }}</strong>
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col justify-center gap-5
                        sm:flex-row sm:justify-between"
                        >
                        <!-- Countdown -->
                        <div class="flex-col gap-2 items-center text-center w-full
                            sm:w-auto">
                            <div id="countdown" class="flex gap-1.5 w-full justify-center
                                sm:w-auto sm:justify-start">
                                <!-- Hari -->
                                <div class="border py-2 px-3 rounded-lg">
                                    <span id="days" class="text-xl md:text-lg lg:text-xl">00</span>
                                    <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Hari</p>
                                </div>
                                <div class="flex items-center">:</div>
                                <!-- Jam -->
                                <div class="border py-2 px-3 rounded-lg">
                                    <span id="hours" class="text-xl md:text-lg lg:text-xl">00</span>
                                    <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Jam</p>
                                </div>
                                <div class="flex items-center">:</div>
                                <!-- Menit -->
                                <div class="border py-2 px-3 rounded-lg">
                                    <span id="minutes" class="text-xl md:text-lg lg:text-xl">00</span>
                                    <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Menit</p>
                                </div>
                                <div class="flex items-center">:</div>
                                <!-- Detik -->
                                <div class="border py-2 px-3 rounded-lg">
                                    <span id="seconds" class="text-xl md:text-lg lg:text-xl">00</span>
                                    <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Detik</p>
                                </div>
                            </div>
                            <p class="font-light mt-3 text-gray-500">
                                {{ $lelang->tanggal_dibuka->format('d M Y, H:i') }} - {{ $lelang->tanggal_ditutup->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <!-- Tombol aksi -->
                        <div class="flex gap-2 h-full w-full justify-between
                                text-sm font-medium text-center text-white
                                sm:w-auto sm:justify-start"
                            >
                            @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                                @if ($lelang->trashed())
                                    <a
                                        class="p-4 bg-yellow-500
                                            hover:bg-yellow-600 transition"
                                        >
                                        <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit">
                                                Restore
                                            </button>
                                        </form>
                                    </a>
                                @else
                                    <a href="{{ route('lelang.edit', $lelang->id) }}"
                                        class="text-primer p-4 rounded-lg border border-primer w-full
                                            hover:bg-sekunderDark hover:text-white transition
                                            sm:w-auto"
                                        >
                                        <button>
                                            Edit
                                        </button>
                                    </a>
                                    <a class="p-4 bg-red-500 rounded-lg w-full
                                        hover:bg-red-600 transition
                                        sm:w-auto"
                                        >
                                        <form action="{{ route('lelang.show', $lelang->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                Hapus
                                            </button>
                                        </form>
                                    </a>
                                @endif
                            @elseif ((!Auth::check() || (Auth::user()->role->nama_role == 'customer')))
                                @if ($lelang->trashed())
                                    <a class="p-4 rounded-lg bg-gray-400 w-full
                                        sm:w-auto"
                                        >
                                        <button disabled>
                                            Lelang<br>Dihapus
                                        </button>
                                    </a>
                                @elseif ($lelang->tanggal_ditutup <= \Carbon\Carbon::now())
                                    <a class="p-4 rounded-lg bg-gray-400 w-full
                                        sm:w-auto"
                                        >
                                        <button disabled>
                                            Lelang<br>Berakhir
                                        </button>
                                    </a>
                                @elseif ((is_null($userBids))
                                    && ($lelang->tanggal_dibuka <= \Carbon\Carbon::now())
                                    && ($lelang->tanggal_ditutup > \Carbon\Carbon::now())
                                    )
                                    <a class="rounded-lg bg-sekunderDark w-full h-full
                                        hover:bg-primer transition"
                                        >
                                        <button id="toggleLelang"
                                            class="p-4 w-full
                                                sm:w-auto"
                                            >
                                            Pasang<br>Tawaran
                                        </button>
                                    </a>
                                @elseif ($userBids)
                                    <a class="rounded-lg bg-blue-500 w-full h-full
                                        hover:bg-blue-600 transition
                                        sm:w-auto"
                                        >
                                        <button id="toggleLelang" onclick="fillBidForm({{ $userBids->harga_pengajuan ?? '0' }})"
                                            class="p-4 block w-full h-full
                                                md:p-3 lg:p-4"
                                            >
                                            Ubah<br>Tawaran
                                        </button>
                                    </a>
                                    <a class="rounded-lg bg-red-500 w-full h-full
                                        hover:bg-red-600 transition
                                        sm:w-auto"
                                        >
                                        <form action="{{ route('lelang.destroy', ['id' => $lelang->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan tawaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-4 block w-full h-full
                                                md:p-3 lg:p-4"
                                                >
                                                Batalkan<br>Tawaran
                                            </button>
                                        </form>
                                    </a>
                                @else
                                    <a class="rounded-lg bg-canceledhov w-full h-full
                                        transition"
                                        >
                                        <button
                                            class="p-4 w-full
                                                sm:w-auto"
                                            >
                                            Menunggu<br>Dibuka
                                        </button>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Div 3: Penawar -->
                <div class="">
                    <h4 class="text-3xl mb-2">
                        Penawaran Teratas
                    </h4>
                    <ul>
                        @if ($lelang->pasangLelang->isNotEmpty())
                        <!-- 3 penawar teratas -->
                            @foreach ($lelang->pasangLelang->sortByDesc('harga_pengajuan')->take(5) as $bid)
                                <li class="py-2 border-b flex justify-between items-center"
                                    >
                                    <div class="flex items-center space-x-3">
                                        <!-- foto profil -->
                                        @if ($bid->user->foto_profil)
                                            <img src="{{ asset('storage/' . $bid->user->foto_profil) }}" alt="Foto Profil {{ $bid->user->name }}"
                                            class="w-10 h-10 rounded-full"
                                            >
                                        @else
                                            <svg width="40px" height="40px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="2 2 20 20">
                                                <path d="M22 12C22 6.49 17.51 2 12 2C6.49 2 2 6.49 2 12C2 14.9 3.25 17.51 5.23 19.34C5.23 19.35 5.23 19.35 5.22 19.36C5.32 19.46 5.44 19.54 5.54 19.63C5.6 19.68 5.65 19.73 5.71 19.77C5.89 19.92 6.09 20.06 6.28 20.2C6.35 20.25 6.41 20.29 6.48 20.34C6.67 20.47 6.87 20.59 7.08 20.7C7.15 20.74 7.23 20.79 7.3 20.83C7.5 20.94 7.71 21.04 7.93 21.13C8.01 21.17 8.09 21.21 8.17 21.24C8.39 21.33 8.61 21.41 8.83 21.48C8.91 21.51 8.99 21.54 9.07 21.56C9.31 21.63 9.55 21.69 9.79 21.75C9.86 21.77 9.93 21.79 10.01 21.8C10.29 21.86 10.57 21.9 10.86 21.93C10.9 21.93 10.94 21.94 10.98 21.95C11.32 21.98 11.66 22 12 22C12.34 22 12.68 21.98 13.01 21.95C13.05 21.95 13.09 21.94 13.13 21.93C13.42 21.9 13.7 21.86 13.98 21.8C14.05 21.79 14.12 21.76 14.2 21.75C14.44 21.69 14.69 21.64 14.92 21.56C15 21.53 15.08 21.5 15.16 21.48C15.38 21.4 15.61 21.33 15.82 21.24C15.9 21.21 15.98 21.17 16.06 21.13C16.27 21.04 16.48 20.94 16.69 20.83C16.77 20.79 16.84 20.74 16.91 20.7C17.11 20.58 17.31 20.47 17.51 20.34C17.58 20.3 17.64 20.25 17.71 20.2C17.91 20.06 18.1 19.92 18.28 19.77C18.34 19.72 18.39 19.67 18.45 19.63C18.56 19.54 18.67 19.45 18.77 19.36C18.77 19.35 18.77 19.35 18.76 19.34C20.75 17.51 22 14.9 22 12ZM16.94 16.97C14.23 15.15 9.79 15.15 7.06 16.97C6.62 17.26 6.26 17.6 5.96 17.97C4.44 16.43 3.5 14.32 3.5 12C3.5 7.31 7.31 3.5 12 3.5C16.69 3.5 20.5 7.31 20.5 12C20.5 14.32 19.56 16.43 18.04 17.97C17.75 17.6 17.38 17.26 16.94 16.97Z" fill="#292D32"/>
                                                <path d="M12 6.92969C9.93 6.92969 8.25 8.60969 8.25 10.6797C8.25 12.7097 9.84 14.3597 11.95 14.4197C11.98 14.4197 12.02 14.4197 12.04 14.4197C12.06 14.4197 12.09 14.4197 12.11 14.4197C12.12 14.4197 12.13 14.4197 12.13 14.4197C14.15 14.3497 15.74 12.7097 15.75 10.6797C15.75 8.60969 14.07 6.92969 12 6.92969Z" fill="#292D32"/>
                                            </svg>
                                        @endif
                                        <!-- harga pengajuan -->
                                        <div>
                                            <span class="font-semibold">
                                                {{ $bid->user->name }}
                                            </span><br>
                                            <span class="text-gray-600">
                                                Rp {{ number_format($bid->harga_pengajuan, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- tanggal pengajuan -->
                                    <div class="text-sm text-gray-500">
                                        {{ $bid->updated_at ? $bid->updated_at->format('d M Y, H:i') : $bid->created_at->format('d M Y, H:i') }}
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <p class="text-gray-500">Belum ada penawaran.</p>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- logika jam countdown --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const bidForm = document.getElementById("bidForm");
            const popupContent = document.getElementById('popupContent');
            const toggleButton = document.getElementById("toggleLelang");
            const adaBid = @json($userBids);

            const endDate = new Date("{{ $lelang->tanggal_ditutup }}").getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const timeLeft = endDate - now;

                if (timeLeft > 0) {
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    // Update elemen utama
                    document.getElementById("days").textContent = String(days).padStart(2, "0");
                    document.getElementById("hours").textContent = String(hours).padStart(2, "0");
                    document.getElementById("minutes").textContent = String(minutes).padStart(2, "0");
                    document.getElementById("seconds").textContent = String(seconds).padStart(2, "0");

                    // Jika popup terlihat, update elemen popup
                    if (!bidForm.classList.contains("hidden")) {
                        document.getElementById("daysPopup").textContent = String(days).padStart(2, "0");
                        document.getElementById("hoursPopup").textContent = String(hours).padStart(2, "0");
                        document.getElementById("minutesPopup").textContent = String(minutes).padStart(2, "0");
                        document.getElementById("secondsPopup").textContent = String(seconds).padStart(2, "0");
                    }
                } else {
                    // Waktu habis
                    document.getElementById("countdown").innerHTML = "<p class='text-red-500'>Lelang telah berakhir</p>";
                    clearInterval(timer);
                }
            }

            // Event untuk membuka popup
            if (toggleButton) {
                    toggleButton.addEventListener("click", function () {
                        toggleButton.innerHTML = '<span>Tunggu<br>Sebentar...</span>';
                        fetch('/lelang/auth', {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.loggedIn) {
                                // Tampilkan popup
                                bidForm.classList.remove("hidden");
                                bidForm.classList.add("flex");
                                setTimeout(() => {
                                    popupContent.classList.remove('opacity-0', '-translate-y-10');
                                    popupContent.classList.add('opacity-100', 'translate-y-0');
                                }, 10);
                            } else {
                                if (!adaBid) {
                                    toggleButton.innerHTML = '<span>Pasang<br>Tawaran</span>';
                                } else {
                                    toggleButton.innerHTML = '<span>Ubah<br>Tawaran</span>';
                                }
                                toastr.warning('Anda harus login terlebih dahulu untuk memasang tawaran.', 'Peringatan');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                }

            // Interval untuk memperbarui countdown
            const timer = setInterval(updateCountdown, 1000);
            updateCountdown();
        });

        function clsPopup() {
            const toggleButton = document.getElementById("toggleLelang");
            const adaBid = @json($userBids);

            if (!adaBid) {
                toggleButton.innerHTML = '<span>Pasang<br>Tawaran</span>';
            } else {
                toggleButton.innerHTML = '<span>Ubah<br>Tawaran</span>';
            }
            popupContent.classList.remove('opacity-100', 'translate-y-0');
            popupContent.classList.add('opacity-0', '-translate-y-10');
            popupContent.addEventListener(
                'transitionend',
                () => {
                    bidForm.classList.add('hidden'); // Sembunyikan setelah transisi selesai
                },
                { once: true }
            );
        }

    </script>

@endsection
