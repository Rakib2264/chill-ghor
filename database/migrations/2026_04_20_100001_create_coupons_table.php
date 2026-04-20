<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('type', ['percent', 'flat'])->default('percent');
                $table->integer('value')->default(0);
                $table->integer('min_order')->default(0);
                $table->integer('max_uses')->nullable();
                $table->integer('used_count')->default(0);
                $table->date('expires_at')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->integer('discount')->default(0)->after('coupon_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'discount')) $table->dropColumn('discount');
            if (Schema::hasColumn('orders', 'coupon_code')) $table->dropColumn('coupon_code');
        });
        Schema::dropIfExists('coupons');
    }
};
