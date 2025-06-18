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
            type: 'line',
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
                'rgba(52, 152, 219, 0.5)',
                'rgba(129, 236, 236, 0.5)',
                'rgba(99, 110, 114, 0.5)',
            ],
            borderColor: [
                'rgba(52, 152, 219, 1)',
                'rgba(99, 110, 114, 1)',
                'rgba(99, 110, 114, 1)',
            ],
            borderWidth: 1,
        },

        {
            id: 'dailyProfitChart',
            type: 'bar',
            label: 'Profit Bersih Harian',
            backgroundColor: 'rgba(46, 204, 113, 0.5)',
            borderColor: 'rgba(46, 204, 113, 1)',
            scales: {
                x: { type: 'time', time: { unit: 'day', tooltipFormat: 'd MMM yyyy' } },
                y: { beginAtZero: true },
            },
        },
        {
            id: 'weeklyProfitChart',
            type: 'line',
            label: 'Profit Bersih Mingguan',
            backgroundColor: 'rgba(46, 204, 113, 0.5)',
            borderColor: 'rgba(46, 204, 113, 1)',
        },
        {
            id: 'monthlyProfitChart',
            type: 'line',
            label: 'Profit Bersih Bulanan',
            backgroundColor: 'rgba(46, 204, 113, 0.5)',
            borderColor: 'rgba(46, 204, 113, 1)',
        },
        {
            id: 'yearlyProfitChart',
            type: 'pie',
            label: 'Profit Bersih Tahunan',
            backgroundColor: [
                'rgba(160, 102, 69, 0.5)',
                'rgba(241, 196, 15, 0.5)',
                'rgba(46, 204, 113, 0.5)',
            ],
            borderColor: [
                'rgba(160, 102, 69, 1)',
                'rgba(241, 196, 15, 1)',
                'rgba(46, 204, 113, 1)',
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
