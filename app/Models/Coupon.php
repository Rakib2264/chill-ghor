<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order',
        'min_order_amount',
        'max_uses',
        'usage_limit',
        'used_count',
        'expires_at',
        'active',
        'is_active',
        'max_discount',
        'label',
        'per_user_limit',
        'allow_guest',
        'valid_from',
        'valid_to'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'active' => 'boolean',
        'allow_guest' => 'boolean',
        'expires_at' => 'date',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'per_user_limit' => 'integer',
        'value' => 'integer',
        'max_discount' => 'integer'
    ];

    // ==================== ইউজার ইউসেজ রিলেশন ====================
    public function userUsages()
    {
        return $this->hasMany(CouponUserUsage::class);
    }

    // ==================== ইউজার কতবার ব্যবহার করেছে ====================
    public function userUsedCount($userId = null)
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return 0;

        return $this->userUsages()
            ->where('user_id', $userId)
            ->count();
    }

    // ==================== ইউজার কতবার বাকি আছে ====================
    public function userRemainingUses($userId = null)
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return 0;

        $limit = $this->per_user_limit ?? 1;
        $used = $this->userUsedCount($userId);

        return max(0, $limit - $used);
    }

    // ==================== ইউজার ব্যবহার করতে পারবে কিনা ====================
    public function canUserUse($userId = null)
    {
        $userId = $userId ?? Auth::id();

        // গেস্ট ইউজার
        if (!$userId) {
            return (bool) $this->allow_guest;
        }

        // লগইনড ইউজার
        $limit = $this->per_user_limit ?? 1;
        $used = $this->userUsedCount($userId);

        return $used < $limit;
    }

    // ==================== হেল্পার মেথড ====================
    protected function getMinOrderValue()
    {
        return $this->min_order_amount ?? $this->min_order ?? 0;
    }

    protected function getIsActiveValue()
    {
        return $this->is_active ?? $this->active ?? false;
    }

    protected function getUsageLimitValue()
    {
        return $this->usage_limit ?? $this->max_uses ?? null;
    }

    protected function getExpiryDate()
    {
        return $this->valid_to ?? $this->expires_at ?? null;
    }

    // ==================== ভ্যালিডেশন চেক ====================
    public function isValid(int $subtotal = 0, $userId = null): array
    {
        // বেসিক চেক
        if (!$this->getIsActiveValue()) {
            return [false, 'এই কুপন বর্তমানে সক্রিয় নেই।'];
        }

        $expiryDate = $this->getExpiryDate();
        if ($expiryDate && now()->gt($expiryDate)) {
            return [false, 'এই কুপনের মেয়াদ শেষ হয়েছে।'];
        }

        $usageLimit = $this->getUsageLimitValue();
        if ($usageLimit && $this->used_count >= $usageLimit) {
            return [false, 'কুপনের ব্যবহারের সীমা পূর্ণ হয়েছে।'];
        }

        $minOrder = $this->getMinOrderValue();
        if ($minOrder && $subtotal < $minOrder) {
            return [false, "ন্যূনতম অর্ডার ৳{$minOrder} প্রয়োজন।"];
        }

        // ইউজার ভিত্তিক লিমিট চেক
        if (!$this->canUserUse($userId)) {
            if (!$userId && !$this->allow_guest) {
                return [false, 'এই কুপন ব্যবহার করতে লগইন করুন।'];
            }
            $limit = $this->per_user_limit ?? 1;
            $used = $this->userUsedCount($userId);
            if ($used >= $limit) {
                return [false, "আপনি এই কুপনটি সর্বোচ্চ {$limit} বার ব্যবহার করতে পারবেন।"];
            }
            return [false, 'আপনি এই কুপনটি ব্যবহার করতে পারবেন না।'];
        }

        return [true, null];
    }

    // ==================== ডিসকাউন্ট ক্যালকুলেশন ====================
    public function calculateDiscount(int $subtotal): int
    {
        if ($this->type === 'flat') {
            return min($subtotal, (int) $this->value);
        }

        // percentage type
        $discount = (int) round($subtotal * $this->value / 100);
        if ($this->max_discount) {
            $discount = min($discount, (int) $this->max_discount);
        }
        return $discount;
    }

    // ==================== ইউসেজ ইনক্রিমেন্ট ====================
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    // ==================== কুপন ব্যবহারের রেকর্ড ====================
    public function markAsUsed($orderId, $userId = null, $discountAmount = null)
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return false;

        // চেক করা ইতিমধ্যে ব্যবহার করেছে কিনা
        $existing = CouponUserUsage::where('coupon_id', $this->id)
            ->where('user_id', $userId)
            ->where('order_id', $orderId)
            ->first();

        if ($existing) return false;

        CouponUserUsage::create([
            'coupon_id' => $this->id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount ?? 0
        ]);

        $this->incrementUsage();

        return true;
    }

    // ==================== স্কোপ ====================
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', true)->orWhere('active', true);
        });
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->whereNull('valid_to')
                ->orWhere('expires_at', '>', now())
                ->orWhere('valid_to', '>', now());
        });
    }

    public function scopeAvailableForUser($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        return $query->active()->notExpired()->where(function ($q) use ($userId) {
            if ($userId) {
                $q->whereDoesntHave('userUsages', function ($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                });
            } else {
                $q->where('allow_guest', true);
            }
        });
    }
}
