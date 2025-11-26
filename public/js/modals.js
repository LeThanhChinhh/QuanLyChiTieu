/**
 * Modal Management
 * Handle opening, closing, and modal interactions
 */

/**
 * Setup modal event listeners
 */
export function setupModals() {
    // Modal triggers
    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal-target');
            openModal(modalId);
        });
    });

    // Modal close buttons
    document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) closeModal(activeModal.id);
        }
    });
}

/**
 * Open modal by ID
 */
export function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close modal by ID
 */
export function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}
