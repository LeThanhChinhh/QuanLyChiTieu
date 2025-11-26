@extends('layouts.admin')

@section('title', 'Tổng quan hệ thống')

@section('content')
<div class="grid grid-cols-4 gap-4 mb-4">
    <!-- Total Users -->
    <div class="glass-card">
        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="text-muted mb-3">Tổng người dùng</p>
                <h3 class="fw-bold">{{ number_format($totalUsers) }}</h3>
            </div>
            <div class="p-4 rounded-circle" style="background: rgba(59, 130, 246, 0.1); color: var(--info-color); border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                <i class="ri-user-line" style="font-size: 1.5rem;"></i>
            </div>
        </div>
        <div class="flex items-center text-success" style="font-size: 0.875rem;">
            <i class="ri-arrow-up-line" style="margin-right: 0.25rem;"></i>
            <span>+{{ $newUsersThisMonth }} trong tháng này</span>
        </div>
    </div>

    <!-- Total Transactions -->
    <div class="glass-card">
        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="text-muted mb-3">Tổng giao dịch</p>
                <h3 class="fw-bold">{{ number_format($totalTransactions) }}</h3>
            </div>
            <div class="p-4 rounded-circle" style="background: rgba(34, 197, 94, 0.1); color: var(--success-color); border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                <i class="ri-exchange-dollar-line" style="font-size: 1.5rem;"></i>
            </div>
        </div>
        <div class="text-muted" style="font-size: 0.875rem;">
            Toàn hệ thống
        </div>
    </div>

    <!-- Total Volume -->
    <div class="glass-card" style="grid-column: span 2;">
        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="text-muted mb-3">Tổng dòng tiền ghi nhận</p>
                <h3 class="fw-bold text-primary">{{ number_format($totalTransactionAmount) }} ₫</h3>
            </div>
            <div class="p-4 rounded-circle" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color); border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                <i class="ri-money-dollar-circle-line" style="font-size: 1.5rem;"></i>
            </div>
        </div>
        <div class="text-muted" style="font-size: 0.875rem;">
            Tổng giá trị tất cả giao dịch (Thu + Chi)
        </div>
    </div>
</div>

<!-- Recent Activity Placeholder -->
<div class="glass-card">
    <h4 class="mb-4">Hoạt động gần đây</h4>
    <div style="text-align: center; padding: 3rem 0; color: var(--text-muted);">
        <i class="ri-file-list-3-line" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
        <p>Chưa có dữ liệu log hoạt động</p>
    </div>
</div>
@endsection
