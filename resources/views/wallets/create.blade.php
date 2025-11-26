@extends('layouts.app')

@section('title', 'Thêm Ví Mới')

@section('styles')
    @vite(['resources/css/wallet.css'])
@endsection

@section('content')
<div class="wallet-form-container">
    <div class="wallet-form-header">
        <h2 class="text-primary">
            <i class="ri-wallet-3-line me-2"></i>Thêm Ví Mới
        </h2>
    </div>
    
    <form action="{{ route('wallets.store') }}" method="POST">
        @csrf
        
        <div class="wallet-form-group">
            <label class="wallet-form-label">Tên ví</label>
            <input type="text" name="name" class="wallet-form-input" 
                   placeholder="Ví dụ: Tiền mặt, Vietcombank..." required autofocus>
        </div>

        <div class="wallet-form-group">
            <label class="wallet-form-label">Số dư hiện tại (VNĐ)</label>
            <input type="text" class="wallet-form-input large-amount" 
                   placeholder="0" required oninput="formatCurrency(this, 'balance')">
            <input type="hidden" name="balance" id="balance">
            <div class="form-text">Nhập số tiền thực tế đang có trong ví này.</div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="wallet-form-group">
                    <label class="wallet-form-label">Màu sắc</label>
                    <input type="color" name="color" class="wallet-form-input" 
                           value="#3B82F6" title="Chọn màu ví" style="height: 48px; padding: 4px;">
                </div>
            </div>
            <div class="col-md-8">
                <div class="wallet-form-group">
                    <label class="wallet-form-label">Biểu tượng</label>
                    <select name="icon" class="wallet-form-input font-family-icon">
                        <option value="ri-wallet-3-line">💰 Ví thường</option>
                        <option value="ri-bank-card-line">💳 Thẻ ngân hàng</option>
                        <option value="ri-bank-line">🏛️ Ngân hàng</option>
                        <option value="ri-safe-2-line">🔒 Két sắt / Tiết kiệm</option>
                        <option value="ri-hand-coin-line">🤲 Tiền mặt</option>
                        <option value="ri-bit-coin-line">🪙 Crypto / Đầu tư</option>
                        <option value="ri-smartphone-line">📱 Ví điện tử (Momo, ZaloPay)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="wallet-form-actions">
            <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary fw-bold">
                <i class="ri-save-line"></i> Lưu Ví
            </button>
        </div>
    </form>
</div>

<script>
    function formatCurrency(input, targetId) {
        // Xóa mọi ký tự không phải số
        let value = input.value.replace(/\D/g, '');
        
        // Cập nhật giá trị cho input hidden (để gửi về server)
        if (targetId) {
            document.getElementById(targetId).value = value;
        }
        
        // Format hiển thị (thêm dấu chấm)
        if (value !== '') {
            input.value = new Intl.NumberFormat('vi-VN').format(value);
        } else {
            input.value = '';
        }
    }
</script>
@endsection