// import Swal from "sweetalert2";

// export function showAlert(options) {
//     Swal.fire({
//         title: options.title || "Apakah Anda yakin?",
//         text: options.text || "Tindakan ini tidak dapat dibatalkan!",
//         icon: options.icon || "warning",
//         showCancelButton: true,
//         confirmButtonColor: options.confirmButtonColor || "#3085d6",
//         cancelButtonColor: options.cancelButtonColor || "#d33",
//         confirmButtonText: options.confirmButtonText || "Ya",
//         cancelButtonText: options.cancelButtonText || "Batal",
//     }).then((result) => {
//         console.log(options.confirmUrl);
//         if (result.isConfirmed) {
//             if (options.onConfirm) {
//                 options.onConfirm();
//             } else if (options.confirmUrl) {
//                 window.location.href = options.confirmUrl;
//             }
//         }
//     });
// }

import Swal from 'sweetalert2';

// Fungsi helper utama
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
        if (result.isConfirmed && typeof options.onConfirm === 'function') {
            options.onConfirm();
        }
    });
}

// Fungsi inisialisasi untuk menangani pesan dari sesi
export function initSweetAlert() {
    // Buat fungsi showAlert bisa diakses secara global
    window.showAlert = showAlert;

    // Baca data dari "Jembatan Data" di body
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
