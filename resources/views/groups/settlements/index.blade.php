@extends('layouts.app')

@section('title', 'Thanh toán và số dư - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<!-- Add Settlement Button (Top Right) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
        <i class="ri-arrow-left-line"></i> Quay lại
    </a>
    <a href="{{ route('groups.settlements.create', $group) }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="ri-add-line"></i>
        <span>Ghi nhận thanh toán</span>
    </a>
</div>

<!-- Balances Overview -->
<div class="balances-overview">
    <h2>Số Dư Hiện Tại</h2>
    <div class="balances-grid">
        @foreach($balances as $balance)
            @php
                $isPositive = $balance->balance > 0.01;
                $isNegative = $balance->balance < -0.01;
                $isSettled = abs($balance->balance) < 0.01;
            @endphp
            <div class="balance-card {{ $isPositive ? 'positive' : ($isNegative ? 'negative' : 'settled') }}">
                <div class="balance-user">
                    @if($balance->user->avatar)
                        <img src="{{ asset('storage/' . $balance->user->avatar) }}" alt="{{ $balance->user->name }}" class="user-avatar">
                    @else
                        <div class="user-avatar-placeholder">{{ substr($balance->user->name, 0, 1) }}</div>
                    @endif
                    <span>{{ $balance->user->name }}</span>
                </div>
                <div class="balance-amount">
                    <h3>{{ number_format(abs($balance->balance)) }}đ</h3>
                    <span class="balance-status">
                        @if($isPositive)
                            <i class="ri-arrow-up-line"></i> Được nợ
                        @elseif($isNegative)
                            <i class="ri-arrow-down-line"></i> Đang nợ
                        @else
                            <i class="ri-checkbox-circle-line"></i> Đã thanh toán
                        @endif
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Simplified Debts -->
@if(!empty($simplifiedDebts))
    <div class="debts-section">
        <h2>Gợi Ý Thanh Toán</h2>
        <p class="section-subtitle">Các khoản thanh toán tối ưu để cân bằng số dư</p>
        
        <div class="debts-list">
            @foreach($simplifiedDebts as $debt)
                <div class="debt-item">
                    <div class="debt-from">
                        <strong>{{ $debt['from']->name }}</strong>
                    </div>
                    <div class="debt-arrow">
                        <i class="ri-arrow-right-line"></i>
                        <span class="debt-amount">{{ number_format($debt['amount']) }}đ</span>
                    </div>
                    <div class="debt-to">
                        <strong>{{ $debt['to']->name }}</strong>
                    </div>
                    @if($debt['from']->id === Auth::id() || $debt['to']->id === Auth::id() || $group->isAdmin(Auth::id()))
                        <a href="{{ route('groups.settlements.create', $group) }}?to={{ $debt['to']->id }}&amount={{ $debt['amount'] }}" class="btn btn-primary">
                            <i class="ri-check-line"></i> Ghi nhận thanh toán
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Settlement History -->
<div class="settlements-section">
    <h2>Lịch Sử Thanh Toán</h2>
    
    @if($settlements->isEmpty())
        <div class="empty-state-small">
            <i class="ri-exchange-dollar-line"></i>
            <p>Chưa có thanh toán nào</p>
        </div>
    @else
        <div class="settlements-list">
            @foreach($settlements as $settlement)
                <div class="settlement-item">
                    <div class="settlement-date">
                        {{ $settlement->settled_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="settlement-info">
                        <strong>{{ $settlement->fromUser->name }}</strong>
                        <i class="ri-arrow-right-line"></i>
                        <strong>{{ $settlement->toUser->name }}</strong>
                    </div>
                    <div class="settlement-amount">
                        {{ number_format($settlement->amount) }}đ
                    </div>
                    @if($settlement->from_user_id === Auth::id() || $group->isAdmin(Auth::id()))
                        <form action="{{ route('groups.settlements.destroy', [$group, $settlement]) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-danger" onclick="return confirm('Xóa ghi nhận thanh toán này?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
        
        {{ $settlements->links() }}
    @endif
</div>
@endsection
