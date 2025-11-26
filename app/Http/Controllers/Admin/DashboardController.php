<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->count();
        
        $totalTransactions = Transaction::count();
        $totalTransactionAmount = Transaction::sum('amount');

        return view('admin.dashboard', compact(
            'totalUsers', 
            'newUsersThisMonth', 
            'totalTransactions', 
            'totalTransactionAmount'
        ));
    }
}
