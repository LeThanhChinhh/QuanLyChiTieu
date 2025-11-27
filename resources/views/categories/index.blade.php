@extends('layouts.app')

@section('title', 'Danh mục - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/categories.css'])
@endsection

@section('content')
<!-- Actions -->
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i>
        <span>Thêm danh mục</span>
    </a>
</div>

<!-- Success Alert -->
@if(session('success'))
    <div class="alert alert-success">
        <i class="ri-checkbox-circle-line"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<!-- Categories Grid -->
@if(isset($categories) && count($categories) > 0)
    <div class="categories-grid">
        @foreach($categories as $category)
            <div class="category-card">
                <!-- Main Content (Horizontal Layout) -->
                <div class="category-content-wrapper">
                    <!-- Left: Icon -->
                    <div class="category-icon-wrapper">
                        <div class="category-icon-large" style="background: {{ $category->color }}; color: #fff;">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                    </div>
                    
                    <!-- Right: Name & Badge -->
                    <div class="category-details">
                        <h3 class="category-name">{{ $category->name }}</h3>
                        <span class="category-type-badge {{ $category->type }}">
                            {{ $category->type === 'income' ? 'Thu nhập' : 'Chi tiêu' }}
                        </span>
                    </div>
                </div>

                <!-- Stats (Bottom Section) -->
                <div class="category-stats">
                    <div class="category-stat-row">
                        <span class="category-stat-label">Tháng này</span>
                        <span class="category-stat-value">{{ number_format($category->monthly_total ?? 0, 0, ',', '.') }}₫</span>
                    </div>
                </div>

                <!-- Action Buttons (Bottom with Border Top) -->
                @if($category->user_id)
                <div class="category-actions">
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-icon btn-secondary" title="Sửa">
                        <i class="ri-edit-line"></i>
                    </a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon btn-danger" title="Xóa"
                                onclick="return confirm('Bạn có chắc muốn xóa danh mục này? Tất cả giao dịch liên quan sẽ bị ảnh hưởng.')">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
                @else
                <div class="category-actions">
                    <span class="badge bg-secondary" title="Danh mục hệ thống"><i class="ri-lock-line"></i> System</span>
                </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="categories-empty">
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ri-folder-3-line"></i>
                </div>
                <h3 class="empty-state-title">Chưa có danh mục</h3>
                <p class="empty-state-text">Tạo danh mục để tổ chức và quản lý giao dịch của bạn</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="ri-add-line"></i>
                    <span>Tạo danh mục đầu tiên</span>
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
