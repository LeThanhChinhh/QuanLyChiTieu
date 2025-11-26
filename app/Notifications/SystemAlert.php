<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemAlert extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Cập nhật hệ thống',
            'message' => $this->message,
            'type'    => 'info', // Dùng để CSS màu xanh dương
            'icon'    => 'ri-information-line',
            'link'    => '#'
        ];
    }
}