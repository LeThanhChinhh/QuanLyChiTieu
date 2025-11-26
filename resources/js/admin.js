// Admin Panel Scripts

// Modal Logic
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
    }
}

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-backdrop')) {
        event.target.classList.remove('show');
    }
});

// Custom Confirm Dialog
window.showConfirm = function(message, callback) {
    const modal = document.getElementById('confirmModal');
    const msgElem = document.getElementById('confirmMessage');
    const btnElem = document.getElementById('confirmBtn');
    
    if (modal && msgElem && btnElem) {
        msgElem.textContent = message || 'Bạn có chắc chắn muốn thực hiện hành động này?';
        
        // Remove old event listeners to prevent multiple firings
        const newBtn = btnElem.cloneNode(true);
        btnElem.parentNode.replaceChild(newBtn, btnElem);
        
        newBtn.addEventListener('click', function() {
            closeModal('confirmModal');
            if (typeof callback === 'function') {
                callback();
            }
        });
        
        openModal('confirmModal');
    } else {
        // Fallback if modal elements are missing
        if (confirm(message)) {
            callback();
        }
    }
}

// Helper to handle form submissions with confirmation
window.confirmSubmit = function(event, message) {
    event.preventDefault();
    const form = event.target.closest('form');
    
    showConfirm(message, function() {
        form.submit();
    });
    return false;
}

