@extends('layouts.app')
@section('title', 'Ví của tôi')

@section('styles')
    @vite(['resources/css/wallet.css'])
@endsection

@section('content')
<div class="wallets-grid">
    <!-- Add Wallet Card -->
    <a href="{{ route('wallets.create') }}" class="add-wallet-card">
        <div class="add-wallet-icon">
            <i class="ri-add-line"></i>
        </div>
        <h3 class="add-wallet-text">Thêm ví mới</h3>
        <p class="add-wallet-hint">Quản lý dòng tiền của bạn</p>
    </a>

    @foreach($wallets as $wallet)
    <div class="wallet-card">
        <div class="wallet-header">
            <div class="wallet-info">
                <div class="wallet-icon" style="background: {{ $wallet->color }}; box-shadow: 0 4px 10px {{ $wallet->color }}40;">
                    <i class="{{ $wallet->icon }}"></i>
                </div>
                <div class="wallet-details">
                    <h3>{{ $wallet->name }}</h3>
                    <div class="wallet-type">Ví cá nhân</div>
                </div>
            </div>
        </div>

        <div class="wallet-balance-section">
            <div class="wallet-balance-label">Số dư hiện tại</div>
            <div class="wallet-balance {{ $wallet->balance >= 0 ? 'positive' : 'negative' }}">
                {{ number_format($wallet->balance, 0, ',', '.') }}₫
            </div>
        </div>
        
        <div class="wallet-actions">
            <a href="{{ route('wallets.edit', $wallet->id) }}" class="btn btn-icon btn-secondary" title="Chỉnh sửa">
                <i class="ri-pencil-fill"></i>
            </a>
            
            <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" class="d-inline" onsubmit="return confirm('CẢNH BÁO: Xóa ví này sẽ xóa tất cả giao dịch liên quan. Bạn có chắc không?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-icon btn-danger" title="Xóa ví">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($wallets->isEmpty())
<div class="wallets-empty">
    <div class="wallets-empty-icon">
        <i class="ri-wallet-3-line"></i>
    </div>
    <h3>Chưa có ví nào</h3>
    <p>Tạo ví đầu tiên để bắt đầu quản lý dòng tiền</p>
</div>
@endif

@endsection