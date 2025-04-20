<x-layout>
    @include('lelang.form')

    <div class="bg-white rounded-lg p-6 w-[90%] mx-auto mb-12 text-black">
        {{-- <h3 class="text-xl font-semibold mb-4">Detail Lelang</h3> --}}

        <div class="flex flex-col gap-10 lg:flex-row">
            {{-- FLEX KIRI --}}
            <div class="lg:w-1/5">
                {{-- Foto Produk --}}
                @if ($lelang->foto_produk)
                    <div class="rounded-lg overflow-hidden w-full max-w-md border-gray-100 border-12">
                        <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="Foto Produk Lelang" class="w-full rounded-lg">
                    </div>
                @else
                    <p class="text-gray-500">Tidak ada foto produk tersedia.</p>
                @endif

                {{-- Deskripsi --}}
                <p class="mt-4 text-xl">Deskripsi</p>
                <p class="text-gray-600">{{ $lelang->keterangan ?? 'Tidak ada keterangan' }}</p>
                {{-- <p><strong>Kode Lelang:</strong> {{ $lelang->kode_lelang }}</p> --}}
            </div>

            {{-- FLEX KANAN --}}
            <div class="lg:w-4/5 flex flex-col gap-8 shadow-xs rounded-sm px-4">
                {{-- Div 1: Informasi --}}
                <div class="">
                    <div>
                        {{-- <p><strong>Kode Lelang:</strong> {{ $lelang->kode_lelang }}</p> --}}
                        <p class="text-3xl mb-2">{{ $lelang->nama_produk_lelang }}</p>
                        <p class="font-light mb-3 text-gray-500">Dimulai Dari Rp {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</p>
                        <p class="mb-5">Kuantitas : {{ $lelang->jumlah_kg }} kg</p>
                        {{-- <p><strong>Tanggal Dibuka:</strong> {{ $lelang->tanggal_dibuka->format('d M Y, H:i') }}</p>
                        <p><strong>Tanggal Ditutup:</strong> {{ $lelang->tanggal_ditutup->format('d M Y, H:i') }}</p> --}}
                        <div>
                            @if ($lelang->pasangLelang->isNotEmpty())
                                @php
                                    $topBid = $lelang->pasangLelang->sortByDesc('harga_pengajuan')->first();
                                @endphp
                                <p class="py-2">
                                    Nama: {{ $topBid->user->name }}<br>
                                    Harga: Rp {{ number_format($topBid->harga_pengajuan, 0, ',', '.') }}
                                </p>
                            @else
                                <p class="text-gray-500">Belum ada penawaran.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Div 2: Countdown dan Tombol Aksi --}}
                <div class="">

                    <p class="mb-3">Lelang berakhir pada</p>
                    <div class="flex items-center justify-between">
                        {{-- Countdown --}}
                        <div class="flex gap-2 items-center">
                            <div id="countdown" class="flex gap-2">
                                {{-- hari --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="days" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Hari</p>
                                </div>
                                <div class="flex items-center justify-center">:</div>
                                {{-- jam --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="hours" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Jam</p>
                                </div>
                                <div class="flex items-center justify-center">:</div>
                                {{-- menit --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="minutes" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Menit</p>
                                </div>
                                <div class="flex items-center justify-center">:</div>
                                <!-- detik -->
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="seconds" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Detik</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            {{-- Tombol aksi --}}
                            @if ((Auth::check() && Auth::user()->role->nama_role == 'pegawai'))
                                @if ($lelang->trashed())
                                    <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-yellow-500 text-white px-4 py-[1rem] text-sm font-medium rounded-lg hover:bg-yellow-600">
                                            Restore
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('lelang.edit', $lelang->id) }}" class="px-4 py-[1rem] text-sm font-medium border border-[#0F3714] rounded-lg hover:bg-[#255B22] hover:text-white">
                                        Edit
                                    </a>
                                    <form action="{{ route('lelang.destroy', $lelang->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white text-sm font-medium px-4 py-[1rem] rounded-lg hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            @elseif ((!Auth::check() || (Auth::user()->role->nama_role == 'customer')))
                                <button href="{{ route('lelang.form', $lelang->id) }}" class="px-4 py-[1rem] text-sm font-medium text-white text-center bg-[#255B22] rounded-lg hover:bg-[#0F3714] transition"
                                    onclick="document.getElementById('bidForm').classList.remove('hidden')" id="toggleLelang">
                                    Pasang Tawaran
                                </button>
                            @endif
                        </div>
                    </div>
                    <p class="font-light mt-3 text-gray-500">{{ $lelang->tanggal_dibuka->format('d M Y, H:i') }} - {{ $lelang->tanggal_ditutup->format('d M Y, H:i') }}</p>
                </div>

                {{-- Div 3: Penawar --}}
                <div class="">
                    <h4 class="text-3xl mb-2">Penawaran</h4>
                    <ul>
                        @if ($lelang->pasangLelang->isNotEmpty())
                            @foreach ($lelang->pasangLelang->sortByDesc('harga_pengajuan')->take(3) as $bid)
                            <li class="py-2 border-b flex justify-between">
                                <span>{{ $bid->user->name }}</span>
                                <span>Rp {{ number_format($bid->harga_pengajuan, 0, ',', '.') }}</span>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const bidForm = document.getElementById("bidForm");
            const toggleButton = document.getElementById("toggleLelang");

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
            toggleButton.addEventListener("click", function () {
                bidForm.classList.remove("hidden");
                bidForm.classList.add("flex");
                updateCountdown(); // Memastikan nilai waktu langsung diperbarui saat popup dibuka
            });

            // Interval untuk memperbarui countdown
            const timer = setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>

</x-layout>
