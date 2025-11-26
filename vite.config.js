import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 1. Các file CSS giao diện chính (Load trên mọi trang hoặc trang cụ thể)
                'resources/css/app.css',       // File gốc (nếu có dùng)
                'resources/css/common.css',    // CSS dùng chung (biến màu, font, reset)
                'resources/css/layout.css',    // CSS cho Sidebar, Header, Footer
                
                // 2. Các file CSS riêng cho từng module
                'resources/css/auth.css',        // Login/Register
                'resources/css/welcome.css',     // Trang Landing Page
                'resources/css/dashboard.css',   // Trang Dashboard chính
                'resources/css/transactions.css', // Trang Giao dịch
                'resources/css/budgets.css',     // Trang Ngân sách
                'resources/css/categories.css',  // Trang Danh mục
                'resources/css/recurring.css',   // Trang Giao dịch định kỳ
                'resources/css/profile.css',     // Trang Hồ sơ cá nhân
                'resources/css/admin.css',       // Admin Panel CSS

                // 3. Các file JavaScript logic
                'resources/js/app.js',         // JS chính của Laravel
                'resources/js/admin.js',       // Admin Panel JS
                'resources/js/common.js',      // JS dùng chung (Toggle menu, format tiền...)
                'resources/js/dashboard.js',   // JS vẽ biểu đồ (Chart.js)
            ],
            refresh: true,
        }),
    ],
});