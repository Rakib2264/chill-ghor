<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = ['zone_name', 'min_order_for_free', 'delivery_charge', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
