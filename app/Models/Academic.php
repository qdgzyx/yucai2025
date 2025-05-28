<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Academic extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'start_date', 'end_date', 'is_current'];
    public function semesters()
{
    return $this->hasMany(Semester::class);
}
}
