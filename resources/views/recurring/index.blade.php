@extends('layouts.app')

@section('title', 'Giao dịch định kỳ')

@section('content')
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
            <i class="ri-checkbox-circle-line"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($recurringTransactions->count() > 0)
        <div class="recurring-grid">
            <!-- Add New Card -->
            <a href="{{ route('recurring.create') }}" class="add-recurring-card">
                <div class="add-recurring-icon">
                    <i class="ri-add-line"></i>
                </div>
                <h3 class="add-recurring-text">Thêm mới</h3>
                <p class="add-recurring-hint">Tự động hóa giao dịch của bạn</p>
            </a>

            @foreach($recurringTransactions as $recurring)
                <div class="glass-card">
                    @php
                        $frequencies = [
                            'daily' => 'Hàng ngày',
                            'weekly' => 'Hàng tuần',
                            'monthly' => 'Hàng tháng',
                            'yearly' => 'Hàng năm'
                        ];
                    @endphp
                    
                    <!-- Header: Icon + Name -->
                    <div class="card-header-custom">
                        <div class="card-icon-wrapper">
                            <div class="icon-box {{ $recurring->type == 'income' ? 'bg-income' : ($recurring->type == 'expense' ? 'bg-expense' : 'bg-transfer') }}">
                                <i class="ri-{{ $recurring->type == 'income' ? 'arrow-up-circle-line' : ($recurring->type == 'expense' ? 'arrow-down-circle-line' : 'exchange-line') }}"></i>
                            </div>
                            <div class="transaction-info">
                                <h3>{{ $recurring->description ?? 'Không tên' }}</h3>
                                <span class="badge">{{ $frequencies[$recurring->frequency] ?? ucfirst($recurring->frequency) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Body: Amount + Wallet -->
                    <div class="card-body-custom">
                        <div class="amount">{{ number_format($recurring->amount) }} ₫</div>
                        <div class="wallet-info">
                            <i class="ri-wallet-3-line"></i>
                            <span>{{ $recurring->wallet->name }}</span>
                            @if($recurring->type == 'transfer' && $recurring->destinationWallet)
                                <i class="ri-arrow-right-line"></i>
                                <span>{{ $recurring->destinationWallet->name }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer: Next Run + Status -->
                    <div class="card-footer-custom">
                        <div class="next-run">
                            <label>Lần chạy tới</label>
                            <span>
                                <i class="ri-calendar-event-line"></i>
                                {{ $recurring->next_run_date->format('d/m/Y') }}
                            </span>
                        </div>
                        
                        <form action="{{ route('recurring.toggle', $recurring) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label class="switch">
                                <input type="checkbox" onchange="this.form.submit()" {{ $recurring->status == 'active' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </form>
                    </div>
                    
                    <!-- Actions: At bottom -->
                    <div class="recurring-card-actions">
                        <a href="{{ route('recurring.edit', $recurring) }}" class="btn btn-icon btn-secondary" title="Sửa">
                            <i class="ri-edit-line"></i>
                        </a>
                        <form action="{{ route('recurring.destroy', $recurring) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
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
                <i class="ri-calendar-check-line" style="font-size: 3rem; color: #10B981;"></i>
            </div>
            <h3 class="empty-state-title">Chưa có giao dịch định kỳ</h3>
            <p class="empty-state-subtitle">Tạo giao dịch định kỳ để tự động hóa việc ghi chép chi tiêu của bạn</p>
            <a href="{{ route('recurring.create') }}" class="btn btn-primary">
                <i class="ri-add-line"></i>
                <span>Tạo giao dịch đầu tiên</span>
            </a>
        </div>
    @endif
@endsection

@section('styles')
    @vite(['resources/css/recurring.css'])
@endsection