<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Thêm dòng này
use Illuminate\Support\Facades\Auth; // Thêm dòng này

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Chia sẻ biến $notifications cho file layouts.app
        View::composer('layouts.app', function ($view) {
            $notifications = [];
            $unreadCount = 0;

            if (Auth::check()) {
                // Lấy tất cả thông báo, sắp xếp mới nhất
                $notifications = Auth::user()->notifications()->latest()->take(10)->get();
                // Đếm số chưa đọc
                $unreadCount = Auth::user()->unreadNotifications->count();
            }

            $view->with('notifications', $notifications);
            $view->with('unreadCount', $unreadCount);
        });
    }
}