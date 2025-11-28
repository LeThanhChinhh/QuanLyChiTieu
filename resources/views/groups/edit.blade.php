@extends('layouts.app')

@section('title', 'Chỉnh sửa nhóm - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('groups.show', $group) }}" class="btn-back">
            <i class="ri-arrow-left-line"></i> {{ $group->name }}
        </a>
        <h1><i class="ri-settings-line"></i> Chỉnh Sửa Nhóm</h1>
    </div>
</div>

<div class="form-container-centered">
    <form action="{{ route('groups.update', $group) }}" method="POST" class="form-card-modern">
        @csrf
        @method('PUT')

        <div class="form-header-modern">
            <i class="ri-edit-line"></i>
            <h2>Thông tin nhóm</h2>
        </div>

        <div class="form-group-modern">
            <label for="name" class="form-label-modern">Tên nhóm <span class="required">*</span></label>
            <input type="text" id="name" name="name" class="form-input-modern" value="{{ old('name', $group->name) }}" required placeholder="VD: Du lịch Đà Lạt 2025">
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group-modern">
            <label for="description" class="form-label-modern">Mô tả</label>
            <textarea id="description" name="description" class="form-input-modern" rows="3" placeholder="Mô tả về nhóm (không bắt buộc)">{{ old('description', $group->description) }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group-modern">
                    <label for="icon" class="form-label-modern">Icon</label>
                    <select id="icon" name="icon" class="form-input-modern">
                        <option value="ri-team-line" {{ $group->icon === 'ri-team-line' ? 'selected' : '' }}>👥 Nhóm</option>
                        <option value="ri-home-4-line" {{ $group->icon === 'ri-home-4-line' ? 'selected' : '' }}>🏠 Gia đình</option>
                        <option value="ri-home-line" {{ $group->icon === 'ri-home-line' ? 'selected' : '' }}>🏡 Tiền nhà</option>
                        <option value="ri-user-heart-line" {{ $group->icon === 'ri-user-heart-line' ? 'selected' : '' }}>💑 Bạn bè</option>
                        <option value="ri-briefcase-line" {{ $group->icon === 'ri-briefcase-line' ? 'selected' : '' }}>💼 Công việc</option>
                        <option value="ri-flight-takeoff-line" {{ $group->icon === 'ri-flight-takeoff-line' ? 'selected' : '' }}>✈️ Du lịch</option>
                        <option value="ri-building-line" {{ $group->icon === 'ri-building-line' ? 'selected' : '' }}>🏢 Phòng trọ</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-modern">
                    <label for="color" class="form-label-modern">Màu sắc</label>
                    <input type="color" id="color" name="color" class="form-input-modern" value="{{ old('color', $group->color) }}" style="height: 50px; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="form-actions-modern">
            <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
                <i class="ri-close-line"></i> Hủy
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="ri-check-line"></i> Lưu Thay Đổi
            </button>
        </div>
    </form>

    @if($group->created_by === Auth::id())
        <div class="danger-zone" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 2px solid #EF4444; border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 2rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);">
            <h3 style="color: #EF4444; margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600;">
                <i class="ri-alert-line"></i> Vùng Nguy Hiểm
            </h3>
            <p style="color: var(--text-secondary); margin: 0 0 1rem 0; font-size: 0.875rem;">
                Xóa nhóm này sẽ xóa vĩnh viễn tất cả chi tiêu, số dư và lịch sử thanh toán. Hành động này không thể hoàn tác!
            </p>
            <form action="{{ route('groups.destroy', $group) }}" method="POST" id="deleteGroupForm">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="ri-delete-bin-line"></i> Xóa Nhóm Vĩnh Viễn
                </button>
            </form>
        </div>
    @endif
</div>

<script>
function confirmDelete() {
    if (confirm('⚠️ BẠN CÓ CHẮC CHẮN MUỐN XÓA NHÓM NÀY?\n\n✗ Tất cả chi tiêu sẽ bị xóa\n✗ Lịch sử thanh toán sẽ mất\n✗ Không thể khôi phục\n\nNhấn OK để tiếp tục xóa.')) {
        if (confirm('Xác nhận lần cuối: Bạn thực sự muốn xóa nhóm "{{ $group->name }}"?')) {
            document.getElementById('deleteGroupForm').submit();
        }
    }
}
</script>
@endsection
