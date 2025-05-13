<script>
    document.addEventListener("DOMContentLoaded", function() {
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

        // Fungsi untuk menampilkan Toastr dengan dukungan judul
        function showToastr(type, message, title = 'Pemberitahuan') {
            if (typeof toastr[type] === 'function') {
                toastr[type](message, title);
            }
        }

        @if (session('success'))
            const success = @json(session('success'));
            if (typeof success === 'object') {
                showToastr('success', success.message, success.title || '[Sukses]');
            } else {
                showToastr('success', success, '[Sukses]');
            }
        @endif
        @if (session('error'))
            const error = @json(session('error'));
            if (typeof error === 'object') {
                showToastr('error', error.message, error.title || '[Kesalahan]');
            } else {
                showToastr('error', error, '[Kesalahan]');
            }
        @endif
        @if (session('info'))
            const info = @json(session('info'));
            if (typeof info === 'object') {
                showToastr('info', info.message, info.title || '[Info]');
            } else {
                showToastr('info', info, '[Info]');
            }
        @endif
        @if (session('warning'))
            const warning = @json(session('warning'));
            if (typeof warning === 'object') {
                showToastr('warning', warning.message, warning.title || '[Peringatan]');
            } else {
                showToastr('warning', warning, '[Peringatan]');
            }
        @endif

        // Menampilkan pesan error validasi
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showToastr('error', @json($error), 'Validasi Gagal');
            @endforeach
        @endif
    });
</script>
