<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
    <div class="blob blob-1" style="background: #4f46e5;"></div>
    <div class="blob blob-2" style="background: #0f172a;"></div>
    
    <div class="app-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2>
                    <i class="ri-shield-keyhole-line"></i>
                    <span>Admin Panel</span>
                </h2>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="ri-user-settings-line"></i> <span>Quản lý User</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="ri-folder-settings-line"></i> <span>Danh mục Mẫu</span>
                    </a>
                </div>

                <div class="nav-item mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link w-full text-left" style="background: none; border: none; cursor: pointer;">
                            <i class="ri-logout-box-line text-danger"></i> <span class="text-danger">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="main-content" id="mainContent">
            <header class="header">
                <div class="header-left">
                    <div class="header-title-wrapper">
                        <h2 class="header-title">
                            @yield('title', 'Admin Dashboard') <span class="badge badge-admin">ADMIN</span>
                        </h2>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="user-profile flex items-center gap-2">
                        <div class="user-info flex flex-col" style="text-align: right;">
                            <span class="user-name fw-bold">{{ Auth::user()->name }}</span>
                            <span class="user-role text-muted" style="font-size: 0.8rem;">Administrator</span>
                        </div>
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="Avatar" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Global Confirmation Modal -->
    <div id="confirmModal" class="modal-backdrop">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="ri-error-warning-line me-2"></i>Xác nhận
                </h5>
                <button type="button" class="btn btn-icon" onclick="closeModal('confirmModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body pt-2">
                <p id="confirmMessage" class="text-muted mb-0">Bạn có chắc chắn muốn thực hiện hành động này?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" onclick="closeModal('confirmModal')">Hủy</button>
                <button type="button" id="confirmBtn" class="btn btn-danger">Đồng ý</button>
            </div>
        </div>
    </div>
</body>
</html>
