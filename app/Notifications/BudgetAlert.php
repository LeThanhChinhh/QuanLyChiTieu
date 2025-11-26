<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetAlert extends Notification
{
    use Queueable;

    protected $data;

    // Nhận dữ liệu truyền vào
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Chỉ lưu vào database
    }

    // Định dạng dữ liệu lưu vào cột 'data' trong bảng notifications
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'type' => $this->data['type'], // 'warning', 'success', 'info'
            'icon' => $this->data['icon'], // class icon (ví dụ: ri-error-warning-line)
            'link' => $this->data['link'] ?? '#'
        ];
    }
}