@extends('layouts.app')

@section('title', 'Tạo nhóm mới - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="form-container-centered">
    <div class="form-card-modern">
        <div class="form-header-modern">
            <h2 class="text-primary">
                <i class="ri-add-line me-2"></i>Tạo Nhóm Mới
            </h2>
        </div>

        <form action="{{ route('groups.store') }}" method="POST">
            @csrf

            <div class="form-group-modern">
                <label class="form-label-modern">Tên nhóm <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-input-modern" 
                       placeholder="VD: Du lịch Đà Lạt, Tiền nhà tháng 11..."
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">Mô tả</label>
                <textarea name="description" class="form-input-modern" rows="3" 
                          placeholder="Mô tả ngắn gọn về nhóm chi tiêu này...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Màu sắc</label>
                        <input type="color" name="color" class="form-input-modern" 
                               value="{{ old('color', '#10B981') }}" style="height: 48px; padding: 4px;">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Icon</label>
                        <select name="icon" class="form-input-modern">
                            <option value="ri-team-line">👥 Nhóm</option>
                            <option value="ri-home-4-line">🏠 Gia đình</option>
                            <option value="ri-user-heart-line">❤️ Bạn bè</option>
                            <option value="ri-briefcase-line">💼 Công việc</option>
                            <option value="ri-flight-takeoff-line">✈️ Du lịch</option>
                            <option value="ri-building-line">🏢 Phòng trọ</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions-modern">
                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="ri-save-line"></i> Tạo Nhóm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
