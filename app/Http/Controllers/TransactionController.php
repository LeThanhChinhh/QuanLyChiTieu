<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Wallet; // [MỚI] Import Model Wallet
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // [MỚI] Import DB để dùng Transaction
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Notifications\TransactionCreated;
use App\Notifications\BudgetAlert;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with(['category', 'wallet']); // [MỚI] Eager load thêm wallet

        // ... (Giữ nguyên logic Search và Filter như cũ) ...
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('description', 'like', '%' . $keyword . '%')
                  ->orWhereHas('category', function($catQuery) use ($keyword) {
                      $catQuery->where('name', 'like', '%' . $keyword . '%');
                  });
            });
        }
        
        // Filter logic (giữ nguyên)...
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest('transaction_date')->paginate(10);
        $transactions->appends($request->all());

        $categories = Category::where('user_id', Auth::id())->get();
        // [MỚI] Lấy thêm danh sách ví để lọc (nếu muốn mở rộng sau này)
        $wallets = Wallet::where('user_id', Auth::id())->get(); 

        return view('transactions.index', compact('transactions', 'categories', 'wallets'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        // [MỚI] Lấy danh sách ví để hiển thị trong select box
        $wallets = Wallet::where('user_id', Auth::id())->get();
        
        return view('transactions.create', compact('categories', 'wallets'));
    }

    // --- [QUAN TRỌNG] HÀM STORE ĐÃ SỬA ---
public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:0',
        'transaction_date' => 'required|date',
        'type' => 'required|in:income,expense,transfer', // Thêm transfer
        'wallet_id' => 'required|exists:wallets,id',
        
        // Validate cho category (Transfer không bắt buộc có category)
        'category_id' => 'nullable|required_unless:type,transfer|exists:categories,id',
        
        // Validate ví đích (Bắt buộc nếu là transfer, và phải khác ví nguồn)
        'destination_wallet_id' => [
            'nullable',
            'required_if:type,transfer',
            'exists:wallets,id',
            'different:wallet_id'
        ],
    ]);

    $transaction = null;

    DB::transaction(function () use ($request, &$transaction) {
        // 1. Tạo giao dịch
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'wallet_id' => $request->wallet_id,
            'destination_wallet_id' => $request->type == 'transfer' ? $request->destination_wallet_id : null,
            'category_id' => $request->type == 'transfer' ? null : $request->category_id, // Transfer ko cần danh mục
            'amount' => $request->amount,
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description
        ]);

        // 2. Trừ/Cộng tiền vào Ví
        $sourceWallet = Wallet::find($request->wallet_id);
        $amount = (float) $request->amount;

        if ($request->type == 'income') {
            $sourceWallet->increment('balance', $amount);
        } 
        elseif ($request->type == 'expense') {
            $sourceWallet->decrement('balance', $amount);
        } 
        elseif ($request->type == 'transfer') {
            // Logic chuyển khoản: Trừ ví nguồn -> Cộng ví đích
            $destWallet = Wallet::find($request->destination_wallet_id);
            
            $sourceWallet->decrement('balance', $amount);
            $destWallet->increment('balance', $amount);
        }
    });

    if ($transaction) {
            Auth::user()->notify(new TransactionCreated($transaction));
        }

    // Logic check budget (chỉ áp dụng cho chi tiêu)
    if ($request->type == 'expense') {
        $this->checkBudget($request);
    }

    return redirect()->route('transactions.index')->with('success', 'Giao dịch thành công!');
}

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);

        $categories = Category::where('user_id', Auth::id())->get();
        $wallets = Wallet::where('user_id', Auth::id())->get(); // [MỚI]
        
        return view('transactions.edit', compact('transaction', 'categories', 'wallets'));
    }

    public function update(Request $request, Transaction $transaction)
{
    if ($transaction->user_id !== Auth::id()) abort(403);

    $request->validate([
        'amount' => 'required|numeric|min:0',
        'transaction_date' => 'required|date',
        'type' => 'required|in:income,expense,transfer',
        'wallet_id' => 'required|exists:wallets,id',
        'category_id' => 'nullable|required_unless:type,transfer|exists:categories,id',
        'destination_wallet_id' => [
            'nullable', 
            'required_if:type,transfer', 
            'exists:wallets,id', 
            'different:wallet_id'
        ],
    ]);

    DB::transaction(function () use ($request, $transaction) {
        // BƯỚC 1: HOÀN TIỀN CŨ (Revert old transaction)
        // Khôi phục số dư các ví về trạng thái trước khi có giao dịch này
        $oldSource = Wallet::find($transaction->wallet_id);
        $oldAmount = (float) $transaction->amount;

        if ($oldSource) {
            if ($transaction->type == 'income') {
                $oldSource->decrement('balance', $oldAmount);
            } elseif ($transaction->type == 'expense') {
                $oldSource->increment('balance', $oldAmount);
            } elseif ($transaction->type == 'transfer') {
                // Hoàn lại: Cộng vào nguồn, Trừ ở đích
                $oldSource->increment('balance', $oldAmount);
                if ($transaction->destination_wallet_id) {
                    Wallet::find($transaction->destination_wallet_id)?->decrement('balance', $oldAmount);
                }
            }
        }

        // BƯỚC 2: CẬP NHẬT DỮ LIỆU GIAO DỊCH
        $transaction->update([
            'wallet_id' => $request->wallet_id,
            'destination_wallet_id' => $request->type == 'transfer' ? $request->destination_wallet_id : null,
            'category_id' => $request->type == 'transfer' ? null : $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description
        ]);

        // BƯỚC 3: TRỪ TIỀN MỚI (Apply new transaction)
        $newSource = Wallet::find($request->wallet_id);
        $newAmount = (float) $request->amount;

        if ($request->type == 'income') {
            $newSource->increment('balance', $newAmount);
        } elseif ($request->type == 'expense') {
            $newSource->decrement('balance', $newAmount);
        } elseif ($request->type == 'transfer') {
            $newDest = Wallet::find($request->destination_wallet_id);
            
            $newSource->decrement('balance', $newAmount);
            $newDest->increment('balance', $newAmount);
        }
    });

    if ($request->type == 'expense') {
        $this->checkBudget($request);
    }

    return redirect()->route('transactions.index')->with('success', 'Cập nhật giao dịch thành công!');
}

