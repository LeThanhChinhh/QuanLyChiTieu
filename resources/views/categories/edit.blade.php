@extends('layouts.app')

@section('styles')
    @vite(['resources/css/categories.css'])
@endsection

@section('content')
<div class="glass-form-container">
    <div class="glass-header">
        <h5 class="fw-bold text-primary mb-0">
            <i class="ri-edit-line me-2"></i>Chỉnh sửa Danh mục
        </h5>
    </div>
    
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="glass-form-group">
            <label class="glass-label">Loại danh mục</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="type_expense" value="expense" {{ $category->type == 'expense' ? 'checked' : '' }}>
                    <label class="form-check-label text-danger fw-bold" for="type_expense">
                        Chi tiêu
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="type_income" value="income" {{ $category->type == 'income' ? 'checked' : '' }}>
                    <label class="form-check-label text-success fw-bold" for="type_income">
                        Thu nhập
                    </label>
                </div>
            </div>
        </div>

        <div class="glass-form-group">
            <label class="glass-label">Tên danh mục</label>
            <input type="text" name="name" class="glass-input" value="{{ $category->name }}" required>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="glass-form-group">
                    <label class="glass-label">Màu sắc</label>
                    <input type="color" name="color" class="glass-input" value="{{ $category->color }}" title="Chọn màu" style="height: 48px; padding: 4px;">
                </div>
            </div>

            <div class="col-md-8 mb-3">
                <div class="glass-form-group">
                    <label class="glass-label">Biểu tượng</label>
                    <select name="icon" class="glass-input font-family-icon">
                        <option value="ri-restaurant-2-line" {{ $category->icon == 'ri-restaurant-2-line' ? 'selected' : '' }}>🍽️ Ăn uống</option>
                        <option value="ri-car-line" {{ $category->icon == 'ri-car-line' ? 'selected' : '' }}>🚗 Di chuyển</option>
                        <option value="ri-home-4-line" {{ $category->icon == 'ri-home-4-line' ? 'selected' : '' }}>🏠 Nhà cửa</option>
                        <option value="ri-shopping-bag-3-line" {{ $category->icon == 'ri-shopping-bag-3-line' ? 'selected' : '' }}>🛍️ Mua sắm</option>
                        <option value="ri-wallet-3-line" {{ $category->icon == 'ri-wallet-3-line' ? 'selected' : '' }}>💰 Lương/Ví</option>
                        <option value="ri-gift-line" {{ $category->icon == 'ri-gift-line' ? 'selected' : '' }}>🎁 Quà tặng</option>
                        <option value="ri-heart-pulse-line" {{ $category->icon == 'ri-heart-pulse-line' ? 'selected' : '' }}>💊 Sức khỏe</option>
                        <option value="ri-book-read-line" {{ $category->icon == 'ri-book-read-line' ? 'selected' : '' }}>📚 Giáo dục</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="glass-footer">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold">Cập nhật</button>
        </div>
    </form>
</div>
@endsection