@extends('layouts.app')

@section('title', 'Thêm chi tiêu - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="form-container-centered" style="max-width: 800px;">
    <div class="form-card-modern">
        <div class="form-header-modern">
            <h2 class="text-primary">
                <i class="ri-add-line me-2"></i>Thêm Chi Tiêu
            </h2>
        </div>

        <form action="{{ route('groups.expenses.store', $group) }}" method="POST" id="expenseForm">
            @csrf

            <div class="form-group-modern">
                <label class="form-label-modern">Số tiền <span class="text-danger">*</span></label>
                <input type="text" id="amount_display" class="form-input-modern form-input-large" 
                       placeholder="0" required oninput="formatCurrency(this, 'amount')">
                <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
                @error('amount')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">Mô tả</label>
                <input type="text" name="description" class="form-input-modern" 
                       value="{{ old('description') }}" placeholder="VD: Tiền khách sạn, Ăn tối BBQ...">
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Người trả <span class="text-danger">*</span></label>
                        <select name="paid_by_user_id" class="form-input-modern" required>
                            @foreach($group->activeMembers as $member)
                                <option value="{{ $member->user_id }}" {{ Auth::id() == $member->user_id ? 'selected' : '' }}>
                                    {{ $member->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Danh mục</label>
                        <select name="category_id" class="form-input-modern">
                            <option value="">Không chọn</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Ngày chi tiêu <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-input-modern" 
                               value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">Phương thức chia <span class="text-danger">*</span></label>
                <div class="split-method-buttons">
                    <button type="button" class="split-method-btn active" data-method="equal">
                        <i class="ri-equal-line"></i>
                        <span>Chia đều</span>
                    </button>
                    <button type="button" class="split-method-btn" data-method="percentage">
                        <i class="ri-percent-line"></i>
                        <span>Phần trăm</span>
                    </button>
                    <button type="button" class="split-method-btn" data-method="custom">
                        <i class="ri-edit-line"></i>
                        <span>Tùy chỉnh</span>
                    </button>
                    <button type="button" class="split-method-btn" data-method="shares">
                        <i class="ri-pie-chart-line"></i>
                        <span>Tỷ lệ</span>
                    </button>
                </div>
                <input type="hidden" name="split_method" id="split_method" value="equal">
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">Chia cho ai? <span class="text-danger">*</span></label>
                <div class="members-checklist" id="membersList">
                    @foreach($group->activeMembers as $index => $member)
                        <div class="member-check-item">
                            <input type="checkbox" 
                                   name="members[{{ $index }}][user_id]" 
                                   value="{{ $member->user_id }}" 
                                   id="member_{{ $member->user_id }}"
                                   class="member-checkbox"
                                   {{ Auth::id() == $member->user_id ? 'checked' : '' }}>
                            <label for="member_{{ $member->user_id }}">
                                <span class="member-name">{{ $member->user->name }}</span>
                                <span class="member-amount" id="amount_display_{{ $member->user_id }}"></span>
                            </label>
                            
                            <!-- Hidden inputs for different split methods -->
                            <input type="hidden" name="members[{{ $index }}][amount]" id="amount_{{ $member->user_id }}" value="0" class="member-amount-input">
                            <input type="hidden" name="members[{{ $index }}][percentage]" id="percentage_{{ $member->user_id }}" value="0" class="member-percentage-input">
                            <input type="hidden" name="members[{{ $index }}][shares]" id="shares_{{ $member->user_id }}" value="1" class="member-shares-input">
                        
                            <!-- Input fields (hidden by default, shown based on split method) -->
                            <input type="number" class="split-input percentage-input" style="display:none;" placeholder="%" min="0" max="100" step="1" data-member="{{ $member->user_id }}">
                            <input type="text" class="split-input amount-input" style="display:none;" placeholder="Số tiền" data-member="{{ $member->user_id }}" oninput="formatSplitAmount(this, {{ $member->user_id }})">
                            <input type="number" class="split-input shares-input" style="display:none;" placeholder="Phần" min="1" value="1" step="1" data-member="{{ $member->user_id }}">
                        </div>
                @endforeach
            </div>

            <div class="split-summary" id="splitSummary">
                <p>Mỗi người trả: <strong id="perPerson">0đ</strong></p>
            </div>

            <div class="form-actions-modern">
                <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
                    <i class="ri-close-line"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-check-line"></i> Thêm Chi Tiêu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const amountInput = document.getElementById('amount');
const amountDisplayInput = document.getElementById('amount_display');
const splitMethodInput = document.getElementById('split_method');
const memberCheckboxes = document.querySelectorAll('.member-checkbox');
const splitMethodBtns = document.querySelectorAll('.split-method-btn');

// Switch split method
splitMethodBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        splitMethodBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const method = this.dataset.method;
        splitMethodInput.value = method;
        
        // Hide all split inputs
        document.querySelectorAll('.split-input').forEach(input => input.style.display = 'none');
        
        // Show relevant inputs
        if (method === 'percentage') {
            document.querySelectorAll('.percentage-input').forEach(input => {
                input.style.display = 'block';
                if (!input.value) input.value = '';
            });
        } else if (method === 'custom') {
            document.querySelectorAll('.amount-input').forEach(input => {
                input.style.display = 'block';
                if (!input.value) input.value = '';
            });
        } else if (method === 'shares') {
            document.querySelectorAll('.shares-input').forEach(input => {
                input.style.display = 'block';
                if (!input.value) input.value = '1';
            });
        }
        
        calculateSplits();
    });
});

