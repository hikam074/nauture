export function initOneSignal() {
    // // Penjaga: Jika OneSignal sudah diinisialisasi, jangan lakukan apa-apa.
    // if (window.OneSignal && window.OneSignal.isInitialized) {
    //     console.log('onesignal sudah ada');
    //     return;
    // }

    // if (typeof window.OneSignalDeferred !== 'undefined') {
    //     window.OneSignalDeferred.push(function(OneSignal) {
    //         // Tandai bahwa OneSignal sudah diinisialisasi
    //         // agar tidak dijalankan lagi oleh hot-reload.
    //         OneSignal.isInitialized = true;

    //         OneSignal.init({
    //             appId: "85efa3ec-3377-47ee-89ab-4811a87cd73e",
    //             allowLocalhostAsSecureOrigin: true,
    //         });
    //     });
    //     console.log('onesignal dihidupkan');
    // }
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(function(OneSignal) {
        OneSignal.init({
        appId: "85efa3ec-3377-47ee-89ab-4811a87cd73e",
        allowLocalhostAsSecureOrigin: true,
        });
    });
}
