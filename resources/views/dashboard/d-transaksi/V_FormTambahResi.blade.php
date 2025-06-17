<div id="popup-resi" class="hidden fixed top-0 left-0 right-0 bottom-0 w-full h-full items-center justify-center z-100">
    <div class="popup-bg fixed top-0 left-0 right-0 bottom-0 w-full h-full bg-black opacity-30 z-101"></div>
    <div class="popup-form bg-white rounded-lg p-6 w-1/3 z-102">

        <h2 id="popup-title" class="text-lg font-bold">Masukkan Kode Resi</h2>

        <p id="popup-order-id-teks" class="mb-4">Kode Transaksi : </p>

        <form id="popup-resi-form" action="" method="POST">

            @csrf
            @method('PATCH')

            <input type="hidden" name="transaksi_id" id="popup-transaksi-id">

            <div class="mb-4">
                <label for="no_resi" class="block mb-2">Nomor Resi</label>

                <input type="text" id="popup-nomor-resi" name="no_resi" value="" required
                    class="border px-2 py-1 w-full rounded-lg">

            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="text-sm px-4 py-2 rounded-lg bg-gray-300" onclick="closePopupResi()">Batal</button>
                <button type="submit" class="text-sm px-4 py-2 rounded-lg bg-primer text-white hover:bg-opacity-90">Kirim</button>
            </div>
        </form>
    </div>
</div>
