<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupBasicInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'banji_id',
        'leader',
        'members'
    ];

    // 在GroupBasicInfo模型中添加到Banji的关联
    public function banji()
    {
        return $this->belongsTo(Banji::class, 'banji_id');
    }
}