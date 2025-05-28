<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantifyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'semester_id',
        'quantify_type_id',
        'score',
        'is_active',
        'order',
           ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}