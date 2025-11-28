@extends('layouts.app')

@section('title', 'Chi tiêu nhóm - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<!-- Add Expense Button (Top Right) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
        <i class="ri-arrow-left-line"></i> Quay lại
    </a>
    <a href="{{ route('groups.expenses.create', $group) }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="ri-add-line"></i>
        <span>Thêm chi tiêu</span>
    </a>
</div>

@if($expenses->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="ri-file-list-line"></i>
        </div>
        <h3 class="empty-state-title">Chưa có chi tiêu nào</h3>
        <p class="empty-state-text">Thêm chi tiêu đầu tiên cho nhóm</p>
    </div>
@else
    <div class="expenses-list-page">
        @foreach($expenses as $expense)
            <div class="expense-card">
                <div class="expense-card-header">
                    <div class="expense-icon" style="background: {{ $expense->category->color ?? '#10B981' }}20; color: {{ $expense->category->color ?? '#10B981' }}">
                        <i class="{{ $expense->category->icon ?? 'ri-shopping-cart-line' }}"></i>
                    </div>
                    <div class="expense-card-info">
                        <h3>{{ $expense->description }}</h3>
                        <p>
                            <strong>{{ $expense->paidBy->name }}</strong> đã trả
                            <span class="separator">•</span>
                            {{ $expense->expense_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="expense-card-amount">
                        <h3>{{ number_format($expense->amount) }}đ</h3>
                        @if($expense->is_settled)
                            <span class="badge badge-success">Đã thanh toán</span>
                        @else
                            <span class="badge badge-warning">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>
                
                <div class="expense-splits">
                    <h4>Chia cho:</h4>
                    <div class="splits-grid">
                        @foreach($expense->splits as $split)
                            <div class="split-item {{ $split->is_paid ? 'paid' : 'unpaid' }}">
                                <span class="split-user">{{ $split->user->name }}</span>
                                <span class="split-amount">{{ number_format($split->amount) }}đ</span>
                                @if($split->is_paid)
                                    <i class="ri-checkbox-circle-fill" style="color: #10B981;"></i>
                                @else
                                    <i class="ri-time-line" style="color: #F59E0B;"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="expense-card-footer">
                    <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="btn btn-secondary btn-sm">
                        Chi Tiết
                    </a>
                    @if($expense->paid_by_user_id === Auth::id() || $group->isAdmin(Auth::id()))
                        <form action="{{ route('groups.expenses.destroy', [$group, $expense]) }}" method="POST" onsubmit="return confirm('Xóa chi tiêu này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="ri-delete-bin-line"></i> Xóa
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $expenses->links() }}
    </div>
@endif

<style>
.expenses-list-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.expense-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.expense-card-header {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.expense-card-info {
    flex: 1;
}

.expense-card-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
}

.expense-card-info p {
    margin: 0;
    color: #6B7280;
    font-size: 0.875rem;
}

.expense-card-amount {
    text-align: right;
}

.expense-card-amount h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    color: var(--primary);
}

.expense-splits {
    padding: 1rem 0;
    border-top: 1px solid #E5E7EB;
}

.expense-splits h4 {
    margin: 0 0 0.75rem 0;
    font-size: 0.875rem;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.splits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.5rem;
}

.split-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: #F9FAFB;
    border-radius: 6px;
}

.split-item.paid {
    background: #F0FDF4;
}

.split-user {
    flex: 1;
    font-size: 0.875rem;
}

.split-amount {
    font-weight: 600;
    font-size: 0.875rem;
}

.expense-card-footer {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    padding-top: 1rem;
    border-top: 1px solid #E5E7EB;
}
</style>
@endsection
