<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuantifyRecord extends Notification
{
    use Queueable;

    protected $quantify;

    public function __construct($quantify)
    {
        $this->quantify = $quantify;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // 修改：增加空值安全处理
        $group = optional($this->quantify)->groupBasicInfo;
        $banji = optional($group)->banji;
        
        $banjiName = $banji->name ?? '未知班级';
        $groupName = $group->name ?? '未知小组';
        $score = $this->quantify->score ?? 0;
        
        return (new MailMessage)
            ->subject('班级量化记录更新提醒')
            ->line("您管理的班级【{$banjiName}】量化记录已更新")
            ->line("小组【{$groupName}】获得【{$score}】分")
            ->action('查看详情', url('/group_quantify/display'))
            ->line('更新时间：' . now()->format('Y-m-d H:i'));
    }

   // 修改: 增加空值处理和数据验证
public function toArray($notifiable)
{
    // 安全获取关联数据
    $groupBasicInfo = optional($this->quantify)->groupBasicInfo;
    $banji = optional($groupBasicInfo)->banji;
    
    return [
        'message' => '班级量化记录已更新',
        'quantify_id' => $this->quantify->id,
        'group_name' => optional($groupBasicInfo)->name,
        'score' => $this->quantify->score,
        'banji_id' => optional($banji)->id,
        'banji_name' => optional($banji)->name,
        'time' => now()->toDateTimeString()
    ];
}
}
