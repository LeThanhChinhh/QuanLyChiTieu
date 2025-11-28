@extends('layouts.app')

@section('title', 'Nhóm chi tiêu - Quản lý Chi tiêu')

@section('styles')
    @vite(['resources/css/groups.css'])
@endsection

@section('content')
<!-- Add Group Button (Top Right) -->
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('groups.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="ri-add-line"></i>
        <span>Tạo nhóm mới</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="ri-checkbox-circle-line"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if($groups->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="ri-team-line"></i>
        </div>
        <h3 class="empty-state-title">Chưa có nhóm chi tiêu nào</h3>
        <p class="empty-state-text">Tạo nhóm mới để quản lý chi tiêu cùng bạn bè và gia đình</p>
    </div>
@else
    <div class="groups-grid">
        @foreach($groups as $group)
            <a href="{{ route('groups.show', $group) }}" class="group-card">
                <div class="group-card-header">
                    <div class="group-icon" style="background: {{ $group->color }}; box-shadow: 0 4px 10px {{ $group->color }}40;">
                        <i class="{{ $group->icon }}" style="color: white;"></i>
                    </div>
                    <div class="group-info">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ Str::limit($group->description, 60) }}</p>
                    </div>
                </div>
                
                <div class="group-stats">
                    <div class="stat-item">
                        <i class="ri-user-line"></i>
                        <span>{{ $group->members_count }} thành viên</span>
                    </div>
                    <div class="stat-item">
                        <i class="ri-file-list-line"></i>
                        <span>{{ $group->expenses_count }} chi tiêu</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
