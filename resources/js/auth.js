import Swal from 'sweetalert2';

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("form");
    const errorMessage = document.getElementById("error-message");

    loginForm.addEventListener("submit", async function (e) {
        e.preventDefault(); // Cegah pengiriman form default

        const formData = new FormData(loginForm);
        const submitButton = loginForm.querySelector("button[type='submit']");
        submitButton.disabled = true; // Nonaktifkan tombol sementara

        try {
            const response = await fetch(loginForm.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            if (!response.ok) {
                const data = await response.json();
                if (data.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Coba Lagi',
                        text: data.message,
                        confirmButtonText: 'Coba Lagi',
                        width: '400px',
                        heightAuto: false,
                    })
                }
            } else {
                const data = await response.json();
                if (data.redirect) {
                    window.location.href = data.redirect; // Redirect ke halaman utama
                }
            }
        } catch (error) {
            console.error("Something went wrong:", error);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Tidak Terduga',
                text: 'Terjadi masalah pada jaringan. Silakan coba lagi.',
                confirmButtonText: 'OK',
                width: '400px',
                heightAuto: false,
            });
        } finally {
            submitButton.disabled = false; // Aktifkan kembali tombol
        }
    });
});
