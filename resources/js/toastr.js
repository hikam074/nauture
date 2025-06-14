import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function initToastr() {
    toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "8000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
    };
    window.toastr = toastr; // Buat global jika masih perlu dipanggil dari tempat lain

    // Baca data dari "Jembatan Data" di body
    const body = document.body;
    const toastrMessages = {
        success: body.dataset.toastrSuccess,
        error: body.dataset.toastrError,
        info: body.dataset.toastrInfo,
        warning: body.dataset.toastrWarning
    };

    // Tampilkan notifikasi berdasarkan data yang ada
    for (const type in toastrMessages) {
        if (toastrMessages[type]) {
            try {
                const data = JSON.parse(toastrMessages[type]);
                if (typeof data === 'object') {
                    toastr[type](data.message, data.title || `[${type.charAt(0).toUpperCase() + type.slice(1)}]`);
                } else {
                    toastr[type](data);
                }
            } catch (e) {
                console.error("Gagal mem-parsing data toastr:", toastrMessages[type]);
            }
        }
    }
}
