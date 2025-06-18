<?php

namespace App\Observers;

use App\Models\QuantifyRecord;
use App\Models\Banji;
use App\Notifications\QuantifyRecorded;
use Symfony\Component\Mailer\Exception\TransportException;

class QuantifyRecordObserver
{
    public function creating(QuantifyRecord $quantify_record)
    {
        //
    }

    public function updating(QuantifyRecord $quantify_record)
    {
        //
    }

    public function created(QuantifyRecord $quantify_record)
    {
        // 通过 quantify_record 的 banji_id 获取对应的 user_id
        $banji = Banji::find($quantify_record->banji_id);
        
        if ($banji && $banji->user_id) {
            $user = \App\Models\User::find($banji->user_id);
            
            if ($user) {
                try {
                    // 发送通知
                    $user->notify(new QuantifyRecorded($quantify_record));
                    // 记录成功日志
                    \Log::info("通知已成功发送。量化记录ID: {$quantify_record->id}");
                } catch (TransportException $e) {
                    // 记录邮件发送失败的错误日志
                    \Log::error("邮件发送失败: " . $e->getMessage());
                } catch (\Exception $e) {
                    // 捕获其他异常并记录日志
                    \Log::error("通知发送失败: " . $e->getMessage());
                }
            } else {
                // 记录日志：未找到对应的用户
                \Log::warning("未找到对应的用户，无法发送通知。量化记录ID: {$quantify_record->id}");
            }
        } else {
            // 记录日志：未找到对应的班或用户ID
            \Log::warning("未找到对应的班或用户ID，无法发送通知。量化记录ID: {$quantify_record->id}");
        }
    }
}