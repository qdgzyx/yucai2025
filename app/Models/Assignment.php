<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    
    protected $fillable = ['subject_id', 'content', 'attachment', 'user_id', 'publish_at', 'deadline','status','reject_reason','approval_time','rejected_at','approval_type','director_id'];
   
    protected $dates = ['publish_at','deadline'];
   
    public function banjis() {
        return $this->belongsToMany(Banji::class)->withTimestamps();
    }
   
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
   
    public function user() {
        return $this->belongsTo(User::class);
    }
}
