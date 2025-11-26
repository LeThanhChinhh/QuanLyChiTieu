<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet; // <--- THÊM DÒNG NÀY
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // 1. Tính Tổng Thu trong tháng này (Dựa trên giao dịch)
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // 2. Tính Tổng Chi trong tháng này (Dựa trên giao dịch)
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // 3. [SỬA LẠI] Tính Số Dư Tổng: Lấy tổng balance của tất cả các ví
        // Thay vì tính cộng trừ giao dịch, ta lấy trực tiếp số tiền thật đang có trong các ví
        $balance = Wallet::where('user_id', $userId)->sum('balance'); 

        // 4. DATA CHO BIỂU ĐỒ (Giữ nguyên)
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $m = $date->month;
            $y = $date->year;

            $labels[] = $date->format('M'); 

            $incomeData[] = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $m)
                ->whereYear('transaction_date', $y)
                ->sum('amount');

            $expenseData[] = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $m)
                ->whereYear('transaction_date', $y)
                ->sum('amount');
        }

        // 5. Lấy 5 giao dịch gần nhất (Giữ nguyên)
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance', 'recentTransactions',
            'labels', 'incomeData', 'expenseData'
        ));
    }
}