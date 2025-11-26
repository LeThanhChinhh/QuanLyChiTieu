<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        $recurringTransactions = RecurringTransaction::where('user_id', Auth::id())
            ->with(['category', 'wallet'])
            ->orderBy('next_run_date', 'asc')
            ->get();

        return view('recurring.index', compact('recurringTransactions'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        $wallets = Wallet::where('user_id', Auth::id())->get();
        
        return view('recurring.create', compact('categories', 'wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense,transfer',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'nullable|required_unless:type,transfer|exists:categories,id',
            'destination_wallet_id' => 'nullable|required_if:type,transfer|exists:wallets,id|different:wallet_id',
        ]);

        RecurringTransaction::create([
            'user_id' => Auth::id(),
            'wallet_id' => $request->wallet_id,
            'destination_wallet_id' => $request->type == 'transfer' ? $request->destination_wallet_id : null,
            'category_id' => $request->type == 'transfer' ? null : $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'next_run_date' => $request->start_date, // Initial next run date is start date
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('recurring.index')->with('success', 'Đã tạo giao dịch định kỳ thành công.');
    }

    public function edit(RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('user_id', Auth::id())->get();
        $wallets = Wallet::where('user_id', Auth::id())->get();

        return view('recurring.edit', compact('recurring', 'categories', 'wallets'));
    }

    public function update(Request $request, RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense,transfer',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'nullable|required_unless:type,transfer|exists:categories,id',
            'destination_wallet_id' => 'nullable|required_if:type,transfer|exists:wallets,id|different:wallet_id',
        ]);

        $recurring->update([
            'wallet_id' => $request->wallet_id,
            'destination_wallet_id' => $request->type == 'transfer' ? $request->destination_wallet_id : null,
            'category_id' => $request->type == 'transfer' ? null : $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'description' => $request->description,
            'status' => $request->status ?? $recurring->status,
        ]);

        return redirect()->route('recurring.index')->with('success', 'Đã cập nhật giao dịch định kỳ thành công.');
    }

    public function destroy(RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $recurring->delete();

        return redirect()->route('recurring.index')->with('success', 'Đã xóa giao dịch định kỳ thành công.');
    }

    public function toggleStatus(RecurringTransaction $recurring)
    {
        if ($recurring->user_id !== Auth::id()) {
            abort(403);
        }

        $recurring->status = $recurring->status === 'active' ? 'inactive' : 'active';
        $recurring->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái thành công.');
    }
}

