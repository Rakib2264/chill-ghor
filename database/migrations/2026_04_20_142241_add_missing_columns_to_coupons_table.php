<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'label')) {
                $table->string('label')->nullable();
            }
            
            if (!Schema::hasColumn('coupons', 'max_discount')) {
                $table->integer('max_discount')->nullable();
            }
            
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                if (Schema::hasColumn('coupons', 'max_uses')) {
                    $table->renameColumn('max_uses', 'usage_limit');
                } else {
                    $table->integer('usage_limit')->nullable();
                }
            }
            
            if (!Schema::hasColumn('coupons', 'is_active')) {
                if (Schema::hasColumn('coupons', 'active')) {
                    $table->renameColumn('active', 'is_active');
                } else {
                    $table->boolean('is_active')->default(true);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['label', 'max_discount']);
        });
    }
};