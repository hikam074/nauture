export function initOneSignal() {
    if (typeof window.OneSignalDeferred !== 'undefined') {
        window.OneSignalDeferred.push(function(OneSignal) {
            OneSignal.init({
                appId: "85efa3ec-3377-47ee-89ab-4811a87cd73e", // Ganti dengan App ID Anda
                allowLocalhostAsSecureOrigin: true,
            });
        });
        console.log('OneSignal initialized.');
    }
}
