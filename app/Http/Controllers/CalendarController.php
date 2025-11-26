<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        return view('transactions.calendar');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        // Eager load 'wallet' và 'category' để lấy tên và icon
        $transactions = Transaction::where('user_id', Auth::id())
            ->whereBetween('transaction_date', [$start, $end])
            ->with(['wallet', 'category']) 
            ->get();

        $events = $transactions->map(function ($transaction) {
            // 1. Xác định màu sắc
            $color = '#3B82F6'; // Mặc định: Xanh dương (Transfer)
            if ($transaction->type == 'income') {
                $color = '#10B981'; // Xanh lá
            } elseif ($transaction->type == 'expense') {
                $color = '#EF4444'; // Đỏ
            }

            // 2. Xử lý thời gian
            $start = $transaction->transaction_date->format('Y-m-d');
            // Nếu có created_at thì thêm giờ để sắp xếp, nếu không thì mặc định
            if ($transaction->created_at) {
                $start .= 'T' . $transaction->created_at->format('H:i:s');
            } else {
                $start .= 'T12:00:00'; // Fallback time to ensure order
            }

            return [
                'title' => number_format($transaction->amount),
                'start' => $start,
                'allDay' => false, // Force point-in-time
                'backgroundColor' => $color,
                'borderColor' => $color,
                // Dữ liệu mở rộng để dùng trong Popup SweetAlert2
                'extendedProps' => [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'type' => $transaction->type,
                    'description' => $transaction->description,
                    'wallet_name' => $transaction->wallet->name ?? 'Ví đã xóa',
                    'category_name' => $transaction->category->name ?? ($transaction->type == 'transfer' ? 'Chuyển khoản' : 'Khác'),
                    'category_icon' => $transaction->category->icon ?? ($transaction->type == 'transfer' ? 'ri-exchange-line' : 'ri-question-line'),
                ]
            ];
        });

        return response()->json($events);
    }
}