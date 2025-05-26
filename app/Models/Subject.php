<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];
    
    // public function banjis() {
    //     return $this->belongsToMany(Banji::class);
    // }
    public function banjis() {
        return $this->belongsToMany(Banji::class, 'teacher_banji_subject')
            ->withPivot('user_id');
    }
}
