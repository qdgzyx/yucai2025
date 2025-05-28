<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuantifyType extends Model
{
    use HasFactory;
    
    protected $fillable = ['parent_id', 'code', 'name', 'weight', 'order', 'requirements', 'user_id'];

    /**
     * 获取父级量化类型
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(QuantifyType::class, 'parent_id');
    }

    /**
     * 获取子级量化类型
     */
    public function children(): HasMany
    {
        return $this->hasMany(QuantifyType::class, 'parent_id');
    }
}
