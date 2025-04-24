<div id="bidForm" class="fixed inset-0 text-black bg-black/30 justify-center items-start hidden z-50">
    <div class="bg-white w-[70%] p-6 rounded-lg shadow-lg mt-20">
        <div class="text-lg mb-2">
            @if ($lelang->pasangLelang->isNotEmpty())
                @php
                    $topBid = $lelang->pasangLelang->sortByDesc('harga_pengajuan')->first();
                @endphp
                <p>Tawaran tertinggi : <strong> Rp {{ number_format($topBid->harga_pengajuan, 0, ',', '.') }} </strong></p>
            @else
                <p>Tawaran tertinggi = <strong>Belum ada penawaran</strong></p>
            @endif
        </div>
        <p class="text-gray-500 mb-4 text-sm">Tawaran akan ditambah dengan ongkos kirim ketika anda<br>memenangkan lelang ini</p>


        <form action="{{ route('lelang.bid', $lelang->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="harga_pengajuan" class="block text-lg">Masukkan Penawaran</label>
                {{-- form isi  harga bid --}}
                <div class="relative">
                    <input type="number" id="harga_pengajuan" name="harga_pengajuan" value="" required
                        class="mt-2 p-2 w-full rounded-md border border-black shadow-sm">
                    <p id="formattedNominal" class="text-gray-600 mt-1 text-sm"></p>
                </div>
                <input type="hidden" name="lelang_id" id="lelangID" value="{{ $lelang->id}}">
            </div>

            <p class="mb-3 text-lg">Lelang berakhir pada</p>
            <div class="flex justify-between gap-2">
                <div>
                    {{-- Countdown --}}
                    <div class="flex gap-2 items-center">
                        <div id="countdown" class="flex gap-2">
                            {{-- hari --}}
                            <div class="border text-center py-2 px-3 rounded-lg">
                                <span id="daysPopup" class="text-base">00</span>
                                <p class="text-xs text-gray-500">Hari</p>
                            </div>
                            <div class="flex items-center justify-center">:</div>
                            {{-- jam --}}
                            <div class="border text-center py-2 px-3 rounded-lg">
                                <span id="hoursPopup" class="text-base">00</span>
                                <p class="text-xs text-gray-500">Jam</p>
                            </div>
                            <div class="flex items-center justify-center">:</div>
                            {{-- menit --}}
                            <div class="border text-center py-2 px-3 rounded-lg">
                                <span id="minutesPopup" class="text-base">00</span>
                                <p class="text-xs text-gray-500">Menit</p>
                            </div>
                            <div class="flex items-center justify-center">:</div>
                            <!-- detik -->
                            <div class="border text-center py-2 px-3 rounded-lg">
                                <span id="secondsPopup" class="text-base">00</span>
                                <p class="text-xs text-gray-500">Detik</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- EULA --}}
                <div class="flex gap-4">
                    <div class="flex-1">
                        <p class="text-sm text-justify max-w-3xs">
                            Ketika anda klik pasang maka anda setuju untuk membeli item ini ketika anda memenangkan lelang.
                        </p>
                    </div>
                    {{-- tombol kembali --}}
                    <button type="button" id="closePopup" class="px-6 py-[1rem] text-sm font-medium text-center border border-[#255B22] rounded-lg hover:bg-gray-300 hover:border-black transition"
                        onclick="document.getElementById('bidForm').classList.add('hidden'); document.getElementById('bidForm').classList.remove('flex')">
                        Kembali
                    </button>
                    {{-- tombol submit --}}
                    <button id="submit" type="submit" class="px-6 py-[1rem] text-sm font-medium text-white text-center bg-[#255B22] rounded-lg hover:bg-[#0F3714] transition">
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
    }
</script>
