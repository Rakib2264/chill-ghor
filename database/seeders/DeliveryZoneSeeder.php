<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('delivery_zones')->insert([
            ['zone_name' => 'বনগ্রাম বাজার', 'min_order_for_free' => 1500, 'delivery_charge' => 20, 'is_active' => true],
            ['zone_name' => 'বড় মিয়াপুর', 'min_order_for_free' => 1500, 'delivery_charge' => 40, 'is_active' => true],
            ['zone_name' => 'ভাটু মিয়াপুর', 'min_order_for_free' => 1500, 'delivery_charge' => 50, 'is_active' => true],
            ['zone_name' => 'মিয়াপুর হাজীপাড়া', 'min_order_for_free' => 1500, 'delivery_charge' => 50, 'is_active' => true],
            ['zone_name' => 'রসুলপুর', 'min_order_for_free' => 1500, 'delivery_charge' => 45, 'is_active' => true],
            ['zone_name' => 'রাজাপুর', 'min_order_for_free' => 1500, 'delivery_charge' => 60, 'is_active' => true],
            ['zone_name' => 'ভৈরবপুর', 'min_order_for_free' => 1500, 'delivery_charge' => 60, 'is_active' => true],
            ['zone_name' => 'সামান্যপাড়া', 'min_order_for_free' => 1500, 'delivery_charge' => 60, 'is_active' => true],
            ['zone_name' => 'গাংগোহাটি', 'min_order_for_free' => 1500, 'delivery_charge' => 60, 'is_active' => true],
            ['zone_name' => 'গাংগোহাটি চরপাড়া', 'min_order_for_free' => 1500, 'delivery_charge' => 70, 'is_active' => true],
            ['zone_name' => 'মাঝগ্রাম', 'min_order_for_free' => 1500, 'delivery_charge' => 60, 'is_active' => true],
            ['zone_name' => 'মিনিরপাড়া', 'min_order_for_free' => 1500, 'delivery_charge' => 50, 'is_active' => true],
            ['zone_name' => 'বামনডাঙ্গা', 'min_order_for_free' => 1500, 'delivery_charge' => 50, 'is_active' => true],
            ['zone_name' => 'কুমিরগাড়ি', 'min_order_for_free' => 1500, 'delivery_charge' => 50, 'is_active' => true],
            ['zone_name' => 'ভদ্রখোলা', 'min_order_for_free' => 1500, 'delivery_charge' => 70, 'is_active' => true],
            ['zone_name' => 'চরভদ্রখোলা', 'min_order_for_free' => 1500, 'delivery_charge' => 70, 'is_active' => true],
        ]);
    }
}
