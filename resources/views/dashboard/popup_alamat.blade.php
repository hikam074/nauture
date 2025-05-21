<div id="popupAlamat"
    class="fixed top-0 bottom-0 left-0 right-0 mt-16 hidden items-center justify-center z-10"
>
    <form id="pengirimans" action="{{ route('transaksi.create') }}" method="POST">
        @csrf
    <!--BG-->
    <div class="fixed top-0 bottom-0 left-0 right-0 h-full w-full bg-black opacity-50 z-20"></div>
    <!--CONTENT-->
    <div id="alamatContent"
        class="w-[80%] flex flex-col gap-5 z-30 bg-white p-6 rounded-lg shadow-lg"
        >
        <h2 class="text-lg font-bold text-center">Pilih Lokasi dan Pengiriman</h2>
        <!--VALUE : PASANG_LELANG -->
        <input type="hidden" name="pasang_lelang_id" id="pasang_lelang_id">
        <!--VALUE : BERAT-->
        <input type="hidden" name="weight" id="weight">
        <!--VALUE : HARGA_PENGAJUAN-->
        <input type="hidden" name="harga_pengajuan" id="hargaBid">
        <!--VALUE : HARGA_TOTAL-->
        <input type="hidden" name="harga_total" id="harga_total">

        <!--SECTION 1 ALAMAT-->
        <div class="flex flex-col gap-2 border-1 border-canceled p-4 rounded-lg w-full">
            <!--SEARCH BAR CARI LOKASI-->
            <div class="-mt-8 mb-4">
                <h3 class="absolute bg-white px-2 text-canceledhov">Lokasi Tujuan</h3>
            </div>
            <div class="flex gap-5">
                <!-- DETAIL LOKASI -->
                <div class="flex-3 gap-20">
                    <label for="destinationSearch" class="text-sm">
                        Provinsi, Kabupaten, atau Kecamatan anda :
                    </label>
                    <div class="flex gap-2">
                        <!-- INPUT CARI ALAMAT -->
                        <input type="text" id="destinationSearch" class="w-full p-2 border rounded" placeholder="Contoh : malang">
                        <button id="searchLocation" type="button"
                            class="bg-sekunderDark text-white px-4 py-2 rounded cursor-pointer
                            hover:bg-primer"
                            >
                            Cari
                        </button>
                    </div>
                </div>
                <!-- DROPDOWN LOKASI -->
                <div class="flex-5">
                    <label for="destinationList" class="text-sm">
                        Hasil pencarian, silahkan pilih alamat anda :
                    </label>
                    <!-- DROPDOWN LOKASI DIISI PAKAI JS -->
                    <select id="destinationList" name="destination" class="w-full p-2 border rounded"></select>
                    <input type="hidden" id="destinationJson" name="destinationJson">
                </div>
            </div>
            <!-- INPUT ALAMAT DETAIL -->
            <div class="flex gap-5">
                <div class="w-full">
                    <label for="addressDetail" class="text-sm">Alamat Detail :</label>
                    <input type="text" name="detail_alamat" id="addressDetail" class="w-full p-2 border rounded" placeholder="Contoh : Jl. Tidar 1 No.3 Kranjingan Sumbersari Jember Jawa Timur">
                </div>
            </div>
        </div>

        <!-- SECTION 2 METODE & KONFIRMASI -->
        <div class="flex gap-2 w-full">
            <!-- SECTION 2.1 METODE PENGIRIMAN -->
            <div class="flex flex-col flex-1 gap-4 border-1 border-canceled p-4 rounded-lg max-h-51 overflow-y-scroll">
                <div class="-mt-8 mb-4">
                    <h3 class="absolute bg-white px-2 text-canceledhov">Metode</h3>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <div class="w-full">
                        <!-- DROPDOWN PILIH METODE PENGIRIMAN -->
                        <label>Metode Pengiriman:</label>
                        <div class="flex flex-row gap-5 text-sm w-full">
                            <label><input type="radio" id="shippingMethod" name="shippingMethod" value="pickup" class="mr-1"> Ambil Sendiri</label>
                            <label><input type="radio" id="shippingMethod" name="shippingMethod" value="expedition" class="mr-1"> Menggunakan Ekspedisi</label>
                        </div>
                    </div>
                    <!-- BTN DAPATKAN TARIF  -->
                    <button id="calculateCost" type="button"
                        class="bg-sekunderDark text-white px-4 py-2 rounded cursor-pointer
                            hover:bg-primer"
                        >
                        Dapatkan Tarif
                    </button>
                </div>
                <!-- DROPDOWN TARIF -->
                <div class="w-full">
                    <label for="costList">Pilihan Tarif <span class="font-thin">(Sesuaikan dengan berat paket: <span id="beratPaket"></span> Kg)</span> :</label>
                    <div id="shipping-options-container" class="grid grid-cols-1 gap-4">
                        <!-- isi card dengan js -->
                        <span class="text-canceled italic text-sm">silahkan pilih metode terlebih dahulu</span>
                    </div>
                    <input type="hidden" id="selected-shipping-option" name="shipping_method" value="">
                    <input type="hidden" id="ongkir" name="ongkir" value="">
                </div>
            </div>

            <!-- SECTION 2.2 KONFIRMASI -->
            <div class="flex flex-1 flex-col gap-4 border-1 border-canceled p-4 rounded-lg max-h-51 overflow-y-scroll">
                <div class="-mt-8 mb-4">
                    <h3 class="absolute bg-white px-2 text-canceledhov">Konfirmasi</h3>
                </div class="">
                    <div class="grid grid-cols-[140px_1fr] gap-y-2 gap-x-5 text-sm">
                        <!-- NAMA -->
                        <div class="font-medium">Kode Lelang</div>
                        <div id="kodeLelang" class="font-semibold"></div>
                        <!-- NAMA -->
                        <div class="font-medium">Nama Penerima</div>
                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                        <!-- ALAMAT -->
                        <div class="font-medium">Alamat</div>
                        <div>
                            <span id="konfirAlamatDr" class="font-light"></span> |
                            <span id="konfirAlamatIsi" class="font-thin"></span>
                        </div>
                        <!-- PENGIRIMAN -->
                        <div class="font-medium">Pengiriman</div>
                        <div class="font-semibold">
                            <span id="konfirPengiriman"></span>
                        </div>
                        <!-- BIAYA -->
                        <div class="font-medium">Biaya Lelang</div>
                        <div>Rp. <span id="konfirmasiBiayaLelang"></span></div>
                        <!-- BIAYA -->
                        <div class="font-medium">Biaya Pengiriman</div>
                        <div><span id="konfirOngkir"></span></div>
                        <!-- BIAYA -->
                        <div class="font-medium">Total biaya</div>
                        <div class="font-semibold"><span id="konfirBiaya"></span></div>

                    </div>
                </div>
            </div>

        <!-- SECTION 3 BTN BTN -->
        <div class="flex gap-5">
            <button id="closePopup" type="button" class="bg-canceled text-white px-4 py-2 rounded w-full cursor-pointer
                hover:bg-canceledhov"
                >
                Batal
            </button>
            <button id="submitBtn" type="submit" class="bg-hapus text-white px-4 py-2 rounded w-full cursor-pointer
                hover:bg-hapushov"
                >
                Buat Pesanan
            </button>
        </div>

    </div>
    </form>
