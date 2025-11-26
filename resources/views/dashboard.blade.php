@extends('layouts.app')

@section('title', 'Tổng quan - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/dashboard.css'])
@endsection

@section('content')
<!-- Stats Grid (Bento/Hero Layout) -->
<div class="stats-grid">
    <!-- Hero Card: Net Worth -->
    <div class="stat-card hero">
        <div class="stat-content">
            <div class="stat-label text-white-50">Tổng tài sản</div>
            <div class="stat-value text-white">{{ number_format($balance ?? 0, 0, ',', '.') }}₫</div>
            <div class="stat-badge mt-2">
                <i class="ri-arrow-up-line"></i>
                <span>+12.5% so với tháng trước</span>
            </div>
        </div>
        <div class="hero-pattern"></div>
    </div>

    <!-- Income -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon income">
                <i class="ri-arrow-down-circle-line"></i>
            </div>
            <div class="stat-trend positive">
                <i class="ri-arrow-up-line"></i> 5%
            </div>
        </div>
        <div class="stat-label">Tổng thu nhập</div>
        <div class="stat-value">{{ number_format($totalIncome ?? 0, 0, ',', '.') }}₫</div>
    </div>

    <!-- Expense -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon expense">
                <i class="ri-arrow-up-circle-line"></i>
            </div>
            <div class="stat-trend negative">
                <i class="ri-arrow-up-line"></i> 2%
            </div>
        </div>
        <div class="stat-label">Tổng chi tiêu</div>
        <div class="stat-value">{{ number_format($totalExpense ?? 0, 0, ',', '.') }}₫</div>
    </div>

    <!-- Transactions Count -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon balance">
                <i class="ri-file-list-3-line"></i>
            </div>
        </div>
        <div class="stat-label">Giao dịch tháng này</div>
        <div class="stat-value">{{ count($recentTransactions ?? []) }}</div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <!-- Overview Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Tổng quan tháng này</h3>
        </div>
        <div class="chart-container">
            <canvas id="overviewChart"></canvas>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Thu nhập vs Chi tiêu (6 tháng)</h3>
        </div>
        <div class="chart-container">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="transactions-card">
    <div class="transactions-header">
        <h3 class="transactions-title">Giao dịch gần đây</h3>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <span>Xem tất cả</span>
            <i class="ri-arrow-right-line"></i>
        </a>
    </div>
    
    @if(isset($recentTransactions) && count($recentTransactions) > 0)
        <div class="transaction-list">
            @foreach($recentTransactions as $transaction)
            <div class="transaction-item">
                <div class="transaction-icon {{ $transaction->type }}">
                    <i class="ri-{{ $transaction->type == 'income' ? 'arrow-down' : 'arrow-up' }}-line"></i>
                </div>
                <div class="transaction-info">
                    <div class="transaction-category">{{ $transaction->category->name ?? 'Chung' }}</div>
                    <div class="transaction-description">{{ $transaction->description ?? 'Không có mô tả' }}</div>
                </div>
                <div class="transaction-meta">
                    <div class="transaction-amount {{ $transaction->type }}">
                        {{ $transaction->type == 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', '.') }}₫
                    </div>
                    <div class="transaction-date">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="ri-inbox-line"></i>
            </div>
            <h3 class="empty-state-title">Chưa có giao dịch</h3>
            <p class="empty-state-text">Bắt đầu bằng cách tạo giao dịch đầu tiên của bạn</p>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i>
                <span>Tạo giao dịch</span>
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
    <script>
        // Truyền dữ liệu từ PHP sang JavaScript
        window.dashboardData = {
            labels: @json($labels ?? []),
            incomeData: @json($incomeData ?? []),
            expenseData: @json($expenseData ?? []),
            totalIncome: {{ $totalIncome ?? 0 }},
            totalExpense: {{ $totalExpense ?? 0 }},
            balance: {{ $balance ?? 0 }}
        };
    </script>
    @vite(['resources/js/dashboard.js'])
@endsection
