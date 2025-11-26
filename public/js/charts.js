/**
 * Chart Initialization and Management
 * Handles all Chart.js functionality
 */

let charts = {};

/**
 * Initialize all charts on the page
 */
export function initCharts() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') return;

    // Set global chart defaults
    Chart.defaults.font.family = 'Poppins';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6B7280';

    // Initialize each chart canvas found on the page
    const chartCanvases = document.querySelectorAll('canvas[data-chart]');
    chartCanvases.forEach(canvas => {
        const chartType = canvas.getAttribute('data-chart');
        const chartId = canvas.id;

        if (chartType && chartId) {
            initializeChart(chartId, chartType);
        }
    });
}

/**
 * Initialize specific chart by type
 */
export function initializeChart(canvasId, type) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Example chart configurations
    const chartConfigs = {
        doughnut: {
            type: 'doughnut',
            data: {
                labels: ['Food & Dining', 'Transportation', 'Shopping', 'Entertainment', 'Bills', 'Others'],
                datasets: [{
                    data: [2800000, 1200000, 1500000, 800000, 1650000, 500000],
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#6B7280'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        },
        line: {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'],
                datasets: [
                    {
                        label: 'Income',
                        data: [12000000, 13500000, 14000000, 15000000, 14500000, 16000000, 15500000, 17000000, 16500000, 15000000, 15750000],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    },
                    {
                        label: 'Expense',
                        data: [8000000, 7500000, 9000000, 8800000, 7200000, 9500000, 8900000, 9200000, 8500000, 7800000, 8450000],
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#10B981',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value / 1000000 + 'M';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        }
    };

    // Destroy existing chart if present
    if (charts[canvasId]) {
        charts[canvasId].destroy();
    }

    // Create new chart
    const config = chartConfigs[type];
    if (config) {
        charts[canvasId] = new Chart(ctx, config);
    }
}

export { charts };
