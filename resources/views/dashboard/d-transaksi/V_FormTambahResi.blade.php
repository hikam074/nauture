<div id="popup-resi" class="hidden fixed top-0 left-0 w-full h-full items-center justify-center">
    <div class=" fixed top-0 left-0 w-full h-full items-center justify-center bg-black opacity-30 z-0"></div>
    <div class="bg-white rounded-lg p-6 w-1/3 z-1">
        <h2 class="text-lg font-bold mb-4">{{ isset($transaksi->no_resi) ? 'Ubah Nomor Resi' : 'Masukkan Nomor Resi' }} </h2>
        <form id="popup-resi-form" action="" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="transaksi_id" id="popup-transaksi-id">
            <div class="mb-4">
                <label for="no_resi" class="block mb-2">Nomor Resi</label>
                <input type="text" id="popup-nomor-resi" name="no_resi" value="{{ isset($transaksi->no_resi) ? $transaksi->no_resi : ' ' }}" required
                    class="border px-2 py-1 w-full rounded-lg"
                >
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="text-sm px-4 py-2 rounded-lg bg-gray-300" onclick="closePopup()">Batal</button>
                <button type="submit" class="text-sm px-4 py-2 rounded-lg bg-primer text-white hover:bg-opacity-90">Kirim</button>
            </div>
        </form>
    </div>
</div>
