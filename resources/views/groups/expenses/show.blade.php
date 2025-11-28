@extends('layouts.app')

@section('title', 'Chi tiết chi tiêu - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('groups.expenses.index', $group) }}" class="btn-back">
            <i class="ri-arrow-left-line"></i> Quay lại
        </a>
        <h1><i class="ri-file-text-line"></i> Chi Tiết Chi Tiêu</h1>
    </div>
</div>

<div class="expense-detail-card">
    <div class="expense-detail-header">
        <div class="expense-icon-large" style="background: {{ $expense->is_settlement ? '#3B82F6' : ($expense->category->color ?? '#10B981') }}20; color: {{ $expense->is_settlement ? '#3B82F6' : ($expense->category->color ?? '#10B981') }}">
            <i class="{{ $expense->is_settlement ? 'ri-exchange-dollar-line' : ($expense->category->icon ?? 'ri-shopping-cart-line') }}"></i>
        </div>
        <div class="expense-detail-info">
            <h1>{{ $expense->description }}</h1>
            <p>{{ $expense->is_settlement ? 'Thanh toán số dư' : ($expense->category->name ?? 'Không có danh mục') }}</p>
        </div>
        <div class="expense-detail-amount">
            <h2>{{ number_format($expense->amount) }}đ</h2>
            @if($expense->is_settlement)
                <span class="badge badge-success">ĐÃ THANH TOÁN</span>
            @elseif($expense->is_settled)
                <span class="badge badge-success">Đã thanh toán</span>
            @else
                <span class="badge badge-warning">Chưa thanh toán</span>
            @endif
        </div>
    </div>
    
    <div class="expense-detail-meta">
        <div class="meta-item">
            <i class="ri-user-line"></i>
            <div>
                <p class="meta-label">Người trả</p>
                <p class="meta-value">{{ $expense->paidBy->name }}</p>
            </div>
        </div>
        <div class="meta-item">
            <i class="ri-calendar-line"></i>
            <div>
                <p class="meta-label">Ngày</p>
                <p class="meta-value">{{ $expense->expense_date->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="meta-item">
            <i class="ri-pie-chart-line"></i>
            <div>
                <p class="meta-label">Phương thức chia</p>
                <p class="meta-value">
                    @switch($expense->split_method)
                        @case('equal') Chia đều @break
                        @case('percentage') Phần trăm @break
                        @case('custom') Tùy chỉnh @break
                        @case('shares') Tỷ lệ @break
                    @endswitch
                </p>
            </div>
        </div>
    </div>
    
    <div class="splits-section">
        <h3>Chi tiết chia tiền</h3>
        <div class="splits-detail-list">
            @foreach($expense->splits as $split)
                <div class="split-detail-item {{ $split->is_paid ? 'paid' : 'unpaid' }}">
                    <div class="split-detail-user">
                        @if($split->user->avatar)
                            <img src="{{ asset('storage/' . $split->user->avatar) }}" alt="{{ $split->user->name }}" class="user-avatar-small">
                        @else
                            <div class="user-avatar-small-placeholder">{{ substr($split->user->name, 0, 1) }}</div>
                        @endif
                        <strong>{{ $split->user->name }}</strong>
                    </div>
                    <div class="split-detail-info">
                        <span class="split-detail-amount">{{ number_format($split->amount) }}đ</span>
                        @if($split->percentage)
                            <span class="split-detail-percent">({{ $split->percentage }}%)</span>
                        @endif
                        @if($split->shares)
                            <span class="split-detail-shares">({{ $split->shares }} phần)</span>
                        @endif
                    </div>
                    <div class="split-detail-status">
                        @if($split->is_paid)
                            <span class="status-badge paid">
                                <i class="ri-checkbox-circle-fill"></i> Đã trả
                            </span>
                            <small>{{ $split->paid_at->format('d/m/Y H:i') }}</small>
                        @else
                            <span class="status-badge unpaid">
                                <i class="ri-time-line"></i> Chưa trả
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    @if($expense->paid_by_user_id === Auth::id() || $group->isAdmin(Auth::id()))
        <div class="expense-actions">
            <form action="{{ route('groups.expenses.destroy', [$group, $expense]) }}" method="POST" onsubmit="return confirm('Xóa chi tiêu này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="ri-delete-bin-line"></i> Xóa Chi Tiêu
                </button>
            </form>
        </div>
    @endif
</div>

<style>
.expense-detail-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.expense-detail-header {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    padding-bottom: 2rem;
    border-bottom: 2px solid #E5E7EB;
}

.expense-icon-large {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.expense-detail-info {
    flex: 1;
}

.expense-detail-info h1 {
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
}

.expense-detail-info p {
    margin: 0;
    color: #6B7280;
}

.expense-detail-amount {
    text-align: right;
}

.expense-detail-amount h2 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    color: var(--primary);
}

.expense-detail-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    padding: 2rem 0;
    border-bottom: 2px solid #E5E7EB;
}

.meta-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.meta-item i {
    font-size: 1.5rem;
    color: var(--primary);
}

.meta-label {
    margin: 0;
    font-size: 0.875rem;
    color: #6B7280;
}

.meta-value {
    margin: 0.25rem 0 0 0;
    font-weight: 600;
    color: #1F2937;
}

.splits-section {
    padding: 2rem 0;
}

.splits-section h3 {
    margin: 0 0 1.5rem 0;
}

.splits-detail-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.split-detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 8px;
    background: #F9FAFB;
}

.split-detail-item.paid {
    background: #F0FDF4;
}

.split-detail-user {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.user-avatar-small {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.user-avatar-small-placeholder {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.split-detail-info {
    display: flex;
    gap: 0.5rem;
    align-items: baseline;
}

.split-detail-amount {
    font-weight: 600;
    font-size: 1.125rem;
}

.split-detail-percent, .split-detail-shares {
    color: #6B7280;
    font-size: 0.875rem;
}

.split-detail-status {
    text-align: right;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-badge.paid {
    background: #10B981;
    color: white;
}

.status-badge.unpaid {
    background: #F59E0B;
    color: white;
}

.split-detail-status small {
    display: block;
    margin-top: 0.25rem;
    color: #6B7280;
    font-size: 0.75rem;
}

.expense-actions {
    padding-top: 2rem;
    border-top: 2px solid #E5E7EB;
    display: flex;
    justify-content: flex-end;
}
</style>
@endsection
