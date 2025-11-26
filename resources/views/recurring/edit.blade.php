@extends('layouts.app')

@section('title', 'Sửa giao dịch định kỳ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="glass-form-container p-4">
                <h3 class="text-white mb-4">Sửa giao dịch định kỳ</h3>

                <form action="{{ route('recurring.update', $recurring) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label text-white">Mô tả</label>
                        <input type="text" name="description" class="form-control glass-input" required value="{{ $recurring->description }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Số tiền</label>
                            <input type="number" name="amount" class="form-control glass-input" required min="0" step="0.01" value="{{ $recurring->amount }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Loại</label>
                            <select name="type" class="form-select glass-input" id="typeSelect" required>
                                <option value="expense" {{ $recurring->type == 'expense' ? 'selected' : '' }}>Chi tiêu</option>
                                <option value="income" {{ $recurring->type == 'income' ? 'selected' : '' }}>Thu nhập</option>
                                <option value="transfer" {{ $recurring->type == 'transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Tần suất</label>
                            <select name="frequency" class="form-select glass-input" required>
                                <option value="daily" {{ $recurring->frequency == 'daily' ? 'selected' : '' }}>Hàng ngày</option>
                                <option value="weekly" {{ $recurring->frequency == 'weekly' ? 'selected' : '' }}>Hàng tuần</option>
                                <option value="monthly" {{ $recurring->frequency == 'monthly' ? 'selected' : '' }}>Hàng tháng</option>
                                <option value="yearly" {{ $recurring->frequency == 'yearly' ? 'selected' : '' }}>Hàng năm</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control glass-input" required value="{{ $recurring->start_date->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Ví</label>
                            <select name="wallet_id" class="form-select glass-input" required>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ $recurring->wallet_id == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }} ({{ number_format($wallet->balance) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 {{ $recurring->type == 'transfer' ? 'd-none' : '' }}" id="categoryGroup">
                            <label class="form-label text-white">Danh mục</label>
                            <select name="category_id" class="form-select glass-input">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $recurring->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 {{ $recurring->type != 'transfer' ? 'd-none' : '' }}" id="destinationWalletGroup">
                            <label class="form-label text-white">Ví đến</label>
                            <select name="destination_wallet_id" class="form-select glass-input">
                                <option value="">Chọn ví đến</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ $recurring->destination_wallet_id == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Trạng thái</label>
                        <select name="status" class="form-select glass-input">
                            <option value="active" {{ $recurring->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $recurring->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('recurring.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật lịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
        // Initial check is handled by blade conditional classes, but running it again is safe
        updateFields();
    });
</script>
@endsection
