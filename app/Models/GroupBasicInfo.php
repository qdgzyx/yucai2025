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

    public function banji()
    {
        return $this->belongsTo(Banji::class);
    }
}