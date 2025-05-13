<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('alert'))
            showAlert(@json(session('alert')));
        @endif
    });
</script>
