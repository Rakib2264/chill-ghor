<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'slug', 'name', 'category_id', 'price', 'old_price',
        'image', 'description', 'long_description', 'popular', 'spicy', 'active',
    ];

    protected $casts = [
        'popular' => 'boolean',
        'spicy'   => 'boolean',
        'active'  => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        // If it's already a full URL, return as-is. Otherwise treat as public path.
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset($this->image);
    }
}