</div>


<script>
    // PEMILIHAN ALAMAT
    const searchButton = document.getElementById('searchLocation');
    searchButton.removeEventListener('click', searchHandler);
    searchButton.addEventListener('click', searchHandler);
    const body = document.body;

    let destinations = [];

    function searchHandler() {
        const query = document.getElementById('destinationSearch').value;

        if (query.length >= 3) {
            // matikan tombol
            body.style.cursor = 'wait';
            searchButton.disabled = true;
            toastr.info('Mencari lokasi...', 'Lokasi');

            // cari data
            fetch(`/api/cari-lokasi?search=${query}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log("API Response:", data); // Tambahkan log ini
                destinations = data || []; // Gunakan fallback jika `data.results` undefined
                if (!destinations.length) {
                    throw new Error("Destinations kosong");
                }
                const select = document.getElementById('destinationList');
                select.innerHTML = destinations.map(loc =>
                    `<option value="${loc.id}">${loc.province_name}, ${loc.city_name}, ${loc.district_name}, ${loc.subdistrict_name}</option>`
                ).join('');
                if (select.options.length > 0) {
                    select.selectedIndex = 0;   // Pilih otomatis opsi pertama
                    updateConfirmation();
                }
                toastr.success('Silahkan pilih alamat anda', 'Lokasi ditemukan')
            })
            .catch(err => {
                console.error('Error fetching destinations:', err);
                toastr.error('Terjadi kesalahan saat mencari lokasi.', 'Error');
            })
            .finally(() => {
                // kembalikan kursor ke normal dan aktifkan tombol
                body.style.cursor = 'default';
                searchButton.disabled = false;
            });
        } else {
            toastr.warning('Masukkan minimal 3 karakter untuk mencari lokasi.', 'Peringatan');
        }

    }


    // KALKULASI ONGKIR
    const calculateButton = document.getElementById('calculateCost');
    calculateButton.removeEventListener('click', calculateCostHandler);
    calculateButton.addEventListener('click', calculateCostHandler);

    function calculateCostHandler() {
        const destination = document.getElementById('destinationList').value;
        const shippingMethod = document.querySelector('input[name="shippingMethod"]:checked')?.value;
        const berat = document.getElementById('weight').value;


        if (!destination || !shippingMethod) {
            toastr.warning('Harap pilih lokasi dan metode pengiriman', 'Peringatan');
            return;
        }

        const container = document.getElementById('shipping-options-container');
        container.innerHTML = ''; // Bersihkan opsi sebelumnya

        // matikan tombol
        body.style.cursor = 'wait';
        calculateButton.disabled = true;

        // cari data
        if (shippingMethod === 'expedition') {
            toastr.info('Menghitung ongkos kirim...', 'Pengiriman');
            fetch('/api/cek-ongkir', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    destination,
                    weight: berat,
                    courier: 'jne:pos:tiki:sicepat:jnt:sap:ncs:wahana:lion:rex' // Pilihan kurir
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to calculate shipping cost');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(option => {
                    const card = document.createElement('div');
                    card.className = "border p-4 rounded shadow hover:bg-gray-100 cursor-pointer shipping-card text-sm";
                    card.dataset.service = option.service;
                    card.innerHTML = `
                        <h3 class="font-bold">${option.name} - ${option.service}</h3>
                        <p>${option.description}</p>
                        <p><strong>Biaya:</strong> Rp ${option.cost.toLocaleString('id-ID')}</p>
                        <p><strong>Estimasi:</strong> ${option.etd}</p>
                    `;
                    card.addEventListener('click', () => {
                        document.querySelectorAll('.shipping-card').forEach(c => c.classList.remove('bg-blue-100'));
                        card.classList.add('bg-blue-100'); // Highlight kartu yang dipilih
                        document.getElementById('selected-shipping-option').value = option.service; // Simpan nilai ke hidden input
                        updateConfirmation(option.cost, { name: option.name, service: option.service });
                    });
                    container.appendChild(card);
                });
                updateConfirmation();
                toastr.success('Silahkan pilih layanan yang sesuai dengan anda', 'Tarif dihitung')
            })
            .catch(err => {
                console.error('Error hitung ongkir :', err);
                toastr.error('Terjadi kesalahan saat menghitung ongkir.', 'Error');
            })
        }
        else
        {
            const card = document.createElement('div');
            card.className = "border p-4 rounded shadow hover:bg-gray-100 cursor-pointer shipping-card bg-blue-100";
            card.innerHTML = `
                <h3 class="font-bold">Ambil Sendiri</h3>
                <p><strong>Biaya:</strong> Rp 0</p>
            `;
            card.addEventListener('click', () => {
                document.querySelectorAll('.shipping-card').forEach(c => c.classList.remove('bg-blue-100'));
                card.classList.add('bg-blue-100'); // Highlight kartu yang dipilih
                document.getElementById('selected-shipping-option').value = 'takeaway'; // Simpan nilai ke hidden input
                updateConfirmation(0, { name: 'Ambil Sendiri', service: 'takeaway' });
            });
            container.appendChild(card);
            document.getElementById('selected-shipping-option').value = 'takeaway';
            updateConfirmation(0, { name: 'Ambil Sendiri', service: 'takeaway' }); // Update konfirmasi
            toastr.success('Ambil sendiri gratis, silahkan pilih opsi yang tersedia', 'Tarif ditemukan')
            document.getElementById('ongkir').value = 0;
        }
        // kembalikan kursor ke normal dan aktifkan tombol
        body.style.cursor = 'default';
        calculateButton.disabled = false;
    }


    // UPDATE TEKS KONFIRMASI
    function updateConfirmation(cost = null, shippingInfo = null) {
        const selectedLocation = document.getElementById('destinationList');
        const addressDetail = document.getElementById('addressDetail').value;
        const selectedCost = cost !== null ? cost : '-';
        const hargaBid = parseInt(document.getElementById('hargaBid').value);

        const konfirAlamatDr = document.getElementById('konfirAlamatDr');
        const konfirAlamatIsi = document.getElementById('konfirAlamatIsi');
        const konfirPengiriman = document.getElementById('konfirPengiriman');
        const konfirBiaya = document.getElementById('konfirBiaya');
        const konfirOngkir = document.getElementById('konfirOngkir');

        // Validasi apakah lokasi sudah dipilih
        if (!selectedLocation || selectedLocation.selectedIndex < 0) {
            konfirAlamatDr.textContent = 'Belum dipilih';
            konfirAlamatIsi.textContent = addressDetail || 'Belum diisi';
            konfirPengiriman.textContent = 'Belum dipilih';
            konfirBiaya.textContent = 'Rp. -';
            konfirOngkir.textContent = 'Rp. -';
            return; // Berhenti jika lokasi belum dipilih
        }
        // Update alamat
            const locationText = selectedLocation.options[selectedLocation.selectedIndex].text;
            konfirAlamatDr.textContent = locationText || 'Belum dipilih';
            konfirAlamatIsi.textContent = addressDetail || 'Belum diisi';
            const selectedId = document.getElementById('destinationList').value;
            const selectedData = destinations && destinations.length > 0
                ? destinations.find(d => d.id == selectedId)
                : null;
            if (selectedData) {
                document.getElementById('destinationJson').value = JSON.stringify(selectedData);
            } else {
                document.getElementById('destinationJson').value = '';
            }

        // Update pengiriman
        if (shippingInfo) {
            konfirPengiriman.textContent = `${shippingInfo.name} - ${shippingInfo.service}`;
            // Update biaya
            const hargaTotal = selectedCost + hargaBid;
            document.getElementById('ongkir').value = selectedCost;
            konfirBiaya.textContent = `Rp. ${hargaTotal.toLocaleString('id-ID')}`;
            konfirOngkir.textContent = `Rp. ${selectedCost.toLocaleString('id-ID')}`;
            document.getElementById('harga_total').value = hargaTotal;
        } else {
            const selectedShippingOption = document.getElementById('selected-shipping-option').value;
            if (selectedShippingOption === 'takeaway') {
                konfirPengiriman.textContent = 'Ambil Sendiri';
                konfirOngkir.textContent = 'Rp. 0';
                konfirBiaya.textContent = `Rp. ${hargaBid.toLocaleString('id-ID')}`;
            } else {
                konfirPengiriman.textContent = 'Belum dipilih';
                konfirOngkir.textContent = 'Rp. -';
                konfirBiaya.textContent = 'Rp. -';
            }
        }
    }

    // Tambahkan listener ke elemen dropdown dan radio button
    document.getElementById('destinationList').addEventListener('change', updateConfirmation);
    document.getElementById('addressDetail').addEventListener('input', updateConfirmation);
    document.querySelectorAll('input[name="shippingMethod"]').forEach(method => {
        method.addEventListener('change', () => updateConfirmation());
    });
    updateConfirmation();

</script>
