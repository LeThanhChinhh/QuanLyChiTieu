@extends('layouts.app')

@section('title', 'Ghi nhận thanh toán - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('groups.settlements.index', $group) }}" class="btn-back">
            <i class="ri-arrow-left-line"></i> Quay lại
        </a>
        <h1><i class="ri-add-line"></i> Ghi Nhận Thanh Toán</h1>
    </div>
</div>

<div class="form-container-centered">
    <form action="{{ route('groups.settlements.store', $group) }}" method="POST" class="form-card-modern">
        @csrf

        <div class="form-header-modern">
            <i class="ri-exchange-dollar-line"></i>
            <h2>Thông tin thanh toán</h2>
        </div>

        <div class="form-group-modern">
            <label for="to_user_id" class="form-label-modern">Thanh toán cho ai? <span class="required">*</span></label>
            <select id="to_user_id" name="to_user_id" class="form-input-modern" required>
                <option value="">Chọn người nhận</option>
                @foreach($group->activeMembers as $member)
                    @if($member->user_id !== Auth::id())
                        <option value="{{ $member->user_id }}" {{ request('to') == $member->user_id ? 'selected' : '' }}>
                            {{ $member->user->name }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('to_user_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group-modern">
            <label for="amount" class="form-label-modern">Số tiền <span class="required">*</span></label>
            <input type="text" id="amount" name="amount" class="form-input-modern form-input-large" 
                   value="{{ request('amount', old('amount')) }}" 
                   placeholder="0" required>
            <input type="hidden" id="amount_raw" name="amount_raw">
            @error('amount')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group-modern">
            <label for="notes" class="form-label-modern">Ghi chú</label>
            <textarea id="notes" name="notes" class="form-input-modern" rows="3" placeholder="Thêm ghi chú (không bắt buộc)">{{ old('notes') }}</textarea>
            @error('notes')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="alert alert-info">
            <i class="ri-information-line"></i>
            <p>Ghi nhận rằng bạn đã thanh toán cho người được chọn. Số dư sẽ được cập nhật tự động.</p>
        </div>

        <div class="form-actions-modern">
            <a href="{{ route('groups.settlements.index', $group) }}" class="btn btn-secondary">
                <i class="ri-close-line"></i> Hủy
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="ri-check-line"></i> Ghi Nhận Thanh Toán
            </button>
        </div>
    </form>
</div>

<script>
function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) {
        let formatted = new Intl.NumberFormat('vi-VN').format(value);
        input.value = formatted;
        document.getElementById('amount_raw').value = value;
    } else {
        input.value = '';
        document.getElementById('amount_raw').value = '';
    }
}

const amountInput = document.getElementById('amount');
if (amountInput) {
    // Format initial value if exists
    if (amountInput.value) {
        formatCurrency(amountInput);
    }
    
    amountInput.addEventListener('input', function() {
        formatCurrency(this);
    });
    
    // Before submit, ensure raw value is set
    amountInput.closest('form').addEventListener('submit', function(e) {
        const rawValue = document.getElementById('amount_raw').value;
        if (!rawValue) {
            e.preventDefault();
            alert('Vui lòng nhập số tiền');
        }
    });
}
</script>
@endsection
