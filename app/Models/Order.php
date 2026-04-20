<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_no',
        'customer_name',
        'customer_email',
        'phone',
        'address',
        'notes',
        'payment_method',
        'trx_id',
        'subtotal',
        'delivery_fee',
        'total',
        'coupon_code',
        'discount',
        'status',
        'area',
        'delivery_zone',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
