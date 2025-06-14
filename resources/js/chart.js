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

export function initCharts() {
    const chartsConfig = [
        {
            id: 'dailyChart',
            type: 'bar',
            label: 'Pendapatan Harian',
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            scales: {
                x: { type: 'time', time: { unit: 'day', tooltipFormat: 'd MMM yyyy' } },
                y: { beginAtZero: true },
            },
        },
        {
            id: 'weeklyChart',
            type: 'bar',
            label: 'Pendapatan Mingguan',
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
        },
        {
            id: 'monthlyChart',
            type: 'line',
            label: 'Pendapatan Bulanan',
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
        },
        {
            id: 'yearlyChart',
            type: 'pie',
            label: 'Pendapatan Tahunan',
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
            ],
            borderWidth: 1,
        },
    ];

    chartsConfig.forEach((config) => {
        const chartEl = document.getElementById(config.id);
        if (chartEl && chartEl.dataset.labels && chartEl.dataset.data) {
            const labels = JSON.parse(chartEl.dataset.labels);
            const data = JSON.parse(chartEl.dataset.data);
            renderChart(config.id, config.type, labels, data, config);
        }
    });
}
