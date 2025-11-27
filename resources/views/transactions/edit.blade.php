@extends('layouts.app')

@section('title', 'Chỉnh sửa giao dịch')

@section('styles')
<style>
    /* CSS Cho Type Selector (Đồng bộ với trang Create) */
    .type-selector {
        display: flex;
        background: #F3F4F6;
        padding: 5px;
        border-radius: 15px;
        gap: 5px;
        margin-bottom: 1.5rem;
    }

    .type-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px;
        border-radius: 12px;
        font-weight: 600;
        color: #6B7280;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .type-btn:hover {
        background: rgba(255,255,255,0.5);
        color: #374151;
    }

    /* Active States */
    .type-btn.active.income { background: #fff; color: #10B981; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); }
    .type-btn.active.expense { background: #fff; color: #EF4444; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2); }
    .type-btn.active.transfer { background: #fff; color: #3B82F6; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2); }

    .glass-input { font-size: 1rem !important; }
</style>
@endsection

@section('content')
<div class="glass-form-container">
    <div class="glass-header">
        <h5 class="fw-bold text-primary mb-0">
            <i class="ri-edit-line me-2"></i>Chỉnh sửa giao dịch
        </h5>
    </div>

    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="glass-form-group">
            <label class="glass-label required">Loại giao dịch</label>
            
            <div class="type-selector">
                <button type="button" class="type-btn income" onclick="selectType('income')">
                    <i class="ri-arrow-down-line"></i> Thu nhập
                </button>
                <button type="button" class="type-btn expense" onclick="selectType('expense')">
                    <i class="ri-arrow-up-line"></i> Chi tiêu
                </button>
                <button type="button" class="type-btn transfer" onclick="selectType('transfer')">
                    <i class="ri-exchange-line"></i> Chuyển khoản
                </button>
            </div>
            
            <input type="hidden" name="type" id="type_input" value="{{ old('type', $transaction->type) }}">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="glass-form-group">
                    <label class="glass-label required" id="source_wallet_label">Nguồn tiền (Ví)</label>
                    <select name="wallet_id" class="glass-input" required>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('wallet_id', $transaction->wallet_id) == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6" id="destination_wallet_group" style="display: none;">
                <div class="glass-form-group">
                    <label class="glass-label required">Đến Ví</label>
                    <select name="destination_wallet_id" class="glass-input" id="destination_input">
                        <option value="">-- Chọn ví nhận tiền --</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('destination_wallet_id', $transaction->destination_wallet_id) == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="glass-form-group" id="category_group">
            <label class="glass-label required">Danh mục</label>
            <select name="category_id" class="glass-input" id="category_input">
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="glass-form-group">
            <label class="glass-label required">Số tiền (VNĐ)</label>
            <input 
                type="text" 
                class="glass-input large-amount" 
                value="{{ number_format(old('amount', round($transaction->amount)), 0, '', '.') }}" 
                required
                oninput="formatCurrency(this, 'amount')"
            >
            <input type="hidden" name="amount" id="amount" value="{{ old('amount', round($transaction->amount)) }}">
        </div>

        <div class="glass-form-group">
            <label class="glass-label required">Thời gian thực hiện</label>
            <input type="datetime-local" name="transaction_date" class="glass-input" 
                value="{{ $transaction->transaction_date->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="glass-form-group">
            <label class="glass-label">Ghi chú</label>
            <textarea name="description" class="glass-input" rows="3">{{ old('description', $transaction->description) }}</textarea>
        </div>

        <div class="glass-footer">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold">Cập nhật</button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    function formatCurrency(input, targetId) {
        let value = input.value.replace(/\D/g, '');
        if (targetId) {
            document.getElementById(targetId).value = value;
        }
        if (value !== '') {
            input.value = new Intl.NumberFormat('vi-VN').format(value);
        } else {
            input.value = '';
        }
    }

    function selectType(type) {
        document.getElementById('type_input').value = type;

        // Active state cho nút
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.classList.contains(type)) btn.classList.add('active');
        });

        // Ẩn hiện field
        const destGroup = document.getElementById('destination_wallet_group');
        const catGroup = document.getElementById('category_group');
        const sourceLabel = document.getElementById('source_wallet_label');
        
        const destInput = document.getElementById('destination_input');
        const catInput = document.getElementById('category_input');

        if (type === 'transfer') {
            // Chế độ Chuyển khoản - Hiển thị cả Đến Ví và Danh mục
            destGroup.style.display = 'block';
            catGroup.style.display = 'block';
            sourceLabel.innerText = 'Từ Ví';
            
            destInput.required = true;
            catInput.required = true; // Bắt buộc chọn danh mục cho chuyển khoản
        } else {
            // Chế độ Thu/Chi - Chỉ hiển thị Danh mục
            destGroup.style.display = 'none';
            catGroup.style.display = 'block';
            sourceLabel.innerText = 'Nguồn tiền (Ví)';
            
            destInput.required = false;
            catInput.required = true;
        }
    }

    // Chạy khi load trang để điền đúng dữ liệu cũ
    document.addEventListener('DOMContentLoaded', function() {
        const currentType = document.getElementById('type_input').value;
        selectType(currentType);
    });
</script>
@endsection

@endsection