// Calculate splits
function calculateSplits() {
    const amount = parseFloat(amountInput.value) || 0;
    const method = splitMethodInput.value;
    const checkedMembers = Array.from(memberCheckboxes).filter(cb => cb.checked);
    
    if (amount === 0 || checkedMembers.length === 0) {
        checkedMembers.forEach(cb => {
            document.getElementById(`amount_display_${cb.value}`).textContent = '';
        });
        document.getElementById('perPerson').textContent = '0đ';
        return;
    }
    
    if (method === 'equal') {
        const perPerson = amount / checkedMembers.length;
        checkedMembers.forEach(cb => {
            const memberId = cb.value;
            document.getElementById(`amount_${memberId}`).value = perPerson.toFixed(2);
            document.getElementById(`amount_display_${memberId}`).textContent = `${Math.round(perPerson).toLocaleString()}đ`;
        });
        document.getElementById('perPerson').textContent = `${Math.round(perPerson).toLocaleString()}đ`;
    } else if (method === 'percentage') {
        checkedMembers.forEach(cb => {
            const memberId = cb.value;
            const percentInput = document.querySelector(`.percentage-input[data-member="${memberId}"]`);
            const percent = parseFloat(percentInput.value) || 0;
            const memberAmount = (amount * percent) / 100;
            document.getElementById(`amount_${memberId}`).value = memberAmount.toFixed(2);
            document.getElementById(`percentage_${memberId}`).value = percent;
            document.getElementById(`amount_display_${memberId}`).textContent = `${Math.round(memberAmount).toLocaleString()}đ (${percent}%)`;
        });
    } else if (method === 'custom') {
        checkedMembers.forEach(cb => {
            const memberId = cb.value;
            const amountInputField = document.querySelector(`.amount-input[data-member="${memberId}"]`);
            // Get value from hidden input (already cleaned by formatSplitAmount)
            const memberAmount = parseFloat(document.getElementById(`amount_${memberId}`).value) || 0;
            document.getElementById(`amount_display_${memberId}`).textContent = `${Math.round(memberAmount).toLocaleString()}đ`;
        });
    } else if (method === 'shares') {
        const totalShares = checkedMembers.reduce((sum, cb) => {
            const sharesInput = document.querySelector(`.shares-input[data-member="${cb.value}"]`);
            return sum + (parseInt(sharesInput.value) || 1);
        }, 0);
        
        checkedMembers.forEach(cb => {
            const memberId = cb.value;
            const sharesInput = document.querySelector(`.shares-input[data-member="${memberId}"]`);
            const shares = parseInt(sharesInput.value) || 1;
            const memberAmount = (amount * shares) / totalShares;
            document.getElementById(`amount_${memberId}`).value = memberAmount.toFixed(2);
            document.getElementById(`shares_${memberId}`).value = shares;
            document.getElementById(`amount_display_${memberId}`).textContent = `${Math.round(memberAmount).toLocaleString()}đ (${shares} phần)`;
        });
    }
}

// Event listeners
amountInput.addEventListener('input', calculateSplits);
memberCheckboxes.forEach(cb => cb.addEventListener('change', calculateSplits));
document.querySelectorAll('.split-input').forEach(input => {
    input.addEventListener('input', calculateSplits);
});

// Initial calculation
calculateSplits();

// Currency formatting function
function formatCurrency(input, targetId) {
    // Xóa mọi ký tự không phải số
    let value = input.value.replace(/\D/g, '');
    
    // Cập nhật giá trị cho input hidden (để gửi về server)
    if (targetId) {
        document.getElementById(targetId).value = value;
    }
    
    // Format hiển thị (thêm dấu chấm)
    if (value !== '') {
        input.value = new Intl.NumberFormat('vi-VN').format(value);
    } else {
        input.value = '';
    }
    
    // Trigger recalculation
    if (targetId === 'amount') {
        calculateSplits();
    }
}

// Format split amount input (for custom method)
function formatSplitAmount(input, memberId) {
    let value = input.value.replace(/\D/g, '');
    
    // Update hidden input
    const hiddenInput = document.getElementById(`amount_${memberId}`);
    if (hiddenInput) {
        hiddenInput.value = value;
    }
    
    // Format display
    if (value !== '') {
        input.value = new Intl.NumberFormat('vi-VN').format(value);
    } else {
        input.value = '';
    }
    
    // Trigger recalculation
    calculateSplits();
}
</script>
@endsection
