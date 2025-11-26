@extends('layouts.app')

@section('title', 'Ngân sách - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/budgets.css'])
@endsection

@section('content')
<!-- Budget Stats -->
<div class="budget-stats">
    <div class="stat-card">
        <div class="stat-icon-wrapper balance">
            <i class="ri-wallet-3-line"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($totalBudgets ?? 0, 0, ',', '.') }}₫</div>
            <div class="stat-label">Tổng ngân sách</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper income">
            <i class="ri-checkbox-circle-line"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $onTrack ?? 0 }}</div>
            <div class="stat-label">Đang ổn định</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper expense">
            <i class="ri-alarm-warning-line"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $overspent ?? 0 }}</div>
            <div class="stat-label">Vượt ngân sách</div>
        </div>
    </div>
</div>

<!-- Budgets Grid -->
@if(isset($budgets) && count($budgets) > 0)
    <div class="budgets-grid">
        <!-- Add Budget Card -->
        <a href="{{ route('budgets.create') }}" class="add-budget-card">
            <div class="add-budget-icon">
                <i class="ri-add-line"></i>
            </div>
            <h3 class="add-budget-text">Thêm ngân sách mới</h3>
            <p class="add-budget-hint">Đặt giới hạn chi tiêu cho danh mục</p>
        </a>

        @foreach($budgets as $budget)
            @php
                $percentage = $budget->amount > 0 ? ($budget->spent / $budget->amount) * 100 : 0;
                $status = $percentage < 70 ? 'on-track' : ($percentage < 100 ? 'warning' : 'over-budget');
                $statusText = $percentage < 70 ? 'Ổn định' : ($percentage < 100 ? 'Cảnh báo' : 'Vượt ngân sách');
                $remaining = $budget->amount - $budget->spent;
            @endphp
            
            <div class="budget-card">
                <!-- Budget Header -->
                <div class="budget-header">
                    <div class="budget-info">
                        <div class="budget-category">
                            <div class="budget-icon">
                                <i class="{{ $budget->category->icon ?? 'ri-price-tag-3-line' }}"></i>
                            </div>
                            <div>
                                <h3 class="budget-name">{{ $budget->category->name ?? $budget->name }}</h3>
                                <p class="budget-period">Tháng {{ date('m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="budget-actions">
                        <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-icon btn-secondary" title="Sửa">
                            <i class="ri-edit-line"></i>
                        </a>
                        <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-danger" title="Xóa"
                                    onclick="return confirm('Bạn có chắc muốn xóa ngân sách này?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Budget Amount -->
                <div class="budget-amounts">
                    <div class="budget-spent">
                        <span class="budget-spent-label">Đã chi tiêu</span>
                        <div class="budget-values-wrapper">
                            <span class="budget-spent-value">{{ number_format($budget->spent, 0, ',', '.') }}₫</span>
                            <span class="budget-total">/ {{ number_format($budget->amount, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="budget-progress-section">
                    <div class="budget-progress-header">
                        <span class="budget-progress-label">Tiến độ {{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar {{ $status }}" 
                             style="width: {{ min($percentage, 100) }}%">
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div style="margin-bottom: var(--spacing-md);">
                    <span class="budget-status {{ $status }}">
                        <i class="ri-{{ $status === 'on-track' ? 'checkbox-circle' : ($status === 'warning' ? 'error-warning' : 'close-circle') }}-fill"></i>
                        {{ $statusText }}
                    </span>
                </div>

                <!-- Remaining Amount -->
                <div class="budget-remaining">
                    <span class="budget-remaining-label">Còn lại</span>
                    <span class="budget-remaining-value {{ $remaining < 0 ? 'negative' : '' }}">
                        {{ $remaining >= 0 ? '' : '-' }}{{ number_format(abs($remaining), 0, ',', '.') }}₫
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="empty-state-container">
        <div style="
    width: 100px; 
    height: 100px; 
    background: rgba(255, 255, 255, 0.4); 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    margin-bottom: 1.5rem; 
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1); 
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.18);
">
    <i class="ri-folder-open-line" style="font-size: 3rem; color: #10B981;"></i>
</div>
        <h3 class="empty-state-title">Chưa có ngân sách</h3>
        <p class="empty-state-subtitle">Tạo ngân sách để theo dõi và kiểm soát chi tiêu của bạn</p>
        <a href="{{ route('budgets.create') }}" class="btn btn-primary">
            <i class="ri-add-line"></i>
            <span>Tạo ngân sách đầu tiên</span>
        </a>
    </div>
@endif
@endsection
