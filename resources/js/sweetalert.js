import Swal from "sweetalert2";

export function showAlert(options) {
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
        console.log(options.confirmUrl);
        if (result.isConfirmed) {
            if (options.onConfirm) {
                options.onConfirm();
            } else if (options.confirmUrl) {
                window.location.href = options.confirmUrl;
            }
        }
    });
}
