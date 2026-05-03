<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // WELCOME10 - ইউজার প্রতি 1 বার
        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'], 
            [
                'type' => 'percent',
                'value' => 10,
                'min_order' => 300,
                'min_order_amount' => 300,
                'is_active' => true,  // ✅ active না, is_active ব্যবহার করুন
                'max_discount' => 100,
                'usage_limit' => 100,
                'per_user_limit' => 1,
                'allow_guest' => false,
                'label' => 'Welcome Discount',
                'expires_at' => Carbon::now()->addYear(),
            ]
        );
        
        // FLAT50 - ইউজার প্রতি 3 বার
        Coupon::updateOrCreate(
            ['code' => 'FLAT50'], 
            [
                'type' => 'flat',
                'value' => 50,
                'min_order' => 500,
                'min_order_amount' => 500,
                'is_active' => true,  // ✅ is_active
                'usage_limit' => 50,
                'per_user_limit' => 3,
                'allow_guest' => true,
                'label' => 'Flat 50 Taka Off',
                'expires_at' => Carbon::now()->addMonths(6),
            ]
        );
        
        // FREEDEL - আনলিমিটেড
        Coupon::updateOrCreate(
            ['code' => 'FREEDEL'], 
            [
                'type' => 'flat',
                'value' => 60,
                'min_order' => 0,
                'min_order_amount' => 0,
                'is_active' => true,  // ✅ is_active
                'usage_limit' => 200,
                'per_user_limit' => null,
                'allow_guest' => true,
                'label' => 'Free Delivery',
                'expires_at' => Carbon::now()->addMonths(3),
            ]
        );
        
        // SUMMER20 - ইউজার প্রতি 2 বার
        Coupon::updateOrCreate(
            ['code' => 'SUMMER20'], 
            [
                'type' => 'percent',
                'value' => 20,
                'min_order' => 1000,
                'min_order_amount' => 1000,
                'is_active' => true,  // ✅ is_active
                'max_discount' => 300,
                'usage_limit' => 30,
                'per_user_limit' => 2,
                'allow_guest' => false,
                'label' => 'Summer Special 20% Off',
                'expires_at' => Carbon::now()->addMonths(2),
            ]
        );
    }
}