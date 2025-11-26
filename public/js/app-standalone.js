/**
 * Personal Finance Manager - Standalone JavaScript
 * All-in-one file for direct browser use (no build step required)
 */

// ========== Utility Functions ==========

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

function showToast(message, type = 'success') {
    if (typeof Swal === 'undefined') {
        alert(message);
        return;
    }
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

function confirmAction(title, text, confirmText = 'Yes, proceed!') {
    if (typeof Swal === 'undefined') {
        return Promise.resolve({ isConfirmed: confirm(text) });
    }
    
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: confirmText
    });
}

function formatCurrencyElements() {
    document.querySelectorAll('[data-currency]').forEach(element => {
        const amount = parseFloat(element.getAttribute('data-currency'));
        if (!isNaN(amount)) {
            element.textContent = formatCurrency(amount);
        }
    });
}

// ========== Chart Initialization ==========

const charts = {};

function initCharts() {
    if (typeof Chart === 'undefined') return;

    Chart.defaults.font.family = 'Poppins';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6B7280';

    const chartCanvases = document.querySelectorAll('canvas[data-chart]');
    chartCanvases.forEach(canvas => {
        const chartType = canvas.getAttribute('data-chart');
        const chartId = canvas.id;

        if (chartType && chartId) {
            initializeChart(chartId, chartType);
        }
    });
}

function initializeChart(canvasId, type) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

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
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000) + 'M';
                            }
                        }
                    }
                }
            }
        }
    };

    if (chartConfigs[type]) {
        charts[canvasId] = new Chart(ctx, chartConfigs[type]);
    }
}

// ========== Modal Management ==========

function setupModals() {
    document.querySelectorAll('[data-modal-open]').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal-open');
            openModal(modalId);
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            if (modal) closeModal(modal.id);
        });
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) closeModal(activeModal.id);
        }
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// ========== Form Handling ==========

function setupAjaxForms() {
    const ajaxForms = document.querySelectorAll('form[data-ajax]');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            const method = this.method;

            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Operation successful!', 'success');
                    if (data.redirect) {
                        setTimeout(() => window.location.href = data.redirect, 1500);
                    }
                } else {
                    showToast(data.message || 'Operation failed!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        });
    });
}

// ========== Data Tables ==========

function setupDataTables() {
    const searchInputs = document.querySelectorAll('[data-table-search]');
    
    searchInputs.forEach(input => {
        const tableId = input.getAttribute('data-table-search');
        const table = document.getElementById(tableId);
        
        if (table) {
            input.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
}

// ========== Animations ==========

function setupScrollAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    animatedElements.forEach(el => observer.observe(el));
}

function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// ========== Initialize Everything ==========

document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    setupAjaxForms();
    setupModals();
    setupDataTables();
    formatCurrencyElements();
    setupScrollAnimations();
    setupSmoothScroll();

    console.log('✅ Finance Manager App initialized successfully!');
});

// ========== Export Functions for Global Use ==========
window.financeApp = {
    formatCurrency,
    showToast,
    confirmAction,
    openModal,
    closeModal,
    initializeChart
};
