<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'year'];
    
    public function banji()
    {
        return $this->hasMany(Banji::class);
    }
}
