<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'desc', 
        'class', 
        'vision', 
        'mission', 
        'image', 
        'total_votes',
        'category_id',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithActiveCategory($query)
    {
        return $query->whereHas('category', function($q) {
            $q->where('is_active', true);
        });
    }

    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : 'Tidak ada kategori';
    }

    protected static function boot()
    {
        parent::boot();

        // Update total_votes ketika ada vote baru
        static::updated(function ($candidate) {
            if ($candidate->isDirty('total_votes')) {
            }
        });
    }
}