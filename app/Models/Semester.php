<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;
    
    protected $fillable = ['academic_id', 'name', 'start_date', 'end_date', 'is_current'];

    public function academic()
    {
        return $this->belongsTo(Academic::class);
    }

    /**
     * 定义与QuantifyItem的一对多关系
     */
    public function quantifyItems(): HasMany
    {
        return $this->hasMany(QuantifyItem::class);
    }

    /**
     * 只查询当前学期
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }
}