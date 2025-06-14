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

    // 新增：班级量化记录关联关系
    public function quantifyRecords()
    {
        return $this->hasMany(QuantifyRecord::class);
    }
}