<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupQuantifyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'criteria',
        'score',
        'type'
    ];
}