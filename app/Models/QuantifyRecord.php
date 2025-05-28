<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantifyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantify_item_id',
        'value',
        'recorded_at'
    ];

    public function quantifyItem()
    {
        return $this->belongsTo(QuantifyItem::class);
    }

    public function scopeForUser($query, $user)
    {
        return $query->whereHas('quantifyItem', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }
}