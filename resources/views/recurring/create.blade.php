@extends('layouts.app')

@section('title', 'Tạo giao dịch định kỳ')

@section('styles')
    @vite(['resources/css/wallet.css'])
@endsection

@section('content')
<div class="wallet-form-container">
    <div class="wallet-form-header">
        <h2 class="text-primary">
            <i class="ri-repeat-line me-2"></i>Tạo giao dịch định kỳ
        </h2>
    </div>
    
    <form action="{{ route('recurring.store') }}" method="POST">
                    @csrf

                    <div class="wallet-form-group">
                        <label class="wallet-form-label">Mô tả</label>
                        <input type="text" name="description" class="wallet-form-input" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Số tiền</label>
                                <input type="text" class="wallet-form-input" required oninput="formatCurrency(this, 'amount')">
                                <input type="hidden" name="amount" id="amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Loại</label>
                                <select name="type" class="wallet-form-input" id="typeSelect" required>
                                    <option value="expense">Chi tiêu</option>
                                    <option value="income">Thu nhập</option>
                                    <option value="transfer">Chuyển khoản</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Tần suất</label>
                                <select name="frequency" class="wallet-form-input" required>
                                    <option value="daily">Hàng ngày</option>
                                    <option value="weekly">Hàng tuần</option>
                                    <option value="monthly">Hàng tháng</option>
                                    <option value="yearly">Hàng năm</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Ngày bắt đầu</label>
                                <input type="date" name="start_date" class="wallet-form-input" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Ví</label>
                                <select name="wallet_id" class="wallet-form-input" required>
                                    @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ number_format($wallet->balance) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="categoryGroup">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Danh mục</label>
                                <select name="category_id" class="wallet-form-input">
                                    <option value="">Chọn danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-none" id="destinationWalletGroup">
                            <div class="wallet-form-group">
                                <label class="wallet-form-label">Ví đến</label>
                                <select name="destination_wallet_id" class="wallet-form-input">
                                    <option value="">Chọn ví đến</option>
                                    @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="wallet-form-actions">
                        <a href="{{ route('recurring.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="ri-save-line"></i> Tạo lịch
                        </button>
                    </div>
                </form>
            </div>

            <script>
    function formatCurrency(input, targetId) {
        let value = input.value.replace(/\D/g, '');
        if (targetId) document.getElementById(targetId).value = value;
        if (value !== '') input.value = new Intl.NumberFormat('vi-VN').format(value);
        else input.value = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('typeSelect');
        const categoryGroup = document.getElementById('categoryGroup');
        const destinationWalletGroup = document.getElementById('destinationWalletGroup');

        function updateFields() {
            const type = typeSelect.value;
            if (type === 'transfer') {
                categoryGroup.classList.add('d-none');
                destinationWalletGroup.classList.remove('d-none');
            } else {
                categoryGroup.classList.remove('d-none');
                destinationWalletGroup.classList.add('d-none');
            }
        }

        typeSelect.addEventListener('change', updateFields);
        updateFields(); // Initial check
    });
</script>
@endsection
