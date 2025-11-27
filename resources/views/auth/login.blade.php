<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý tài chính</title>
    
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
                <h2>Đăng nhập</h2>
                <p>Chào mừng bạn quay trở lại!</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    Email hoặc mật khẩu không đúng.
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="ri-mail-line"></i>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="ri-lock-password-line"></i>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ghi nhớ</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">
                    <i class="ri-login-circle-line"></i> Đăng nhập
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
                Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</body>
</html>