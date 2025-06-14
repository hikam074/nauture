import Swal from 'sweetalert2';

// Fungsi helper utama dari file sweetalert.js Anda
function showAlert(options) {
    Swal.fire({
        title: options.title || "Apakah Anda yakin?",
        text: options.text || "Tindakan ini tidak dapat dibatalkan!",
        icon: options.icon || "warning",
        showCancelButton: true,
        confirmButtonColor: options.confirmButtonColor || "#3085d6",
        cancelButtonColor: options.cancelButtonColor || "#d33",
        confirmButtonText: options.confirmButtonText || "Ya",
        cancelButtonText: options.cancelButtonText || "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            // **PERBAIKAN LOGIKA ADA DI SINI**

            // Prioritas 1: Jika onConfirm adalah FUNGSI, jalankan.
            if (typeof options.onConfirm === 'function') {
                options.onConfirm();
            }
            // Prioritas 2: Jika onConfirm adalah STRING (URL), redirect.
            else if (typeof options.onConfirm === 'string') {
                window.location.href = options.onConfirm;
            }
            // Prioritas 3: Fallback ke properti confirmUrl (untuk kompatibilitas)
            else if (options.confirmUrl) {
                window.location.href = options.confirmUrl;
            }
        }
    });
}

// Fungsi inisialisasi untuk menangani pesan dari sesi
export function initSweetAlert() {
    window.showAlert = showAlert;

    const alertData = document.body.dataset.sweetalert;
    if (alertData) {
        try {
            const options = JSON.parse(alertData);
            showAlert(options);
        } catch (e) {
            console.error("Gagal mem-parsing data sweetalert:", alertData);
        }
    }
}
