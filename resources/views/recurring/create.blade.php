@extends('layouts.app')

@section('title', 'Tạo giao dịch định kỳ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="glass-form-container p-4">
                <h3 class="text-white mb-4">Tạo giao dịch định kỳ</h3>

                <form action="{{ route('recurring.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-white">Mô tả</label>
                        <input type="text" name="description" class="form-control glass-input" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Số tiền</label>
                            <input type="text" class="form-control glass-input" required oninput="formatCurrency(this, 'amount')">
                            <input type="hidden" name="amount" id="amount">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Loại</label>
                            <select name="type" class="form-select glass-input" id="typeSelect" required>
                                <option value="expense">Chi tiêu</option>
                                <option value="income">Thu nhập</option>
                                <option value="transfer">Chuyển khoản</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Tần suất</label>
                            <select name="frequency" class="form-select glass-input" required>
                                <option value="daily">Hàng ngày</option>
                                <option value="weekly">Hàng tuần</option>
                                <option value="monthly">Hàng tháng</option>
                                <option value="yearly">Hàng năm</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control glass-input" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Ví</label>
                            <select name="wallet_id" class="form-select glass-input" required>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ number_format($wallet->balance) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="categoryGroup">
                            <label class="form-label text-white">Danh mục</label>
                            <select name="category_id" class="form-select glass-input">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="destinationWalletGroup">
                            <label class="form-label text-white">Ví đến</label>
                            <select name="destination_wallet_id" class="form-select glass-input">
                                <option value="">Chọn ví đến</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('recurring.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Tạo lịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
