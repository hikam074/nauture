
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


        <form {{-- action="{{ route('form.submit') }}"--}} method="POST">
            @csrf
            <div class="mb-4">
                <label for="hargaPenawaran" class="block text-lg">Masukkan Penawaran</label>
                <input type="text" id="hargaPenawaran" name="harga_penawaran" class="mt-2 p-2 w-full rounded-md border border-black shadow-sm" required>
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

                <div class="flex gap-4">
                    <div class="flex-1">
                        <p class="text-sm text-justify max-w-3xs">
                            Ketika anda klik pasang maka anda setuju untuk membeli item ini ketika anda memenangkan lelang.
                        </p>
                    </div>
                    <button type="button" id="closePopup" class="px-6 py-[1rem] text-sm font-medium text-center border border-[#255B22] rounded-lg hover:bg-gray-300 hover:border-black transition"
                        onclick="document.getElementById('bidForm').classList.add('hidden'); document.getElementById('bidForm').classList.remove('flex')">
                        Kembali
                    </button>
                    <button id="submit" type="submit" class="px-6 py-[1rem] text-sm font-medium text-white text-center bg-[#255B22] rounded-lg hover:bg-[#0F3714] transition"
                    onclick="">
                        Pasang
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
</div>

<script>
    const inputHargaPenawaran = document.getElementById('hargaPenawaran');

    // Format input saat user mengetik
    inputHargaPenawaran.addEventListener('input', function () {
        const cursorPosition = this.selectionStart; // Simpan posisi kursor
        const rawValue = this.value.replace(/\D/g, ''); // Hanya angka
        const formattedValue = new Intl.NumberFormat('id-ID').format(rawValue); // Format dengan pemisah ribuan
        this.value = formattedValue;

        // Kembalikan posisi kursor
        this.setSelectionRange(cursorPosition, cursorPosition);
    });

    // Kirim angka tanpa format titik
    inputHargaPenawaran.addEventListener('blur', function () {
        const rawValue = this.value.replace(/\D/g, ''); // Hapus format titik
        this.dataset.rawValue = rawValue; // Simpan nilai asli sebagai data-raw-value
    });

    // Tangkap nilai asli saat formulir dikirimkan
    document.forms[0].addEventListener('submit', function (event) {
        inputHargaPenawaran.value = inputHargaPenawaran.dataset.rawValue || ''; // Ubah kembali ke angka mentah
        console.log(inputHargaPenawaran.value); // Cetak variabel ke console
    });

</script>
