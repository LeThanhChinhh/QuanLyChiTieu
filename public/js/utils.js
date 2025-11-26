/**
 * Utility Functions
 * General helper functions used throughout the app
 */

/**
 * Format number as currency (Vietnamese Dong)
 */
export function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Show toast notification using SweetAlert2
 */
export function showToast(message, type = 'success') {
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

/**
 * Confirm action with SweetAlert2
 */
export function confirmAction(title, text, confirmText = 'Yes, proceed!') {
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

/**
 * Format all numbers with currency class
 */
export function formatCurrencyElements() {
    document.querySelectorAll('[data-currency]').forEach(element => {
        const amount = parseFloat(element.getAttribute('data-currency'));
        if (!isNaN(amount)) {
            element.textContent = formatCurrency(amount);
        }
    });
}
