// ====== IMPORTS ======
import './bootstrap';
import { initToastr } from './toastr.js';
import { initSweetAlert } from './sweetalert.js';
import { initCharts } from './chart.js';
import { initOneSignal } from './onesignal.js';
import { initAnimations } from './animations.js';

// ====== FUNGSI UTAMA ======

function main() {
    initToastr();
    initSweetAlert();
    initCharts();
    initAnimations();
    initOneSignal();

    console.log('Semua skrip telah diinisialisasi secara terpusat.');
}

// ====== EVENT LISTENER TUNGGAL ======
document.addEventListener('DOMContentLoaded', main);

