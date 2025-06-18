<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuantifyRecorded extends Notification
{
    use Queueable;

    protected $quantify_record;

    public function __construct($quantify_record)
    {
        $this->quantify_record = $quantify_record;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('量化记录通知')
            ->line('量化记录已创建，详情如下：')
            ->line('量化记录 ID: ' . $this->quantify_record->id)
            ->line('量化记录内容: ' . $this->quantify_record->content)
            ->action('查看详情', url('/quantify-records/' . $this->quantify_record->id));
    }

    public function toArray($notifiable)
    {
        return [
            'quantify_record_id' => $this->quantify_record->id,
            'content' => $this->quantify_record->content,
            'created_at' => $this->quantify_record->created_at->toDateTimeString(),
        ];
    }
}