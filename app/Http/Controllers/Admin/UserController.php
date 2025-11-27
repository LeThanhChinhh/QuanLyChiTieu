<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        // Prevent banning self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể khóa tài khoản của chính mình.');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'Mở khóa' : 'Khóa';
        return back()->with('success', "Đã {$status} tài khoản {$user->name} thành công.");
    }

    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        // Prevent deleting other admins
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản Admin khác.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Đã xóa tài khoản {$userName} thành công.");
    }
}
