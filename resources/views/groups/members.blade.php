@extends('layouts.app')

@section('title', 'Quản lý thành viên - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('groups.show', $group) }}" class="btn-back">
            <i class="ri-arrow-left-line"></i> {{ $group->name }}
        </a>
        <h1><i class="ri-user-line"></i> Quản Lý Thành Viên</h1>
    </div>
</div>

<!-- Add Member Form -->
<div class="form-container-centered" style="max-width: 500px; margin-bottom: 2rem;">
    <div class="form-card-compact">
        <h3 class="form-card-title">Thêm Thành Viên Mới</h3>
        
        <form action="{{ route('groups.members.store', $group) }}" method="POST">
            @csrf
            
            <div class="form-group-compact">
                <label class="form-label-compact">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-input-compact" 
                       placeholder="email@example.com" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group-compact">
                <label class="form-label-compact">Vai trò</label>
                <select name="role" class="form-input-compact">
                    <option value="member">Thành viên</option>
                    <option value="admin">Quản trị</option>
                    <option value="viewer">Xem</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="ri-user-add-line"></i> Thêm Thành Viên
            </button>
        </form>
    </div>
</div>

<!-- Members List -->
<div class="members-section">
    <h2>Danh Sách Thành Viên ({{ $members->count() }})</h2>
    
    <div class="members-list-detailed">
        @foreach($members as $member)
            <div class="member-card">
                <div class="member-avatar-large">
                    @if($member->user->avatar)
                        <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}">
                    @else
                        <div class="avatar-placeholder-large">{{ substr($member->user->name, 0, 1) }}</div>
                    @endif
                </div>
                
                <div class="member-details">
                    <h4>{{ $member->user->name }}</h4>
                    <p>{{ $member->user->email }}</p>
                    <span class="badge badge-{{ $member->role === 'admin' ? 'primary' : 'secondary' }}">
                        {{ $member->role === 'admin' ? 'Quản trị' : ($member->role === 'viewer' ? 'Xem' : 'Thành viên') }}
                    </span>
                    <small style="display: block; margin-top: 0.5rem; color: #6B7280;">
                        Tham gia: {{ $member->joined_at->format('d/m/Y') }}
                    </small>
                </div>
                
                @if($group->isAdmin(Auth::id()) && $member->user_id !== $group->created_by)
                    <div class="member-actions">
                        <form action="{{ route('groups.members.update-role', [$group, $member]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="if(confirm('Thay đổi vai trò thành viên?')) this.form.submit()" class="form-control form-control-sm">
                                <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Quản trị</option>
                                <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>Thành viên</option>
                                <option value="viewer" {{ $member->role === 'viewer' ? 'selected' : '' }}>Xem</option>
                            </select>
                        </form>
                        
                        <form action="{{ route('groups.members.destroy', [$group, $member]) }}" method="POST" onsubmit="return confirm('Xóa thành viên này khỏi nhóm?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="ri-user-unfollow-line"></i>
                            </button>
                        </form>
                    </div>
                @endif
                
                @if($member->user_id === $group->created_by)
                    <div class="creator-badge">
                        <i class="ri-vip-crown-line"></i> Người tạo
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
