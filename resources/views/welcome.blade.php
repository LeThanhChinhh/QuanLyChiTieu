<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuanLyChiTieu - Quản lý tài chính thông minh</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite(['resources/css/welcome.css'])
</head>
<body>
    <!-- Background Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="ri-bar-chart-box-fill" style="color: white; font-size: 20px;"></i>
                    </div>
                    <span>QuanLyChiTieu</span>
                </div>
                <div class="nav-links">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="nav-link">Tổng quan</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link">Đăng nhập</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Bắt đầu ngay</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <div class="container">
        <main class="hero-section">
            <div class="hero-content">
                <div class="badge">✨ Theo dõi tài chính thông minh</div>
                <h1>Làm chủ tài chính với sự <span class="gradient-text">Chính xác</span></h1>
                <p>Trải nghiệm thế hệ quản lý tài chính cá nhân tiếp theo. Theo dõi, phân tích và phát triển tài sản của bạn với nền tảng trực quan của chúng tôi.</p>
                
                <div class="cta-group">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Bắt đầu ngay</a>
                    <a href="#features" class="btn btn-outline btn-lg">Tìm hiểu thêm</a>
                </div>

                <div class="trust-badges">
                    <span>Được tin dùng bởi nhiều người</span>
                    <div class="avatars">
                        <img src="https://i.pravatar.cc/150?img=1" alt="User" class="avatar">
                        <img src="https://i.pravatar.cc/150?img=2" alt="User" class="avatar">
                        <img src="https://i.pravatar.cc/150?img=3" alt="User" class="avatar">
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <!-- Main Dashboard Card -->
                <div class="dashboard-card main-card">
                    <div class="card-header">
                        <div class="header-text">
                            <div class="line short"></div>
                            <div class="line long"></div>
                        </div>
                        <div class="header-icon"></div>
                    </div>
                    <div class="chart-area">
                        <div class="bar" style="height: 40%"></div>
                        <div class="bar" style="height: 70%"></div>
                        <div class="bar" style="height: 50%"></div>
                        <div class="bar" style="height: 85%"></div>
                        <div class="bar" style="height: 60%"></div>
                        <div class="bar" style="height: 90%"></div>
                    </div>
                </div>
                
                <!-- Floating Widgets -->
                <div class="floating-widget widget-balance">
                    <div class="icon-box">
                        <i class="ri-wallet-3-line" style="color: #10B981; font-size: 24px;"></i>
                    </div>
                    <div class="text-box">
                        <span>Tổng số dư</span>
                        <strong>24,500,000₫</strong>
                    </div>
                </div>
                <div class="floating-widget widget-growth">
                    <div class="growth-icon">
                        <i class="ri-arrow-up-line" style="color: #3B82F6; font-size: 24px;"></i>
                    </div>
                    <div class="text-box">
                        <span>Tăng trưởng</span>
                        <strong>+12.5%</strong>
                    </div>
                </div>
            </div>
        </main>

        <section id="features" class="features-section">
            <div class="feature-card">
                <div class="feature-icon icon-analytics">
                    <i class="ri-line-chart-line" style="color: #10B981; font-size: 32px;"></i>
                </div>
                <h3>Phân tích thông minh</h3>
                <p>Trực quan hóa thói quen chi tiêu của bạn với biểu đồ tương tác đẹp mắt và dữ liệu theo thời gian thực.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-budget">
                    <i class="ri-wallet-3-line" style="color: #3B82F6; font-size: 32px;"></i>
                </div>
                <h3>Kiểm soát ngân sách</h3>
                <p>Thiết lập ngân sách thông minh cho từng danh mục và nhận thông báo trước khi chi tiêu vượt mức.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-secure">
                    <i class="ri-shield-check-line" style="color: #F59E0B; font-size: 32px;"></i>
                </div>
                <h3>Bảo mật cấp ngân hàng</h3>
                <p>Dữ liệu tài chính của bạn được mã hóa và bảo vệ với tiêu chuẩn bảo mật hàng đầu trong ngành.</p>
            </div>
        </section>

        <footer style="margin-top: 5rem; padding: 3rem 0; border-top: 1px solid #eee;">
    <div class="container" style="text-align: center;">
        <div style="margin-bottom: 1.5rem;">
            <a href="#" style="margin: 0 15px; color: #666; text-decoration: none;">Chính sách bảo mật</a>
            <a href="#" style="margin: 0 15px; color: #666; text-decoration: none;">Điều khoản dịch vụ</a>
            <a href="#" style="margin: 0 15px; color: #666; text-decoration: none;">Hỗ trợ</a>
        </div>
        <p style="color: #999;">&copy; 2025 QuanLyChiTieu. Được tạo ra với ❤️ vì sự tự do tài chính.</p>
    </div>
</footer>
    </div>
</body>
</html>
