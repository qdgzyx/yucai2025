<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuantifyRecord extends Model
{
    // 新增与班级的关联关系
    public function banji()
    {
        return $this->belongsTo(Banji::class);
    }

    protected $fillable = [
        'quantify_item_id',
        'assessed_at',
        'user_id',
        'banji_id',
        'score',
        'ip_address',
        'semester_id',
        'remark'
    ];

    // 新增日期转换配置
    protected $dates = ['assessed_at'];
    // 或使用casts方式（二选一即可）
    // protected $casts = [
    //     'assessed_at' => 'datetime'
    // ];

    public function quantifyItem()
    {
        return $this->belongsTo(QuantifyItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}