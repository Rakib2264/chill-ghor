<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'category_id',
        'price',
        'old_price',
        'image',
        'description',
        'long_description',
        'popular',
        'spicy',
        'active',
        'show_on_home',
        'home_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('images/placeholder.png');
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset($this->image);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    protected $guarded = [];

    protected $casts = [
        'popular' => 'boolean',
        'spicy' => 'boolean',
        'active' => 'boolean',
        'show_on_home' => 'boolean',
        'home_order' => 'integer',
        'price' => 'integer',
        'old_price' => 'integer',
    ];

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    public function scopeForHomePage($query, $limit = 8)
    {
        return $query->where('active', true)
            ->where('show_on_home', true)
            ->orderBy('home_order', 'asc')
            ->orderBy('id', 'asc')
            ->limit($limit);
    }
}