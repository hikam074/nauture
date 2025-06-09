import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

export function renderChart(chartId, type, labels, data, options = {}) {
    const ctx = document.getElementById(chartId)?.getContext('2d');
    if (!ctx) return;

    const canvas = ctx.canvas;
    // Ambil dimensi dari atribut data
    const width = canvas.dataset.width || canvas.style.width || '400px';
    const height = canvas.dataset.height || canvas.style.height || '400px';

    // Set ukuran canvas
    canvas.style.width = width;
    canvas.style.height = height;

    new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [
                {
                    label: options.label || 'Dataset',
                    data: data,
                    backgroundColor: options.backgroundColor || 'rgba(54, 162, 235, 0.5)',
                    borderColor: options.borderColor || 'rgba(54, 162, 235, 1)',
                    borderWidth: options.borderWidth || 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Agar ukuran menyesuaikan kustom
            scales: {
                x: options.scales?.x || { type: 'category' },
                y: options.scales?.y || { beginAtZero: true },
            },
            plugins: {
                legend: {
                    display: options.legend !== false,
                    position: options.legendPosition || 'top',
                },
                tooltip: {
                    enabled: options.tooltips !== false,
                },
            },
        },
    });
}
