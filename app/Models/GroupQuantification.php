<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function banji()
    {
        return $this->belongsTo(Banji::class);
    }

    public function groupBasicInfo()
    {
        return $this->belongsTo(GroupBasicInfo::class, 'group_basic_info_id');
    }
}