@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('styles')
    @vite(['resources/css/profile.css'])
@endsection

@section('content')
<div class="profile-grid">
    
    <!-- Basic Info & Avatar -->
    <div class="glass-form-container h-full">
        <div class="glass-header">
            <h3><i class="ri-user-settings-line"></i> Thông tin cơ bản</h3>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Avatar Upload -->
            <div class="avatar-upload-container">
                <div class="avatar-wrapper">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-preview" id="avatarPreview">
                    @else
                        <div class="avatar-initials" id="avatarInitials">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img src="" alt="Avatar" class="avatar-preview hidden" id="avatarPreviewHidden">
                    @endif
                    
                    <label for="avatarInput" class="avatar-upload-btn">
                        <i class="ri-camera-line"></i>
                    </label>
                    <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="text-center">
                    <p class="text-secondary mb-0" style="font-size: 0.875rem;">Nhấn vào icon máy ảnh để thay đổi ảnh đại diện</p>
                </div>
            </div>

            <div class="glass-form-group">
                <label class="glass-label">Họ và tên</label>
                <input type="text" name="name" class="glass-input" value="{{ old('name', $user->name) }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="glass-form-group">
                <label class="glass-label">Địa chỉ Email</label>
                <input type="email" name="email" class="glass-input" value="{{ old('email', $user->email) }}" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center">
                <i class="ri-save-line"></i> Lưu thay đổi
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="glass-form-container h-full">
        <div class="glass-header">
            <h3><i class="ri-lock-password-line"></i> Đổi mật khẩu</h3>
        </div>
        
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="glass-form-group">
                <label class="glass-label">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="glass-input" required>
                @error('current_password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="glass-form-group">
                <label class="glass-label">Mật khẩu mới</label>
                <input type="password" name="password" class="glass-input" required>
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="glass-form-group">
                <label class="glass-label">Nhập lại mật khẩu mới</label>
                <input type="password" name="password_confirmation" class="glass-input" required>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center">
                <i class="ri-key-2-line"></i> Đổi mật khẩu
            </button>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                // Hide initials if visible
                var initials = document.getElementById('avatarInitials');
                if (initials) initials.style.display = 'none';
                
                // Show preview image
                var preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    // If preview img was hidden (initials case)
                    var hiddenPreview = document.getElementById('avatarPreviewHidden');
                    hiddenPreview.src = e.target.result;
                    hiddenPreview.classList.remove('hidden');
                    hiddenPreview.style.display = 'block';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection