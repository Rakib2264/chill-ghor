<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::updateOrCreate(['code' => 'WELCOME10'], [
            'type' => 'percent',    
            'value' => 10,
            'min_order' => 300,
            'is_active' => true,
            'max_discount' => 100,
            'usage_limit' => 100,
            'label' => 'Welcome Discount',
        ]);
        
        Coupon::updateOrCreate(['code' => 'FLAT50'], [
            'type' => 'flat',
            'value' => 50,
            'min_order' => 500,
            'is_active' => true,
            'usage_limit' => 50,
            'label' => 'Flat 50 Taka Off',
        ]);
        
        Coupon::updateOrCreate(['code' => 'FREEDEL'], [
            'type' => 'flat',
            'value' => 60,
            'min_order' => 0,
            'is_active' => true,
            'usage_limit' => 200,
            'label' => 'Free Delivery',
        ]);
        
        Coupon::updateOrCreate(['code' => 'SUMMER20'], [
            'type' => 'percent',       
            'value' => 20,
            'min_order' => 1000,
            'is_active' => true,
            'max_discount' => 300,
            'usage_limit' => 30,
            'label' => 'Summer Special 20% Off',
        ]);
    }
}