@extends('layouts.app')

@section('title', 'Chi tiết nhóm - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="group-header" style="border-left: 4px solid {{ $group->color }}">
    <div class="group-header-left">
        <div class="group-icon-large" style="background: {{ $group->color }}20; color: {{ $group->color }}">
            <i class="{{ $group->icon }}"></i>
        </div>
        <div>
            <h1>{{ $group->name }}</h1>
            <p>{{ $group->description }}</p>
        </div>
    </div>
    <div class="group-header-actions">
        <a href="{{ route('groups.expenses.create', $group) }}" class="btn btn-primary">
            <i class="ri-add-line"></i> Thêm Chi Tiêu
        </a>
        @if($group->isAdmin(Auth::id()))
            <a href="{{ route('groups.edit', $group) }}" class="btn btn-secondary">
                <i class="ri-settings-line"></i>
            </a>
        @endif
    </div>
</div>

<div class="group-content">
    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" data-tab="overview">Tổng quan</button>
        <button class="tab-btn" data-tab="expenses">Chi tiêu</button>
        <button class="tab-btn" data-tab="balances">Số dư</button>
        <button class="tab-btn" data-tab="members">Thành viên</button>
    </div>

    <!-- Overview Tab -->
    <div class="tab-content active" id="overview">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #3B82F620; color: #3B82F6">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Tổng chi tiêu</p>
                    <h3>{{ number_format($group->expenses->where('is_settlement', false)->sum('amount')) }}đ</h3>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #10B98120; color: #10B981">
                    <i class="ri-user-line"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Thành viên</p>
                    <h3>{{ $group->activeMembers->count() }}</h3>
                </div>
            </div>

            <div class="stat-card {{ $balance && $balance->balance > 0 ? 'positive' : ($balance && $balance->balance < 0 ? 'negative' : '') }}">
                <div class="stat-icon" style="background: {{ $balance && $balance->balance >= 0 ? '#10B98120' : '#EF444420' }}; color: {{ $balance && $balance->balance >= 0 ? '#10B981' : '#EF4444' }}">
                    <i class="ri-wallet-3-line"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Số dư của bạn</p>
                    <h3>{{ $balance ? number_format(abs($balance->balance)) : '0' }}đ</h3>
                    <small>
                        @if($balance && $balance->balance > 0.01)
                            Được nợ
                        @elseif($balance && $balance->balance < -0.01)
                            Đang nợ
                        @else
                            Đã thanh toán
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <div class="section-header">
            <h2>Chi tiêu gần đây</h2>
            <a href="{{ route('groups.expenses.index', $group) }}" class="btn-link">Xem tất cả</a>
        </div>

        @if($recentExpenses->isEmpty())
            <div class="empty-state-small">
                <i class="ri-file-list-line"></i>
                <p>Chưa có chi tiêu nào</p>
            </div>
        @else
            <div class="expenses-list">
                @foreach($recentExpenses as $expense)
                    <div class="expense-item">
                        <div class="expense-icon" style="background: {{ $expense->category->color ?? '#10B981' }}20; color: {{ $expense->category->color ?? '#10B981' }}">
                            <i class="{{ $expense->category->icon ?? 'ri-shopping-cart-line' }}"></i>
                        </div>
                        <div class="expense-info">
                            <h4>{{ $expense->description }}</h4>
                            <p>
                                <span>{{ $expense->paidBy->name }}</span>
                                <span class="separator">•</span>
                                <span>{{ $expense->expense_date->format('d/m/Y') }}</span>
                            </p>
                        </div>
                        <div class="expense-amount">
                            <strong>{{ number_format($expense->amount) }}đ</strong>
                            @if($expense->splits->where('user_id', Auth::id())->first())
                                <small>Bạn: {{ number_format($expense->splits->where('user_id', Auth::id())->first()->amount) }}đ</small>
                            @endif
                        </div>
                        <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="btn-icon">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Expenses Tab -->
    <div class="tab-content" id="expenses">
        @if($allExpenses->isEmpty())
            <div class="empty-state-small">
                <i class="ri-file-list-line"></i>
                <p>Chưa có chi tiêu nào</p>
            </div>
        @else
            <div class="expenses-list">
                @foreach($allExpenses as $expense)
                    <div class="expense-item">
                        <div class="expense-icon" style="background: {{ $expense->category->color ?? '#10B981' }}20; color: {{ $expense->category->color ?? '#10B981' }}">
                            <i class="{{ $expense->category->icon ?? 'ri-shopping-cart-line' }}"></i>
                        </div>
                        <div class="expense-info">
                            <h4>{{ $expense->description }}</h4>
                            <p>
                                <span>{{ $expense->paidBy->name }}</span>
                                <span class="separator">•</span>
                                <span>{{ $expense->expense_date->format('d/m/Y') }}</span>
                            </p>
                        </div>
                        <div class="expense-amount">
                            <strong>{{ number_format($expense->amount) }}đ</strong>
                            @if($expense->splits->where('user_id', Auth::id())->first())
                                <small>Bạn: {{ number_format($expense->splits->where('user_id', Auth::id())->first()->amount) }}đ</small>
                            @endif
                        </div>
                        <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="btn-icon">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Balances Tab -->
    <div class="tab-content" id="balances">
        <div class="balances-grid">
            @foreach($balances as $userBalance)
                @php
                    $isPositive = $userBalance->balance > 0.01;
                    $isNegative = $userBalance->balance < -0.01;
                    $isSettled = abs($userBalance->balance) < 0.01;
                @endphp
                <div class="balance-card {{ $isPositive ? 'positive' : ($isNegative ? 'negative' : 'settled') }}">
                    <div class="balance-user">
                        @if($userBalance->user->avatar)
                            <img src="{{ asset('storage/' . $userBalance->user->avatar) }}" alt="{{ $userBalance->user->name }}" class="user-avatar">
                        @else
                            <div class="user-avatar-placeholder">{{ substr($userBalance->user->name, 0, 1) }}</div>
                        @endif
                        <span>{{ $userBalance->user->name }}</span>
                    </div>
                    <div class="balance-amount">
                        <h3>{{ number_format(abs($userBalance->balance)) }}đ</h3>
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

        @if(!empty($simplifiedDebts))
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--text-primary); font-size: 1.125rem;">Gợi ý thanh toán</h3>
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

        @if($settlements->isNotEmpty())
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--text-primary); font-size: 1.125rem;">Lịch sử thanh toán gần đây</h3>
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
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('groups.settlements.index', $group) }}" class="btn btn-secondary">
                <i class="ri-exchange-dollar-line"></i> Xem chi tiết & Thanh toán
            </a>
        </div>
    </div>

    <!-- Members Tab -->
    <div class="tab-content" id="members">
        <div class="members-list">
            @foreach($group->activeMembers as $member)
                <div class="member-item">
                    <div class="member-avatar">
                        @if($member->user->avatar)
                            <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}">
                        @else
                            <div class="avatar-placeholder">{{ substr($member->user->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="member-info">
                        <h4>{{ $member->user->name }}</h4>
                        <span class="badge badge-{{ $member->role === 'admin' ? 'primary' : 'secondary' }}">
                            {{ $member->role === 'admin' ? 'Quản trị' : 'Thành viên' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($group->isAdmin(Auth::id()))
            <a href="{{ route('groups.members.index', $group) }}" class="btn btn-secondary btn-full">
                <i class="ri-user-add-line"></i> Quản lý thành viên
            </a>
        @endif
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        
        // Remove active class from all tabs and contents
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding content
        btn.classList.add('active');
        document.getElementById(tab).classList.add('active');
    });
});
</script>
@endsection
