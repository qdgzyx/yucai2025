<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantifyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantify_item_id',
        'assessed_at',
        'user_id', // 新增：添加user_id到可填充字段
        'banji_id',
        'score',
        'ip_address',
        'semester_id',
        'remark'
    ];

    public function quantifyItem()
    {
        return $this->belongsTo(QuantifyItem::class);
    }

    // 新增：定义与User模型的关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $user)
    {
        return $query->whereHas('quantifyItem', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }
}