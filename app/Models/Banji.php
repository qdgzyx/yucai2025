<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banji extends Model
{
    use HasFactory;
    
    protected $fillable = ['grade_id', 'name', 'student_count', 'user_id'];
    
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function assignments() {
        return $this->belongsToMany(Assignment::class)->withTimestamps();
    }
    
    public function subjects() {
        return $this->belongsToMany(Subject::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 新增: 通过GroupBasicInfo关联到量化记录
    public function groupBasicInfos()
    {
        return $this->hasMany(GroupBasicInfo::class);
    }

    /**
     * 获取班级关联的出勤报告
     */
    public function reports()
    {
        return $this->hasMany(\App\Models\Report::class);
    }
}