@extends('layouts.app')

@section('styles')
    @vite(['resources/css/categories.css'])
@endsection

@section('content')
<div class="glass-form-container">
    <div class="glass-header">
        <h5 class="fw-bold text-primary mb-0">
            <i class="ri-folder-add-line me-2"></i>Thêm Danh mục mới
        </h5>
    </div>
    
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="glass-form-group">
            <label class="glass-label">Loại danh mục</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="type_expense" value="expense" checked>
                    <label class="form-check-label text-danger fw-bold" for="type_expense">
                        Chi tiêu
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="type_income" value="income">
                    <label class="form-check-label text-success fw-bold" for="type_income">
                        Thu nhập
                    </label>
                </div>
            </div>
        </div>

        <div class="glass-form-group">
            <label class="glass-label">Tên danh mục</label>
            <input type="text" name="name" class="glass-input" required placeholder="Ví dụ: Ăn sáng, Lương tháng...">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="glass-form-group">
                    <label class="glass-label">Màu sắc</label>
                    <input type="color" name="color" class="glass-input" value="#10B981" title="Chọn màu" style="height: 48px; padding: 4px;">
                </div>
            </div>

            <div class="col-md-8 mb-3">
                <div class="glass-form-group">
                    <label class="glass-label">Biểu tượng</label>
                    <select name="icon" class="glass-input font-family-icon">
                        <option value="ri-restaurant-2-line">🍽️ Ăn uống</option>
                        <option value="ri-car-line">🚗 Di chuyển</option>
                        <option value="ri-home-4-line">🏠 Nhà cửa</option>
                        <option value="ri-shopping-bag-3-line">🛍️ Mua sắm</option>
                        <option value="ri-wallet-3-line">💰 Lương/Ví</option>
                        <option value="ri-gift-line">🎁 Quà tặng</option>
                        <option value="ri-heart-pulse-line">💊 Sức khỏe</option>
                        <option value="ri-book-read-line">📚 Giáo dục</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="glass-footer">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold">Lưu Danh mục</button>
        </div>
    </form>
</div>
@endsection