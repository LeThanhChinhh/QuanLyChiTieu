@extends('layouts.app')

@section('title', 'Chỉnh sửa Ví')

@section('styles')
    @vite(['resources/css/wallet.css'])
@endsection

@section('content')
<div class="wallet-form-container">
    <div class="wallet-form-header">
        <h2 class="text-primary">
            <i class="ri-settings-4-line me-2"></i>Chỉnh sửa Ví
        </h2>
    </div>
    
    <form action="{{ route('wallets.update', $wallet->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="wallet-form-group">
            <label class="wallet-form-label">Tên ví</label>
            <input type="text" name="name" class="wallet-form-input" 
                   value="{{ old('name', $wallet->name) }}" required>
        </div>

        <div class="wallet-form-group">
            <label class="wallet-form-label">Số dư thực tế (VNĐ)</label>
            <input type="number" name="balance" class="wallet-form-input large-amount" 
                   value="{{ old('balance', round($wallet->balance)) }}" required>
            <div class="alert alert-warning mt-2 small py-2" style="background: rgba(253, 230, 138, 0.3); border: 1px solid rgba(251, 191, 36, 0.5); color: #92400E; border-radius: 8px; padding: 8px 12px; display: flex; align-items: center; gap: 8px;">
                <i class="ri-alert-line"></i> 
                Lưu ý: Thay đổi số dư ở đây là điều chỉnh thủ công (không tạo giao dịch).
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="wallet-form-group">
                    <label class="wallet-form-label">Màu sắc</label>
                    <input type="color" name="color" class="wallet-form-input" 
                           value="{{ old('color', $wallet->color) }}" title="Chọn màu ví" style="height: 48px; padding: 4px;">
                </div>
            </div>
            <div class="col-md-8">
                <div class="wallet-form-group">
                    <label class="wallet-form-label">Biểu tượng</label>
                    <select name="icon" class="wallet-form-input font-family-icon">
                        @php
                            $icons = [
                                'ri-wallet-3-line' => '💰 Ví thường',
                                'ri-bank-card-line' => '💳 Thẻ ngân hàng',
                                'ri-bank-line' => '🏛️ Ngân hàng',
                                'ri-safe-2-line' => '🔒 Két sắt / Tiết kiệm',
                                'ri-hand-coin-line' => '🤲 Tiền mặt',
                                'ri-bit-coin-line' => '🪙 Crypto / Đầu tư',
                                'ri-smartphone-line' => '📱 Ví điện tử'
                            ];
                        @endphp
                        @foreach($icons as $value => $label)
                            <option value="{{ $value }}" {{ $wallet->icon == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="wallet-form-actions">
            <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold">
                <i class="ri-save-line"></i> Cập nhật
            </button>
        </div>
    </form>
    
    <hr class="my-4" style="border-color: rgba(0,0,0,0.1);">
    
    <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" class="text-center">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger text-decoration-none" 
                onclick="return confirm('CẢNH BÁO: Xóa ví này sẽ xóa TOÀN BỘ lịch sử giao dịch liên quan đến nó. Bạn có chắc chắn không?')">
            <i class="ri-delete-bin-line"></i> Xóa ví này vĩnh viễn
        </button>
    </form>
</div>
@endsection