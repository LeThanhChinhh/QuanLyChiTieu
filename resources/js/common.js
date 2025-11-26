/* ===================================
   Common JavaScript
   =================================== */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initUserMenu();
    initSearch();
});

// Sidebar Toggle
function initSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            sidebar.classList.toggle('open');
            if (mainContent) {
                mainContent.classList.toggle('expanded');
            }
        });
    }
}

// User Menu Toggle
function initUserMenu() {
    const userMenuTrigger = document.getElementById('userMenuTrigger');
    const userMenu = document.getElementById('userMenu');

    if (userMenuTrigger && userMenu) {
        userMenuTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userMenu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove('active');
            }
        });
    }
}

// Search Functionality
function initSearch() {
    const searchInput = document.querySelector('.search-box input');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // Add your search logic here
        }, 300));
    }
}

// Utility: Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Show Alert
function showAlert(message, type = 'success') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            showConfirmButton: true,
            timer: 3000
        });
    } else {
        alert(message);
    }
}

// Confirm Dialog
function confirmAction(message, callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Yes, do it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    } else {
        if (confirm(message) && callback) {
            callback();
        }
    }
}

// Format Currency (VND)
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Format Date
function formatDate(date, format = 'DD/MM/YYYY') {
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    
    return format
        .replace('DD', day)
        .replace('MM', month)
        .replace('YYYY', year);
}

// Modal Functions
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

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal(e.target.id);
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal-overlay.active');
        if (activeModal) {
            closeModal(activeModal.id);
        }
    }
});

// Export functions for use in other files
window.FinanceApp = {
    showAlert,
    confirmAction,
    formatCurrency,
    formatDate,
    openModal,
    closeModal
};
