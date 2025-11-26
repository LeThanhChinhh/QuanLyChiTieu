<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionCreated extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Giao dịch thành công',
            'message' => "Đã thêm giao dịch mới vào ví \"{$this->transaction->wallet->name}\".",
            'type'    => 'success', // Dùng để CSS màu xanh lá
            'icon'    => 'ri-checkbox-circle-line',
            'link'    => route('transactions.index')
        ];
    }
}