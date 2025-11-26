/**
 * Personal Finance Manager - Main JavaScript
 * Entry point that initializes all modules
 */

import './bootstrap';

// Import all modules
import { formatCurrency, showToast, confirmAction, formatCurrencyElements } from './utils';
import { initCharts, initializeChart } from './charts';
import { setupModals, openModal, closeModal } from './modals';
import { setupAjaxForms } from './forms';
import { setupDataTables } from './tables';
import { setupScrollAnimations, setupSmoothScroll } from './animations';

// ========== Initialize Everything ==========

document.addEventListener('DOMContentLoaded', function() {
    // Initialize features
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
