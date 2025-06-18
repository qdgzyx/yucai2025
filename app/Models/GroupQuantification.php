<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; // 新增日志类

class GroupQuantification extends Model
{
    use HasFactory;

    protected $table = 'group_quantifications';
    
    protected $fillable = [
        'group_basic_info_id',
        'content',
        'score', 
        'time',
        'recorder'
    ];

    // 新增日期类型转换
    protected $casts = [
        'time' => 'datetime'
    ];

    // 修改: 通过group_basic_info_id关联到GroupBasicInfo
    public function groupBasicInfo()
    {
        return $this->belongsTo(GroupBasicInfo::class, 'group_basic_info_id');
    }

    protected static function booted()
    {
        static::created(function ($quantify) {
            try {
                // 新增：空值安全检查和日志
                $group = $quantify->groupBasicInfo;
                if (!$group) {
                    Log::warning("量化记录创建失败：未找到小组基础信息", ['quantify_id' => $quantify->id]);
                    return;
                }
                
                $banji = $group->banji;
                if (!$banji) {
                    Log::warning("量化记录创建失败：小组未关联班级", [
                        'group_id' => $group->id,
                        'quantify_id' => $quantify->id
                    ]);
                    return;
                }
                
                $teacher = $banji->user;
                if (!$teacher) {
                    Log::warning("量化记录创建失败：班级未设置班主任", [
                        'banji_id' => $banji->id,
                        'quantify_id' => $quantify->id
                    ]);
                    return;
                }
                
                // 新增：记录调试信息
                Log::info("准备发送量化通知", [
                    'teacher_id' => $teacher->id,
                    'quantify_id' => $quantify->id
                ]);
                
                $teacher->notify(new \App\Notifications\QuantifyRecord($quantify));
                
            } catch (\Exception $e) {
                Log::error("量化通知发送失败: ".$e->getMessage(), [
                    'quantify_id' => $quantify->id,
                    'exception' => $e
                ]);
            }
        });
    }
}