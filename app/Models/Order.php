<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'invoice_no', 'customer_name', 'phone', 'address', 'notes',
        'payment_method', 'trx_id', 'subtotal', 'delivery_fee', 'total', 'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
