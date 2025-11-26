@extends('layouts.admin')

@section('title', 'Quản lý Danh mục Mẫu')

@section('content')
<div class="grid grid-cols-3 gap-4">
    <!-- Form thêm mới -->
    <div class="glass-card">
        <h5 class="mb-3">Thêm danh mục mới</h5>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Loại</label>
                <select name="type" class="form-select">
                    <option value="expense">Chi tiêu</option>
                    <option value="income">Thu nhập</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <label class="form-label">Icon (RemixIcon)</label>
                    <input type="text" name="icon" class="form-control" placeholder="ri-home-line">
                </div>
                <div>
                    <label class="form-label">Màu sắc</label>
                    <input type="color" name="color" class="form-control" value="#6B7280" style="height: 42px; padding: 4px;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i class="ri-add-line"></i> Thêm mới
            </button>
        </form>
    </div>

    <!-- Danh sách -->
    <div class="glass-card" style="grid-column: span 2;">
        <h5 class="mb-3">Danh sách danh mục mẫu</h5>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td>
                            <div style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $cat->color }}; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="{{ $cat->icon }}"></i>
                            </div>
                        </td>
                        <td class="fw-bold">{{ $cat->name }}</td>
                        <td>
                            @if($cat->type == 'income')
                                <span class="badge badge-active">Thu nhập</span>
                            @else
                                <span class="badge badge-admin">Chi tiêu</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <button class="btn btn-icon btn-primary" 
                                        onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->type }}', '{{ $cat->icon }}', '{{ $cat->color }}')">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger" onclick="return confirmSubmit(event, 'Bạn có chắc chắn muốn xóa danh mục này?')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-backdrop">
    <form id="editForm" method="POST" class="modal-content">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">Sửa danh mục</h5>
            <button type="button" class="btn btn-icon btn-danger" onclick="closeModal('editModal')">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Loại</label>
                <select name="type" id="edit_type" class="form-select">
                    <option value="expense">Chi tiêu</option>
                    <option value="income">Thu nhập</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="form-label">Icon</label>
                    <input type="text" name="icon" id="edit_icon" class="form-control">
                </div>
                <div>
                    <label class="form-label">Màu sắc</label>
                    <input type="color" name="color" id="edit_color" class="form-control" style="height: 42px; padding: 4px;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="closeModal('editModal')">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>

<script>
    function editCategory(id, name, type, icon, color) {
        const form = document.getElementById('editForm');
        form.action = `/admin/categories/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_type').value = type;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_color').value = color;
        
        openModal('editModal');
    }
</script>
@endsection
