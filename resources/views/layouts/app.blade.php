<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý tài chính')</title>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    
    @vite(['resources/css/common.css', 'resources/css/layout.css'])
    @yield('styles')
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="app-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2>
                    <i class="ri-bar-chart-box-line"></i>
                    <span>Quản lý tài chính</span>
                </h2>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-3-line"></i> <span>Tổng quan</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('wallets.index') }}" class="nav-link {{ request()->routeIs('wallets.*') ? 'active' : '' }}">
                        <i class="ri-wallet-3-line"></i> <span>Ví của tôi</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <i class="ri-exchange-dollar-line"></i> <span>Giao dịch</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('recurring.index') }}" class="nav-link {{ request()->routeIs('recurring.*') ? 'active' : '' }}">
                        <i class="ri-loop-right-line"></i> <span>Định kỳ</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('calendar.index') }}" class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <i class="ri-calendar-line"></i> <span>Lịch</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('budgets.index') }}" class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                        <i class="ri-pie-chart-2-line"></i> <span>Ngân sách</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="ri-folder-3-line"></i> <span>Danh mục</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('help.index') }}" class="nav-link {{ request()->routeIs('help.*') ? 'active' : '' }}">
                        <i class="ri-question-line"></i> <span>Hướng dẫn</span>
                    </a>
                </div>
            </nav>
        </aside>

        <main class="main-content" id="mainContent">
            <header class="header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="ri-menu-line"></i>
                    </button>
                    
                    <div class="header-title-wrapper">
                        <h2 class="header-title">
                            @yield('title', 'Quản lý tài chính')
                        </h2>
                        <span class="header-date">
                            {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="position-relative dropdown-container">
                        <button class="header-action-btn" onclick="toggleDropdown('notifyDropdown')">
                            <i class="ri-notification-3-line" style="font-size: 1.25rem;"></i>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="badge">{{ $unreadCount }}</span>
                            @endif
                        </button>

                        <div class="header-dropdown-menu" id="notifyDropdown" style="width: 360px;">
                            <div class="dropdown-header">
                                <h6 class="dropdown-header-title">Thông báo</h6>
                                @if(isset($unreadCount) && $unreadCount > 0)
                                    <a href="{{ route('notifications.markAllRead') }}" class="dropdown-header-link">Đánh dấu đã đọc</a>
                                @endif
                            </div>
                            
                            <div class="notification-list">
                                @forelse($notifications ?? [] as $notification)
                                    <a href="{{ $notification->data['link'] ?? '#' }}" class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                        <div class="notification-icon {{ $notification->data['type'] ?? 'info' }}">
                                            <i class="{{ $notification->data['icon'] ?? 'ri-notification-line' }}"></i>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-title">{{ $notification->data['title'] ?? 'Thông báo' }}</div>
                                            <div class="notification-desc">{{ $notification->data['message'] ?? '' }}</div>
                                            <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted">
                                        <i class="ri-notification-off-line" style="font-size: 2rem; opacity: 0.5;"></i>
                                        <p class="small mt-2 mb-0">Không có thông báo nào</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div style="width: 1px; height: 24px; background: #e2e8f0;"></div>

                    <div class="position-relative dropdown-container">
                        <button class="user-profile-btn" onclick="toggleDropdown('userDropdown')">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="user-avatar-img" style="object-fit: cover;">
                            @else
                                <div class="user-avatar-img">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            
                            <div class="user-info d-none d-md-flex">
                                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                <span class="user-role">Thành viên</span>
                            </div>
                            
                            <i class="ri-arrow-down-s-line text-muted ms-2"></i>
                        </button>
                        
                        <div class="header-dropdown-menu" id="userDropdown">
                            <a href="{{ route('profile.edit') }}" class="header-dropdown-item">
                                <i class="ri-user-settings-line"></i> Hồ sơ cá nhân
                            </a>
                            <div class="border-top my-1"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="header-dropdown-item text-danger">
                                    <i class="ri-logout-box-line"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // --- CHỈ GIỮ LẠI LOGIC DROPDOWN (MENU XỔ XUỐNG) ---
        
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            // Đóng các menu khác
            document.querySelectorAll('.header-dropdown-menu').forEach(menu => {
                if (menu.id !== id) menu.classList.remove('show');
            });
            // Toggle menu hiện tại
            if (el) el.classList.toggle('show');
        }

        // Đóng menu khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.header-dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // --- ĐÃ XÓA LOGIC SIDEBAR ĐỂ TRÁNH XUNG ĐỘT VỚI APP.JS ---
    </script>
    @yield('scripts')
</body>
</html>