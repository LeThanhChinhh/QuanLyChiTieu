@extends('layouts.app')

@section('title', 'Giao dịch - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/transactions.css'])
@endsection

@section('content')
<!-- Add Transaction Button (Top Right) -->
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('transactions.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="ri-add-line"></i>
        <span>Thêm giao dịch</span>
    </a>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success">
        <i class="ri-checkbox-circle-line"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        <i class="ri-error-warning-line"></i>
        <div>{{ session('warning') }}</div>
    </div>
@endif

<!-- Filters Bar -->
<div class="filters-bar">
    <form action="{{ route('transactions.index') }}" method="GET" class="filters-form">
        <div class="segmented-control">
            <button type="submit" name="type" value="all" 
                    class="segmented-item {{ request('type') === 'all' || !request('type') ? 'active' : '' }}">
                Tất cả
            </button>
            <button type="submit" name="type" value="income" 
                    class="segmented-item {{ request('type') === 'income' ? 'active' : '' }}">
                Thu nhập
            </button>
            <button type="submit" name="type" value="expense" 
                    class="segmented-item {{ request('type') === 'expense' ? 'active' : '' }}">
                Chi tiêu
            </button>
            <button type="submit" name="type" value="transfer" 
                    class="segmented-item {{ request('type') === 'transfer' ? 'active' : '' }}">
                Chuyển khoản
            </button>
        </div>
        
        <div class="filters-right">
            <div class="filter-input-wrapper">
                <i class="ri-calendar-line filter-icon"></i>
                <select name="time_range" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ request('time_range') === 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                    <option value="today" {{ request('time_range') === 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="week" {{ request('time_range') === 'week' ? 'selected' : '' }}>Tuần này</option>
                    <option value="month" {{ request('time_range') === 'month' ? 'selected' : '' }}>Tháng này</option>
                    <option value="year" {{ request('time_range') === 'year' ? 'selected' : '' }}>Năm nay</option>
                </select>
            </div>
            
            <div class="filter-input-wrapper">
                <i class="ri-folder-line filter-icon"></i>
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="all">Tất cả danh mục</option>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            @if(request()->hasAny(['type', 'time_range', 'category', 'search']))
                <a href="{{ route('transactions.index') }}" class="btn-icon-only btn-secondary" title="Đặt lại">
                    <i class="ri-refresh-line"></i>
                </a>
            @endif
            <button type="submit" class="btn-icon-only btn-primary" title="Tìm kiếm">
                <i class="ri-search-line"></i>
            </button>
        </div>
    </form>
</div>

<!-- Transactions Table -->
@if(isset($transactions) && count($transactions) > 0)
    <div class="transactions-table-card">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Danh mục</th>
                    <th>Mô tả</th>
                    <th>Loại</th>
                    <th>Số tiền</th>
                    <th style="text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
    @foreach($transactions as $transaction)
    <tr>
        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>

        <td>
            @if($transaction->type == 'transfer')
                <div class="transaction-category-cell">
                    <div class="category-icon-small" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                        <i class="ri-exchange-line"></i> </div>
                    <div style="display: flex; flex-direction: column; line-height: 1.2;">
                        <span style="font-weight: 600; color: #3B82F6;">Chuyển khoản</span>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            Đến: {{ $transaction->destinationWallet->name ?? 'Ví đã xóa' }}
                        </small>
                    </div>
                </div>
            @else
                <div class="transaction-category-cell">
                    <div class="category-icon-small">
                        <i class="{{ $transaction->category->icon ?? 'ri-folder-line' }}"></i>
                    </div>
                    <span>{{ $transaction->category->name ?? 'Chung' }}</span>
                </div>
            @endif
        </td>

        <td>{{ $transaction->description ?? '-' }}</td>

        <td>
            @php
                $typeClass = match($transaction->type) {
                    'income' => 'income',
                    'expense' => 'expense',
                    'transfer' => 'transfer', // Style riêng cho transfer
                    default => ''
                };
                $typeLabel = match($transaction->type) {
                    'income' => 'Thu nhập',
                    'expense' => 'Chi tiêu',
                    'transfer' => 'Chuyển tiền',
                    default => 'Khác'
                };
                $icon = match($transaction->type) {
                    'income' => 'arrow-down',
                    'expense' => 'arrow-up',
                    'transfer' => 'exchange', // Icon mũi tên 2 chiều
                    default => 'question'
                };
            @endphp
            <span class="transaction-type {{ $typeClass }}">
                <i class="ri-{{ $icon }}-line"></i> {{ $typeLabel }}
            </span>
        </td>

        <td class="transaction-amount-cell {{ $transaction->type }}">
            @if($transaction->type == 'expense') - @endif
            @if($transaction->type == 'income') + @endif
            {{ number_format($transaction->amount, 0, ',', '.') }}₫
        </td>

        <td>
            <div class="transaction-actions">
                <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-icon btn-secondary" title="Sửa">
                    <i class="ri-edit-line"></i>
                </a>
                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon btn-danger" title="Xóa"
                            onclick="return confirm('Bạn có chắc muốn xóa giao dịch này?')">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
        </table>
        
        <!-- Pagination -->
        <div class="custom-pagination">
            {{ $transactions->appends(request()->query())->links('custom.pagination') }}
        </div>
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
        <h3 class="empty-state-title">Chưa có giao dịch</h3>
        <p class="empty-state-subtitle">
            @if(request()->hasAny(['type', 'time_range', 'category', 'search']))
                Không tìm thấy giao dịch nào phù hợp với bộ lọc
            @else
                Bắt đầu bằng cách tạo giao dịch đầu tiên của bạn
            @endif
        </p>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="ri-add-line"></i>
            <span>Tạo giao dịch</span>
        </a>
    </div>
@endif
@endsection
