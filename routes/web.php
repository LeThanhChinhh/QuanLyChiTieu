<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupExpenseController;
use App\Http\Controllers\GroupSettlementController;

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// --- KHÁCH (Chưa đăng nhập) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// --- THÀNH VIÊN (Phải đăng nhập mới vào được) ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // Các chức năng chính
    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('budgets', BudgetController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Help Page
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');

    // --- ADMIN PANEL ---
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Global Categories Management
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'edit', 'show']);
    });


    Route::resource('wallets', \App\Http\Controllers\WalletController::class);

    // Recurring Transactions
    Route::resource('recurring', RecurringTransactionController::class);
    Route::patch('/recurring/{recurring}/toggle', [RecurringTransactionController::class, 'toggleStatus'])->name('recurring.toggle');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    // Groups - Chi tiêu nhóm
    Route::resource('groups', GroupController::class);
    
    // Group Members
    Route::get('/groups/{group}/members', [GroupMemberController::class, 'index'])->name('groups.members.index');
    Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])->name('groups.members.store');
    Route::patch('/groups/{group}/members/{member}/role', [GroupMemberController::class, 'updateRole'])->name('groups.members.update-role');
    Route::delete('/groups/{group}/members/{member}', [GroupMemberController::class, 'destroy'])->name('groups.members.destroy');
    
    // Group Expenses
    Route::get('/groups/{group}/expenses', [GroupExpenseController::class, 'index'])->name('groups.expenses.index');
    Route::get('/groups/{group}/expenses/create', [GroupExpenseController::class, 'create'])->name('groups.expenses.create');
    Route::post('/groups/{group}/expenses', [GroupExpenseController::class, 'store'])->name('groups.expenses.store');
    Route::get('/groups/{group}/expenses/{expense}', [GroupExpenseController::class, 'show'])->name('groups.expenses.show');
    Route::delete('/groups/{group}/expenses/{expense}', [GroupExpenseController::class, 'destroy'])->name('groups.expenses.destroy');
    
    // Group Settlements
    Route::get('/groups/{group}/settlements', [GroupSettlementController::class, 'index'])->name('groups.settlements.index');
    Route::get('/groups/{group}/settlements/create', [GroupSettlementController::class, 'create'])->name('groups.settlements.create');
    Route::post('/groups/{group}/settlements', [GroupSettlementController::class, 'store'])->name('groups.settlements.store');
    Route::delete('/groups/{group}/settlements/{settlement}', [GroupSettlementController::class, 'destroy'])->name('groups.settlements.destroy');
});
