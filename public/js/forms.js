/**
 * Form Handlers
 * AJAX form submissions and validation
 */

import { showToast } from './utils';

/**
 * Setup AJAX form submissions
 */
export function setupAjaxForms() {
    const ajaxForms = document.querySelectorAll('[data-ajax-form]');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            const method = this.method;

            // Show loading state
            const submitBtn = this.querySelector('[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Processing...';

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
                    
                    // Reset form if specified
                    if (this.hasAttribute('data-reset-on-success')) {
                        this.reset();
                    }
                    
                    // Reload page if specified
                    if (this.hasAttribute('data-reload-on-success')) {
                        setTimeout(() => location.reload(), 1500);
                    }
                } else {
                    showToast(data.message || 'Operation failed!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    });
}
