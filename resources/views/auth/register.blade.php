<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Quản lý tài chính</title>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    @vite(['resources/css/common.css', 'resources/css/auth.css'])
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="auth-container">
        <div class="auth-brand">
            <i class="ri-bar-chart-box-fill"></i>
            <span>Quản lý tài chính</span>
        </div>

        <div class="auth-card">
            <div class="auth-header">
                <h2>Tạo tài khoản mới</h2>
                <p>Bắt đầu quản lý tài chính ngay hôm nay</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <div class="input-wrapper">
                        <i class="ri-user-line"></i>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="ri-mail-line"></i>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="ri-lock-password-line"></i>
                        <input type="password" name="password" class="form-control" placeholder="Tối thiểu 8 ký tự" required>
                    </div>
                    <small style="color: #6B7280; font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                        Bao gồm: chữ thường, chữ HOA, số và ký tự đặc biệt (@$!%*?&#)
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Nhập lại mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="ri-lock-password-line"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu" required>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Tôi đồng ý với <a href="#" class="forgot-link">Điều khoản</a></label>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="ri-user-add-line"></i> Đăng ký tài khoản
                </button>
            </form>

            <div class="divider"><span>HOẶC</span></div>

            <div class="social-login">
                <a href="{{ route('auth.google') }}" class="social-btn" style="text-decoration: none;">
                    <i class="ri-google-fill" style="color: #DB4437;"></i> Google
                </a>
                <button class="social-btn">
                    <i class="ri-facebook-circle-fill" style="color: #4267B2;"></i> Facebook
                </button>
            </div>

            <div class="auth-footer">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
            </div>
        </div>
    </div>
</body>
</html>