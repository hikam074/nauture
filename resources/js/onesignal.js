export function initOneSignal() {
    // Penjaga: Jika OneSignal sudah diinisialisasi, jangan lakukan apa-apa.
    if (window.OneSignal && window.OneSignal.isInitialized) {
        return;
    }

    if (typeof window.OneSignalDeferred !== 'undefined') {
        window.OneSignalDeferred.push(function(OneSignal) {
            // Tandai bahwa OneSignal sudah diinisialisasi
            // agar tidak dijalankan lagi oleh hot-reload.
            OneSignal.isInitialized = true;

            OneSignal.init({
                appId: "85efa3ec-3377-47ee-89ab-4811a87cd73e",
                allowLocalhostAsSecureOrigin: true,
            });
        });
    }
}
