<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banji extends Model
{
    use HasFactory;
    
    protected $fillable = ['grade_id', 'name', 'student_count', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function users()
    // {
    // return $this->hasMany(User::class, 'banji_id');
    // }
    public function grade()
    {
        return $this->belongsTo(Grade::class);
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
}