public function destroy(Transaction $transaction)
{
    if ($transaction->user_id !== Auth::id()) abort(403);

    DB::transaction(function () use ($transaction) {
        $sourceWallet = Wallet::find($transaction->wallet_id);
        $amount = (float) $transaction->amount;

        // Hoàn tiền lại ví trước khi xóa
        if ($sourceWallet) {
            if ($transaction->type == 'income') {
                $sourceWallet->decrement('balance', $amount);
            } elseif ($transaction->type == 'expense') {
                $sourceWallet->increment('balance', $amount);
            } elseif ($transaction->type == 'transfer') {
                // Xóa chuyển khoản: Cộng lại nguồn, Trừ ở đích
                $sourceWallet->increment('balance', $amount);
                if ($transaction->destination_wallet_id) {
                    Wallet::find($transaction->destination_wallet_id)?->decrement('balance', $amount);
                }
            }
        }
        
        $transaction->delete();
    });
    
    return redirect()->route('transactions.index')->with('success', 'Đã xóa giao dịch!');
}

    // Tách hàm check budget ra cho gọn
    private function checkBudget($request) {
        $date = Carbon::parse($request->transaction_date);
        $budget = Budget::where('user_id', Auth::id())
            ->where('category_id', $request->category_id)
            ->where('month', $date->month)
            ->where('year', $date->year)
            ->first();

        if ($budget) {
            $totalSpent = Transaction::where('user_id', Auth::id())
                ->where('category_id', $request->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            if ($totalSpent > $budget->amount) {
                $rawOverAmount = $totalSpent - $budget->amount;
                $formattedOverAmount = number_format($rawOverAmount, 0, ',', '.');
                
                session()->flash('warning', "Cảnh báo: Bạn đã tiêu lố ngân sách $formattedOverAmount đ!");

                $details = [
                    'title' => 'Cảnh báo ngân sách',
                    'message' => "Bạn đã vượt ngân sách '{$budget->category->name}' khoảng {$formattedOverAmount}đ",
                    'type' => 'warning',
                    'icon' => 'ri-error-warning-line',
                    'link' => route('budgets.index')
                ];
                
                Auth::user()->notify(new BudgetAlert($details));
            }
        }
    }
}