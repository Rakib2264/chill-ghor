<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // শুধুমাত্র label কলাম যোগ করুন (যদি না থাকে)
            if (!Schema::hasColumn('coupons', 'label')) {
                $table->string('label')->nullable();
            }
            
            // শুধুমাত্র max_discount কলাম যোগ করুন (যদি না থাকে)
            if (!Schema::hasColumn('coupons', 'max_discount')) {
                $table->integer('max_discount')->nullable();
            }
            
            // usage_limit ইতিমধ্যে আছে কিনা চেক করুন
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                // যদি max_uses কলাম থাকে তাহলে রিনেম করুন
                if (Schema::hasColumn('coupons', 'max_uses')) {
                    $table->renameColumn('max_uses', 'usage_limit');
                } else {
                    $table->integer('usage_limit')->nullable();
                }
            }
            
            // is_active ইতিমধ্যে আছে কিনা চেক করুন
            if (!Schema::hasColumn('coupons', 'is_active')) {
                // যদি active কলাম থাকে তাহলে রিনেম করুন
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