<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Lấy budgets với thống kê
        $budgets = Budget::where('user_id', $userId)
                    ->with('category')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get()
                    ->map(function($budget) use ($userId) {
                        // Tính tổng đã chi cho category này trong tháng
                        $budget->spent = Transaction::where('user_id', $userId)
                            ->where('category_id', $budget->category_id)
                            ->where('type', 'expense')
                            ->whereMonth('transaction_date', $budget->month)
                            ->whereYear('transaction_date', $budget->year)
                            ->sum('amount');
                        
                        return $budget;
                    });

        // Tính thống kê
        $totalBudgets = $budgets->sum('amount');
        $overspent = $budgets->filter(function($b) {
            return $b->spent > $b->amount;
        })->count();
        $onTrack = $budgets->filter(function($b) {
            $percentage = $b->amount > 0 ? ($b->spent / $b->amount) * 100 : 0;
            return $percentage < 90 && $b->spent <= $b->amount;
        })->count();

        return view('budgets.index', compact('budgets', 'totalBudgets', 'overspent', 'onTrack'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2024',
        ]);

        // Kiểm tra xem đã đặt budget cho danh mục này trong tháng này chưa
        $exists = Budget::where('user_id', Auth::id())
                    ->where('category_id', $request->category_id)
                    ->where('month', $request->month)
                    ->where('year', $request->year)
                    ->exists();

        if ($exists) {
            return back()->withErrors(['msg' => 'Bạn đã đặt ngân sách cho mục này trong tháng đó rồi!']);
        }

        Budget::create([
            'user_id' => Auth::id(),
            ...$request->all()
        ]);

        return redirect()->route('budgets.index')->with('success', 'Đã thiết lập ngân sách!');
    }

    public function edit(Budget $budget)
    {
        // Bảo mật: Chỉ chủ sở hữu mới được sửa
        if ($budget->user_id !== Auth::id()) abort(403);

        $categories = Category::where('user_id', Auth::id())->get();
        return view('budgets.edit', compact('budget', 'categories'));
    }

    // 5. Cập nhật Ngân sách
    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2024',
        ]);

        $budget->update($request->all());

        return redirect()->route('budgets.index')->with('success', 'Cập nhật ngân sách thành công!');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);
        
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Đã xóa ngân sách!');
    }
}