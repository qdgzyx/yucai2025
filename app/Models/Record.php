<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'banji_id',
        'student_id',
        'date',
        'status'
    ];

    public function banji()
    {
        return $this->belongsTo(Banji::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}