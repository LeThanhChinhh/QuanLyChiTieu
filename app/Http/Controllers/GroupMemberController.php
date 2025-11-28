<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->isMember(Auth::id())) {
            abort(403, 'Bạn không phải là thành viên của nhóm này');
        }

        $members = $group->members()
            ->with('user')
            ->where('status', 'active')
            ->get();

        return view('groups.members', compact('group', 'members'));
    }

    public function store(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->isAdmin(Auth::id())) {
            abort(403, 'Chỉ admin mới có thể thêm thành viên');
        }

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'nullable|in:admin,member,viewer',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Check if already active member
        $existingMember = GroupMember::where('group_id', $groupId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingMember) {
            if ($existingMember->status === 'active') {
                return back()->with('error', 'Người dùng đã là thành viên của nhóm');
            }
            
            // Reactivate removed/left member
            $existingMember->update([
                'status' => 'active',
                'role' => $validated['role'] ?? 'member',
                'joined_at' => now(),
                'left_at' => null,
            ]);
            
            return back()->with('success', "Đã thêm lại {$user->name} vào nhóm");
        }

        GroupMember::create([
            'group_id' => $groupId,
            'user_id' => $user->id,
            'role' => $validated['role'] ?? 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return back()->with('success', "Đã thêm {$user->name} vào nhóm");
    }

    public function updateRole(Request $request, $groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->isAdmin(Auth::id())) {
            abort(403, 'Chỉ admin mới có thể thay đổi vai trò');
        }

        $member = GroupMember::where('group_id', $groupId)
            ->where('id', $memberId)
            ->firstOrFail();

        // Prevent changing creator's role
        if ($member->user_id === $group->created_by) {
            return back()->with('error', 'Không thể thay đổi vai trò của người tạo nhóm');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,member,viewer',
        ]);

        $member->update(['role' => $validated['role']]);

        return back()->with('success', 'Đã cập nhật vai trò thành viên');
    }

    public function destroy($groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);
        $member = GroupMember::where('group_id', $groupId)
            ->where('id', $memberId)
            ->firstOrFail();

        // Check permission
        $isAdmin = $group->isAdmin(Auth::id());
        $isSelf = $member->user_id === Auth::id();

        if (!$isAdmin && !$isSelf) {
            abort(403, 'Không có quyền xóa thành viên này');
        }

        // Prevent removing creator
        if ($member->user_id === $group->created_by) {
            return back()->with('error', 'Không thể xóa người tạo nhóm');
        }

        // Check for unsettled debts
        $balance = $group->balances()
            ->where('user_id', $member->user_id)
            ->first();

        if ($balance && abs($balance->balance) > 0.01) {
            return back()->with('error', 'Thành viên còn nợ chưa thanh toán, không thể rời nhóm');
        }

        $member->update([
            'status' => $isSelf ? 'left' : 'removed',
            'left_at' => now(),
        ]);

        $message = $isSelf ? 'Bạn đã rời khỏi nhóm' : 'Đã xóa thành viên khỏi nhóm';

        return $isSelf
            ? redirect()->route('groups.index')->with('success', $message)
            : back()->with('success', $message);
    }
}
