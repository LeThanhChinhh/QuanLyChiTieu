/* ===================================
   Dashboard Charts - REAL DATA
   =================================== */

document.addEventListener('DOMContentLoaded', function() {
    initDashboardCharts();
});

function initDashboardCharts() {
    // Kiểm tra xem có dữ liệu từ Server gửi xuống không
    if (!window.dashboardData) {
        console.warn('No dashboard data found.');
        return;
    }

    initOverviewChart();
    initTrendChart();
}

// 1. Biểu đồ Tròn (Tổng quan Thu/Chi/Dư)
function initOverviewChart() {
    const ctx = document.getElementById('overviewChart');
    if (!ctx) return;

    const data = window.dashboardData;
    const totalIncome = data.totalIncome || 0;
    const totalExpense = data.totalExpense || 0;
    
    // Tính số dư (Savings) để vẽ biểu đồ (Không được âm)
    const savings = Math.max(0, totalIncome - totalExpense);

    // Nếu chưa có dữ liệu gì
    if (totalIncome === 0 && totalExpense === 0) {
        // Hiển thị thông báo chưa có dữ liệu
        ctx.parentElement.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; color: #9CA3AF;">
                <i class="ri-pie-chart-2-line" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>Chưa có dữ liệu để hiển thị</p>
            </div>`;
        return;
    }

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Thu nhập', 'Chi tiêu', 'Số dư thực tế'],
            datasets: [{
                data: [totalIncome, totalExpense, savings],
                backgroundColor: [
                    '#10B981', // Xanh lá (Thu)
                    '#EF4444', // Đỏ (Chi)
                    '#3B82F6'  // Xanh dương (Dư)
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { family: "'Be Vietnam Pro', sans-serif" }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed;
                            return ' ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                        }
                    }
                }
            }
        }
    });
}

// 2. Biểu đồ Đường (Xu hướng 6 tháng)
function initTrendChart() {
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;

    const data = window.dashboardData;
    
    // Nếu cả 6 tháng đều không có dữ liệu
    const hasData = data.incomeData.some(x => x > 0) || data.expenseData.some(x => x > 0);
    
    if (!hasData) {
        ctx.parentElement.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; color: #9CA3AF;">
                <i class="ri-line-chart-line" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>Chưa có dữ liệu 6 tháng gần nhất</p>
            </div>`;
        return;
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels, // Nhãn tháng (vd: Jun, Jul...)
            datasets: [
                {
                    label: 'Thu nhập',
                    data: data.incomeData,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4, // Đường cong mềm mại
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Chi tiêu',
                    data: data.expenseData,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { usePointStyle: true }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#E5E7EB' },
                    ticks: {
                        callback: function(value) {
                            // Rút gọn số tiền (1.000.000 -> 1M)
                            if (value >= 1000000) return (value / 1000000) + 'M';
                            if (value >= 1000) return (value / 1000) + 'k';
                            return value;
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}