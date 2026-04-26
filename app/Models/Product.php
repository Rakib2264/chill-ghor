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
        'stock',        // -1=unlimited, 0=out of stock, 1+=available
        'views_count',
    ];

    protected $casts = [
        'popular'      => 'boolean',
        'spicy'        => 'boolean',
        'active'       => 'boolean',
        'show_on_home' => 'boolean',
        'price'        => 'integer',
        'old_price'    => 'integer',
        'stock'        => 'integer',
        'views_count'  => 'integer',
        'home_order'   => 'integer',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('is_approved', true)
            ->latest();
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ─── Route ─────────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Image ─────────────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('images/placeholder.png');
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset($this->image);
    }

    // ─── Reviews ───────────────────────────────────────────────────────────────

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    // ─── Stock helpers ─────────────────────────────────────────────────────────

    /**
     * স্টকে আছে কিনা
     */
    public function isInStock(): bool
    {
        return $this->stock === -1 || $this->stock > 0;
    }

    /**
     * স্টক শেষ হয়ে গেছে কিনা
     */
    public function isOutOfStock(): bool
    {
        return $this->stock === 0;
    }

    /**
     * সীমাহীন স্টক কিনা
     */
    public function isUnlimitedStock(): bool
    {
        return $this->stock === -1;
    }

    /**
     * স্টক অবস্থার label
     */
    public function stockLabel(): string
    {
        if ($this->stock === -1) return 'unlimited';
        if ($this->stock === 0)  return 'out_of_stock';
        if ($this->stock <= 5)   return 'low_stock';
        return 'available';
    }

    /**
     * কার্টে সর্বোচ্চ কতটা যোগ করা যাবে
     */
    public function maxCartQty(): int
    {
        return $this->stock === -1 ? 99 : max(0, $this->stock);
    }

    // ─── View helpers ──────────────────────────────────────────────────────────

    /**
     * View count বাড়ান (session দিয়ে একই user বারবার count হবে না)
     */
    public function incrementView(): void
    {
        $sessionKey = 'viewed_product_' . $this->id;
        if (!session()->has($sessionKey)) {
            $this->increment('views_count');
            session()->put($sessionKey, true);
        }
    }

    /**
     * 1.2K / 1.5M format-এ view count
     */
    public function getViewsLabelAttribute(): string
    {
        $v = $this->views_count ?? 0;
        if ($v >= 1_000_000) return round($v / 1_000_000, 1) . 'M';
        if ($v >= 1_000)     return round($v / 1_000, 1) . 'K';
        return (string) $v;
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('stock', -1)->orWhere('stock', '>', 0);
        });
    }

    public function scopeForHomePage($query, int $limit = 12)
    {
        return $query->where('active', true)
            ->where('show_on_home', true)
            ->orderBy('home_order')
            ->orderBy('id')
            ->limit($limit);
    }
}