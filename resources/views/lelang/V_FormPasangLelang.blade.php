<div id="bidForm" class="fixed inset-0 text-black bg-black/30 justify-center items-start hidden z-50">
    <div id="popupContent" class="bg-white w-[70%] p-6 rounded-lg shadow-lg mt-17
        sm:mt-20
        transform transition-all duration-500 opacity-0 -translate-y-10"
        >
        <div class="text-lg">
            @if ($lelang->pasangLelang->isNotEmpty())
                @php
                    $topBid = $lelang->pasangLelang->sortByDesc('harga_pengajuan')->first();
                @endphp
                <p>Tawaran tertinggi : <strong> Rp {{ number_format($topBid->harga_pengajuan, 0, ',', '.') }} </strong></p>
            @else
                <p>Tawaran tertinggi = <strong>Belum ada penawaran</strong></p>
            @endif
        </div>
        <p class="text-gray-500 my-1 text-sm">
            Tawaran akan ditambah dengan ongkos kirim ketika anda memenangkan lelang ini
        </p>

        <form action="{{ route('lelang.bid', $lelang->id) }}" method="POST">
            @csrf
            <div class="mb-1
                sm:mb-3"
                >
                <label for="harga_pengajuan" class="block text-lg">Masukkan Penawaran</label>
                <!-- form isi  harga bid -->
                <div class="relative">
                    <input type="number" id="harga_pengajuan" name="harga_pengajuan" value="" required
                        class="mt-2 p-2 w-full rounded-md border border-black shadow-sm">
                    <p id="formattedNominal" class="text-gray-600 mt-1 text-sm"></p>
                </div>
                <input type="hidden" name="lelang_id" id="lelangID" value="{{ $lelang->id}}">
            </div>

            <p class="mb-1 text-lg
                sm:mb-3"
                >
                Lelang berakhir pada
            </p>
            <div class="flex flex-col justify-between gap-2
                lg:flex-row lg:gap-4"
                >
                <div class="">
                    <!-- Countdown -->
                    <div class="flex flex-col gap-1 items-center justify-center
                        sm:justify-start"
                        >
                        <div id="countdown"
                            class="flex gap-1.5 text-center scale-75
                            sm:scale-100"
                            >
                            <!-- Hari -->
                            <div class="border py-2 px-3 rounded-lg">
                                <span id="daysPopup" class="text-xl md:text-lg lg:text-xl">00</span>
                                <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Hari</p>
                            </div>
                            <div class="flex items-center">:</div>
                            <!-- Jam -->
                            <div class="border py-2 px-3 rounded-lg">
                                <span id="hoursPopup" class="text-xl md:text-lg lg:text-xl">00</span>
                                <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Jam</p>
                            </div>
                            <div class="flex items-center">:</div>
                            <!-- Menit -->
                            <div class="border py-2 px-3 rounded-lg">
                                <span id="minutesPopup" class="text-xl md:text-lg lg:text-xl">00</span>
                                <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Menit</p>
                            </div>
                            <div class="flex items-center">:</div>
                            <!-- Detik -->
                            <div class="border py-2 px-3 rounded-lg">
                                <span id="secondsPopup" class="text-xl md:text-lg lg:text-xl">00</span>
                                <p class="text-sm text-gray-500 md:txt-xs lg:text-sm">Detik</p>
                            </div>
                        </div>
                        <p class="font-light text-gray-500"
                            >
                            {{ $lelang->tanggal_dibuka->format('d M Y, H:i') }} - {{ $lelang->tanggal_ditutup->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                <!-- EULA -->
                <div class="flex flex-col gap-2
                    md:flex-row md:gap-4"
                    >
                    <div class="flex-1">
                        <p class="text-sm text-justify lg:max-w-3xs">
                            Ketika anda klik pasang maka anda setuju untuk membeli item ini ketika anda memenangkan lelang.
                        </p>
                    </div>
                    <button type="button" id="closePopup" onclick="clsPopup()"
                        class="px-6 py-3 text-sm font-medium text-center rounded-lg border border-sekunderDark
                            hover:bg-gray-300 hover:border-black transition"
                        >
                        Kembali
                    </button>
                    <button id="submit" type="submit"
                        class="px-6 py-3 text-sm font-medium text-white text-center rounded-lg bg-sekunderDark border border-sekunderDark
                            hover:bg-primer transition"
                        >
                        Pasang
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- logic menampilkan input berformat jam --}}
<script>
    const inputHargaPengajuan = document.getElementById('harga_pengajuan');
    const formattedNominal = document.getElementById('formattedNominal');
    const bidForm = document.getElementById("bidForm");
    const popupContent = document.getElementById('popupContent');

    // Format input saat user mengetik dan tampilkan di elemen teks
    inputHargaPengajuan.addEventListener('input', function () {
        const rawValue = this.value.replace(/\D/g, ''); // Hanya angka
        const formattedValue = new Intl.NumberFormat('id-ID').format(rawValue); // Format dengan pemisah ribuan

        // Tampilkan nilai diformat di bawah input
        formattedNominal.textContent = rawValue ? `Nominal: Rp ${formattedValue}` : '';
    });

    // Kirim angka tanpa format titik
    inputHargaPengajuan.addEventListener('blur', function () {
        const rawValue = this.value.replace(/\D/g, ''); // Hapus format titik
        this.dataset.rawValue = rawValue; // Simpan nilai asli sebagai data-raw-value
    });

    // Tangkap nilai asli saat formulir dikirimkan
    document.forms[0].addEventListener('submit', function (event) {
        inputHargaPengajuan.value = inputHargaPengajuan.dataset.rawValue || ''; // Ubah kembali ke angka mentah
        console.log(inputHargaPengajuan.value); // Cetak variabel ke console
    });

    function fillBidForm(bidValue) {
        const bidInput = document.getElementById('harga_pengajuan');
        const formattedNominal = document.getElementById('formattedNominal');

        // Isi nilai lama ke input
        bidInput.value = bidValue;

        // Format nilai untuk ditampilkan di bawah input
        const formattedValue = new Intl.NumberFormat('id-ID').format(bidValue);
        formattedNominal.textContent = bidValue ? `Nominal: Rp ${formattedValue}` : '';
    };

</script>
