<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Để validate email unique trừ chính mình

class ProfileController extends Controller
{
    // 1. Hiển thị trang hồ sơ
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    // 2. Cập nhật thông tin cơ bản (Tên, Email, Avatar)
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Nếu email thay đổi, có thể cần verify lại (tuỳ chọn, ở đây ta cập nhật luôn)
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null; // Reset xác thực email nếu đổi email
        }

        $user->save();

        return back()->with('success', 'Đã cập nhật thông tin hồ sơ!');
    }

    // 3. Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'], // Laravel tự check pass cũ
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'
            ],
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất: 1 chữ thường, 1 chữ HOA, 1 số và 1 ký tự đặc biệt (@$!%*?&#)',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}