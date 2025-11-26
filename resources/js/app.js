/* ===================================
   Personal Finance Manager - JavaScript
   Modern ES6+ with Chart.js Integration
   =================================== */

// Global app object
window.FinanceApp = {
    // Configuration
    config: {
        charts: {},
        modals: {},
        currency: 'VND'
    },

    // Initialize the application
    init() {
        this.bindEvents();
        this.initializeCharts();
        this.initializeModals();
        this.initializeForms();
        this.initializeTables();
        this.initializeAnimations();
        console.log('Personal Finance Manager initialized successfully');
    },

    // Event binding
    bindEvents() {
        // Sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                // Desktop Toggle
                if (window.innerWidth > 768) {
                    sidebar.classList.toggle('collapsed');
                    if (mainContent) mainContent.classList.toggle('expanded');
                } 
                // Mobile Toggle
                else {
                    sidebar.classList.toggle('active');
                    const overlay = document.getElementById('sidebarOverlay');
                    if (overlay) overlay.classList.toggle('active');
                }
            });
        }

        // User menu toggle
        const userMenuTrigger = document.querySelector('.user-menu-trigger');
        const userMenu = document.querySelector('.user-menu');

        if (userMenuTrigger && userMenu) {
            userMenuTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                userMenu.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!userMenu.contains(e.target)) {
                    userMenu.classList.remove('active');
                }
            });
        }

        // Search functionality
        const searchInput = document.querySelector('.search-box input');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.handleSearch(e.target.value);
            }, 300));
        }

        // ĐÃ XÓA: this.highlightActiveNavigation(); -> Nguyên nhân gây mất active
    },

    // Initialize Chart.js charts
    // initializeCharts() {
    //     // Dashboard Overview Chart
    //     const overviewCtx = document.getElementById('overviewChart');
    //     if (overviewCtx) {
    //         this.config.charts.overview = new Chart(overviewCtx, {
    //             type: 'doughnut',
    //             data: {
    //                 labels: ['Income', 'Expenses', 'Savings'],
    //                 datasets: [{
    //                     data: [60, 30, 10],
    //                     backgroundColor: [
    //                         '#10B981',
    //                         '#EF4444',
    //                         '#3B82F6'
    //                     ],
    //                     borderWidth: 0,
    //                     hoverOffset: 10
    //                 }]
    //             },
    //             options: {
    //                 responsive: true,
    //                 maintainAspectRatio: false,
    //                 plugins: {
    //                     legend: {
    //                         position: 'bottom',
    //                         labels: {
    //                             padding: 20,
    //                             usePointStyle: true,
    //                             font: {
    //                                 family: 'Poppins',
    //                                 size: 14
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         });
    //     }

    //     // Monthly Trend Chart
    //     const trendCtx = document.getElementById('trendChart');
    //     if (trendCtx) {
    //         this.config.charts.trend = new Chart(trendCtx, {
    //             type: 'line',
    //             data: {
    //                 labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    //                 datasets: [{
    //                     label: 'Income',
    //                     data: [50000000, 45000000, 60000000, 55000000, 70000000, 65000000],
    //                     borderColor: '#10B981',
    //                     backgroundColor: 'rgba(16, 185, 129, 0.1)',
    //                     tension: 0.4,
    //                     fill: true
    //                 }, {
    //                     label: 'Expenses',
    //                     data: [35000000, 40000000, 45000000, 50000000, 48000000, 52000000],
    //                     borderColor: '#EF4444',
    //                     backgroundColor: 'rgba(239, 68, 68, 0.1)',
    //                     tension: 0.4,
    //                     fill: true
    //                 }]
    //             },
    //             options: {
    //                 responsive: true,
    //                 maintainAspectRatio: false,
    //                 scales: {
    //                     y: {
    //                         beginAtZero: true,
    //                         ticks: {
    //                             callback: function(value) {
    //                                 return new Intl.NumberFormat('vi-VN', {
    //                                     style: 'currency',
    //                                     currency: 'VND'
    //                                 }).format(value);
    //                             },
    //                             font: {
    //                                 family: 'Poppins'
    //                             }
    //                         }
    //                     },
    //                     x: {
    //                         ticks: {
    //                             font: {
    //                                 family: 'Poppins'
    //                             }
    //                         }
    //                     }
    //                 },
    //                 plugins: {
    //                     legend: {
    //                         labels: {
    //                             usePointStyle: true,
    //                             font: {
    //                                 family: 'Poppins'
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         });
    //     }

    //     // Category Spending Chart
    //     const categoryCtx = document.getElementById('categoryChart');
    //     if (categoryCtx) {
    //         this.config.charts.category = new Chart(categoryCtx, {
    //             type: 'bar',
    //             data: {
    //                 labels: ['Food', 'Transport', 'Shopping', 'Bills', 'Entertainment'],
    //                 datasets: [{
    //                     label: 'Spending',
    //                     data: [12000000, 8000000, 15000000, 10000000, 6000000],
    //                     backgroundColor: [
    //                         '#10B981',
    //                         '#3B82F6',
    //                         '#F59E0B',
    //                         '#EF4444',
    //                         '#8B5CF6'
    //                     ],
    //                     borderRadius: 8
    //                 }]
    //             },
    //             options: {
    //                 responsive: true,
    //                 maintainAspectRatio: false,
    //                 scales: {
    //                     y: {
    //                         beginAtZero: true,
    //                         ticks: {
    //                             callback: function(value) {
    //                                 return new Intl.NumberFormat('vi-VN', {
    //                                     style: 'currency',
    //                                     currency: 'VND'
    //                                 }).format(value);
    //                             }
    //                         }
    //                     }
    //                 },
    //                 plugins: {
    //                     legend: {
    //                         display: false
    //                     }
    //                 }
    //             }
    //         });
    //     }
    // },

    // Initialize modals
    initializeModals() {
        // Modal triggers
        const modalTriggers = document.querySelectorAll('[data-modal]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.getAttribute('data-modal');
                this.openModal(modalId);
            });
        });

        // Modal close buttons
        const closeButtons = document.querySelectorAll('.modal-close, .modal-backdrop');
        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeModal();
            });
        });

        // ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    },

    // Modal management
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            const firstInput = modal.querySelector('input, select, textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    },

    closeModal() {
        const activeModal = document.querySelector('.modal.active');
        if (activeModal) {
            activeModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    },

    // Form handling
    initializeForms() {
        // Form validation and submission
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
            });
        });

        // Dynamic form behaviors
        this.initializeDynamicForms();
    },

    // Form validation
    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    },

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.getAttribute('name');
        let isValid = true;
        let message = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'This field is required';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address';
            }
        }

        // Number validation
        if (field.type === 'number' && value) {
            if (isNaN(value) || parseFloat(value) < 0) {
                isValid = false;
                message = 'Please enter a valid positive number';
            }
        }

        // Password validation
        if (field.type === 'password' && value) {
            if (value.length < 8) {
                isValid = false;
                message = 'Password must be at least 8 characters long';
            }
        }

        this.showFieldValidation(field, isValid, message);
        return isValid;
    },

    showFieldValidation(field, isValid, message) {
        // Remove existing validation
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        field.classList.remove('field-valid', 'field-invalid');

        if (!isValid && message) {
            field.classList.add('field-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        } else if (isValid && field.value.trim()) {
            field.classList.add('field-valid');
        }
    },

    // Dynamic form behaviors
    initializeDynamicForms() {
        // Category type selection
        const typeSelects = document.querySelectorAll('select[name="type"]');
        typeSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                this.updateCategoryOptions(e.target.value);
            });
        });

        // Amount formatting
        // const amountInputs = document.querySelectorAll('input[name*="amount"]');
        // amountInputs.forEach(input => {
        //     input.addEventListener('input', (e) => {
        //         this.formatCurrency(e.target);
        //     });
        // });
    },

    // Table enhancements
    initializeTables() {
        // Data table sorting
        const sortHeaders = document.querySelectorAll('.data-table th[data-sort]');
        sortHeaders.forEach(header => {
            header.addEventListener('click', () => {
                this.sortTable(header);
            });
        });

        // Row actions
        const actionButtons = document.querySelectorAll('.btn-action');
        actionButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleRowAction(button);
            });
        });

        // Bulk actions
        const bulkCheckboxes = document.querySelectorAll('.bulk-checkbox');
        if (bulkCheckboxes.length > 0) {
            this.initializeBulkActions(bulkCheckboxes);
        }
    },

    // Table sorting
    sortTable(header) {
        const table = header.closest('.data-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const column = header.getAttribute('data-sort');
        const isAscending = !header.classList.contains('sort-asc');

        // Sort rows
        rows.sort((a, b) => {
            const aVal = a.querySelector(`[data-${column}]`).textContent.trim();
            const bVal = b.querySelector(`[data-${column}]`).textContent.trim();
            
            // Number comparison
            if (!isNaN(aVal) && !isNaN(bVal)) {
                return isAscending ? aVal - bVal : bVal - aVal;
            }
            
            // String comparison
            return isAscending ? 
                aVal.localeCompare(bVal) : 
                bVal.localeCompare(aVal);
        });

        // Update DOM
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));

        // Update header classes
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        header.classList.add(isAscending ? 'sort-asc' : 'sort-desc');
    },

    // Initialize animations
    initializeAnimations() {
        // Fade in animations for cards
        const cards = document.querySelectorAll('.card, .stat-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    entry.target.style.transition = 'all 0.6s ease';
                    
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                }
            });
        });

        cards.forEach(card => observer.observe(card));

        // Progress bar animations
        const progressBars = document.querySelectorAll('.progress-bar-fill');
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width') || '0';
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 500);
        });
    },

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            value = new Intl.NumberFormat('vi-VN').format(value);
            input.value = value;
        }
    },

    formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    },

    handleSearch(query) {
        if (query.length < 2) return;
        
        // Implement search functionality
        console.log('Searching for:', query);
        // This would typically make an AJAX request
    },

    highlightActiveNavigation() {
       // ĐÃ XÓA LOGIC Ở ĐÂY ĐỂ KHÔNG GÂY XUNG ĐỘT VỚI PHP
    },

    handleRowAction(button) {
        const action = button.getAttribute('data-action');
        const id = button.getAttribute('data-id');
        
        switch (action) {
            case 'edit':
                this.openModal('editModal');
                // Populate form with data
                break;
            case 'delete':
                this.confirmDelete(id);
                break;
            case 'view':
                this.viewDetails(id);
                break;
        }
    },

    confirmDelete(id) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteItem(id);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this item?')) {
                this.deleteItem(id);
            }
        }
    },

    deleteItem(id) {
        // Implement delete functionality
        console.log('Deleting item:', id);
        // This would typically make an AJAX request
    },

    showNotification(message, type = 'success') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: type,
                title: message
            });
        } else {
            // Fallback notification
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 3000);
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.FinanceApp.init();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.FinanceApp;
}