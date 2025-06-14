// import './bootstrap';

// import toastr from 'toastr';
// window.toastr = toastr; // Membuat toastr tersedia di global scope
// import 'toastr/build/toastr.min.css'; // Import file CSS toastr

// import { showAlert } from "./sweetalert";
// window.showAlert = showAlert;

// // Import fungsi animasi dari file terpisah
// // import runGlobalAnimations from './anime.js';

// import { renderChart } from './chart';







// document.addEventListener('DOMContentLoaded', () => {
//     const charts = [
//         {
//             id: 'dailyChart',
//             type: 'bar',
//             label: 'Pendapatan Harian',
//             backgroundColor: 'rgba(54, 162, 235, 0.5)',
//             borderColor: 'rgba(54, 162, 235, 1)',
//             scales: {
//                 x: { type: 'time', time: { unit: 'day', tooltipFormat: 'd MMM yyyy' } },
//                 y: { beginAtZero: true },
//             },
//         },
//         {
//             id: 'weeklyChart',
//             type: 'bar',
//             label: 'Pendapatan Mingguan',
//             backgroundColor: 'rgba(54, 162, 235, 0.5)',
//             borderColor: 'rgba(54, 162, 235, 1)',
//         },
//         {
//             id: 'monthlyChart',
//             type: 'line',
//             label: 'Pendapatan Bulanan',
//             backgroundColor: 'rgba(75, 192, 192, 0.5)',
//             borderColor: 'rgba(75, 192, 192, 1)',
//         },
//         {
//             id: 'yearlyChart',
//             type: 'pie',
//             label: 'Pendapatan Tahunan',
//             backgroundColor: [
//                 'rgba(255, 99, 132, 0.5)',
//                 'rgba(54, 162, 235, 0.5)',
//                 'rgba(255, 206, 86, 0.5)',
//             ],
//             borderColor: [
//                 'rgba(255, 99, 132, 1)',
//                 'rgba(54, 162, 235, 1)',
//                 'rgba(255, 206, 86, 1)',
//             ],
//             borderWidth: 1,
//         },
//     ];

//     charts.forEach((chart) => {
//         const chartEl = document.getElementById(chart.id);
//         if (chartEl) {
//             // Ambil data dari atribut
//             const labels = JSON.parse(chartEl.dataset.labels);
//             const data = JSON.parse(chartEl.dataset.data);

//             // Ambil ukuran dari atribut
//             const width = chartEl.dataset.width || '600px';
//             const height = chartEl.dataset.height || '400px';

//             // Terapkan ukuran pada canvas
//             chartEl.style.width = width;
//             chartEl.style.height = height;

//             // Render chart
//             renderChart(chart.id, chart.type, labels, data, chart);
//         }
//     });

//     if (session('alert')){
//         showAlert(json(session('alert')));
//     }
// });


// ====== IMPORTS ======
import './bootstrap';
import { initToastr } from './toastr.js';
import { initSweetAlert } from './sweetalert.js';
import { initCharts } from './chart.js';
import { initOneSignal } from './onesignal.js';
import { initAnimations } from './animations.js'; 

// ====== FUNGSI UTAMA ======
/**
 * Fungsi 'main' ini akan menjadi satu-satunya titik masuk
 * yang dipanggil setelah halaman siap.
 */
function main() {
    initToastr();
    initSweetAlert();
    initOneSignal();
    initAnimations();
    initCharts();

    console.log('Semua skrip telah diinisialisasi secara terpusat.');
}

// ====== EVENT LISTENER TUNGGAL ======
// Hanya ada satu event listener DOMContentLoaded untuk seluruh aplikasi.
document.addEventListener('DOMContentLoaded', main);
