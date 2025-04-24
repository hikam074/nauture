<x-layout>
    @include('lelang.form')

    <div class="bg-white rounded-lg p-6 w-[90%] mx-auto mb-12 text-black">
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
                {{-- kode lelang --}}
                <p class="mt-4 text-xl">Kode Lelang</p>
                <p class="text-gray-600">{{ $lelang->kode_lelang }}</p>
            </div>

            {{-- FLEX KANAN --}}
            <div class="lg:w-4/5 flex flex-col gap-8 shadow-xs rounded-sm px-4">
                {{-- Div 1: Informasi --}}
                <div>
                    <div>
                        {{-- nama --}}
                        <p class="text-3xl mb-2">{{ $lelang->nama_produk_lelang }}</p>
                        {{-- start from --}}
                        <p class="font-light mb-3 text-gray-500">Dimulai Dari Rp {{ number_format($lelang->harga_dibuka, 0, ',', '.') }}</p>
                        {{-- berat --}}
                        <p class="mb-5">Kuantitas : {{ $lelang->jumlah_kg }} kg</p>
                        <div>
                            {{-- penawar tertinggi --}}
                            @if ($lelang->pasangLelang->isNotEmpty())
                                @php
                                    $topBid = $lelang->pasangLelang->sortByDesc('harga_pengajuan')->first();
                                @endphp
                                <p class="py-2 flex items-center space-x-3">
                                    @if ($topBid->user->foto_profil)
                                        <img src="{{ asset('storage/' . $topBid->user->foto_profil) }}" alt="Foto Profil {{ $topBid->user->name }}" class="w-10 h-10 rounded-full">
                                    @else
                                        <img src="{{ asset('images/assets/defaultAvatar.svg') }}" alt="Foto Profil Default" class="w-10 h-10 rounded-full">
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

                {{-- Div 2: Countdown dan Tombol Aksi --}}
                <div class="">
                    @php
                        // Ambil tawaran dari user yang sedang login untuk lelang ini
                        $userBids = $lelang->pasangLelang->where('user_id', Auth::id())->first();
                    @endphp
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-lg font-medium">Lelang berakhir pada</p>
                        @if (!is_null($userBids))
                            <p class="text-sm font-medium text-gray-700">Tawaran anda saat ini : <strong>Rp {{ number_format($userBids->harga_pengajuan, 0, ',', '.') }}</strong></p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        {{-- Countdown --}}
                        <div class="flex gap-2 items-center">
                            <div id="countdown" class="flex gap-2">
                                {{-- Hari --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="days" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Hari</p>
                                </div>
                                <div class="flex items-center justify-center">:</div>
                                {{-- Jam --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="hours" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Jam</p>
                                </div>
                                <div class="flex items-center justify-center">:</div>
                                {{-- Menit --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="minutes" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Menit</p>
                                </div>
                                <div class="flex items-center justify-center">:</div>
                                {{-- Detik --}}
                                <div class="border text-center py-2 px-3 rounded-lg">
                                    <span id="seconds" class="text-xl">00</span>
                                    <p class="text-sm text-gray-500">Detik</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            {{-- Tombol aksi --}}
                            @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
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
                                @if ($lelang->trashed())
                                    {{-- Tombol dummy abu-abu untuk lelang yang sudah dihapus --}}
                                    <button
                                        class="px-4 py-[1rem] text-sm font-medium text-white text-center bg-gray-400 rounded-lg transition"
                                        disabled>
                                        Lelang Dihapus
                                    </button>
                                @elseif (is_null($userBids))
                                    {{-- Tombol hijau untuk pasang tawaran --}}
                                    <button
                                        class="px-4 py-[1rem] text-sm font-medium text-white text-center bg-[#255B22] hover:bg-[#0F3714] rounded-lg transition"
                                        onclick="document.getElementById('bidForm').classList.remove('hidden')"
                                        id="toggleLelang">
                                        Pasang Tawaran
                                    </button>
                                @else
                                    {{-- Tombol biru untuk ubah tawaran --}}
                                    <button
                                        class="px-4 py-[1rem] text-sm font-medium text-white text-center bg-blue-500 hover:bg-blue-600 rounded-lg transition"
                                        onclick="document.getElementById('bidForm').classList.remove('hidden'); fillBidForm({{ $userBids->harga_pengajuan ?? '0' }})"
                                        id="toggleLelang">
                                        Ubah Tawaran
                                    </button>
                                    {{-- Tombol merah untuk batalkan tawaran --}}
                                    <form action="{{ route('lelang.destroy', ['id' => $lelang->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan tawaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="px-4 py-[1rem] text-sm font-medium text-white text-center bg-red-500 hover:bg-red-600 rounded-lg transition">
                                            Batalkan Tawaran
                                        </button>
                                    </form>
                                @endif
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
                        {{-- 3 penawar teratas --}}
                            @foreach ($lelang->pasangLelang->sortByDesc('harga_pengajuan')->take(3) as $bid)
                                <li class="py-2 border-b flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        {{-- foto profil --}}
                                        @if ($bid->user->foto_profil)
                                            <img src="{{ asset('storage/' . $bid->user->foto_profil) }}" alt="Foto Profil {{ $bid->user->name }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <img src="{{ asset('images/assets/defaultAvatar.svg') }}" alt="Foto Profil Default" class="w-10 h-10 rounded-full">
                                        @endif
                                        {{-- harga pengajuan --}}
                                        <div>
                                            <span class="font-semibold">{{ $bid->user->name }}</span><br>
                                            <span class="text-gray-600">Rp {{ number_format($bid->harga_pengajuan, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    {{-- tanggal pengajuan --}}
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
            if (toggleButton) {
                toggleButton.addEventListener("click", function () {
                    bidForm.classList.remove("hidden");
                    bidForm.classList.add("flex");
                    updateCountdown(); // Memastikan nilai waktu langsung diperbarui saat popup dibuka
                });
            }

            // Interval untuk memperbarui countdown
            const timer = setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>

</x-layout>
