<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Hiển thị danh sách ví
     */
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Form tạo ví mới
     */
    public function create()
    {
        return view('wallets.create');
    }

    /**
     * Lưu ví mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric',
            // Có thể thêm validate cho icon và color nếu muốn
        ]);

        Wallet::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'balance' => $request->balance, // Số dư khởi tạo
            'icon' => $request->icon ?? 'ri-wallet-3-line',
            'color' => $request->color ?? '#10B981',
        ]);

        return redirect()->route('wallets.index')->with('success', 'Đã tạo ví thành công!');
    }

    /**
     * Form sửa ví
     */
    public function edit(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) abort(403);
        return view('wallets.edit', compact('wallet'));
    }

    /**
     * Cập nhật thông tin ví
     */
    public function update(Request $request, Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric', 
            // Lưu ý: Thường thì ta không cho sửa số dư trực tiếp ở đây 
            // mà phải qua giao dịch điều chỉnh, nhưng ở mức đơn giản thì cho phép.
        ]);

        $wallet->update($request->all());

        return redirect()->route('wallets.index')->with('success', 'Cập nhật ví thành công!');
    }

    /**
     * Xóa ví
     */
    public function destroy(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) abort(403);
        
        // Khi xóa ví, các giao dịch thuộc ví đó cũng sẽ mất (do setup onDelete cascade ở migration)
        // Hoặc bạn có thể chặn xóa nếu ví đang có giao dịch.
        $wallet->delete();

        return redirect()->route('wallets.index')->with('success', 'Đã xóa ví!');
    }
}