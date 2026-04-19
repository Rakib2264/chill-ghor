<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name');
            $table->integer('min_order_for_free')->default(0);
            $table->integer('delivery_charge')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default zones
        DB::table('delivery_zones')->insert([
            ['zone_name' => 'ধানমন্ডি', 'min_order_for_free' => 300, 'delivery_charge' => 40, 'is_active' => true],
            ['zone_name' => 'মিরপুর', 'min_order_for_free' => 400, 'delivery_charge' => 50, 'is_active' => true],
            ['zone_name' => 'উত্তরা', 'min_order_for_free' => 500, 'delivery_charge' => 60, 'is_active' => true],
            ['zone_name' => 'গাজীপুর', 'min_order_for_free' => 700, 'delivery_charge' => 80, 'is_active' => true],
            ['zone_name' => 'বনগ্রাম এলাকা', 'min_order_for_free' => 200, 'delivery_charge' => 20, 'is_active' => true],
        ]);
    }
    public function down(): void { Schema::dropIfExists('delivery_zones'); }
};