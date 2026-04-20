<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order',
        'usage_limit', 'used_count', 'expires_at', 'is_active', 
        'max_discount', 'label'  
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'date',
    ];

    public function isValid(int $subtotal = 0): array
    {
        if (!$this->is_active) return [false, 'এই কুপন বর্তমানে সক্রিয় নেই।'];
        if ($this->expires_at && $this->expires_at->isPast()) return [false, 'এই কুপনের মেয়াদ শেষ হয়েছে।'];
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return [false, 'কুপনের ব্যবহারের সীমা পূর্ণ হয়েছে।'];
        if ($this->min_order && $subtotal < $this->min_order) {
            return [false, "ন্যূনতম অর্ডার ৳{$this->min_order} প্রয়োজন।"];
        }
        return [true, null];
    }

    public function calculateDiscount(int $subtotal): int
    {
        if ($this->type === 'flat') {
            return min($subtotal, (int) $this->value);
        }
        // percentage type
        $disc = (int) round($subtotal * $this->value / 100);
        if ($this->max_discount) $disc = min($disc, (int) $this->max_discount);
        return $disc;
    }
    
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }
}