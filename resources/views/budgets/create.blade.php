@extends('layouts.app')

@section('content')
<div class="glass-form-container">
    <div class="glass-header">
        <h5 class="fw-bold text-primary mb-0">
            <i class="ri-add-circle-line me-2"></i>Thiết lập Ngân sách Mới
        </h5>
    </div>
    
    <form action="{{ route('budgets.store') }}" method="POST">
        @csrf
        
        <div class="glass-form-group">
            <label class="glass-label">Áp dụng cho Danh mục nào?</label>
            <select name="category_id" class="glass-input" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="glass-form-group">
            <label class="glass-label">Số tiền tối đa (VNĐ)</label>
            <input type="text" class="glass-input large-amount" placeholder="Ví dụ: 5.000.000" required oninput="formatCurrency(this, 'amount')">
            <input type="hidden" name="amount" id="amount">
            <div class="form-text mt-2">Bạn muốn giới hạn chi tiêu bao nhiêu cho mục này?</div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <div class="glass-form-group">
                    <label class="glass-label">Tháng</label>
                    <select name="month" class="glass-input">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="glass-form-group">
                    <label class="glass-label">Năm</label>
                    <select name="year" class="glass-input">
                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        <option value="{{ date('Y')+1 }}">{{ date('Y')+1 }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="glass-footer">
            <a href="{{ route('budgets.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold">Lưu Ngân Sách</button>
        </div>
    </form>
</div>

<script>
    function formatCurrency(input, targetId) {
        let value = input.value.replace(/\D/g, '');
        if (targetId) document.getElementById(targetId).value = value;
        if (value !== '') input.value = new Intl.NumberFormat('vi-VN').format(value);
        else input.value = '';
    }
</script>
@endsection