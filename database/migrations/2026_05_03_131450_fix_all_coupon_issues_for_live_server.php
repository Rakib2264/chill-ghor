<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ==================== 1. COUPONS টেবিলের বিদ্যমান কলাম চেক করুন ====================
        Schema::table('coupons', function (Blueprint $table) {
            // ✅ শুধু মাত্র বিদ্যমান কলামগুলোর পরে যোগ করুন
            $afterColumn = 'used_count'; // ডিফল্ট
            
            // চেক করুন কোন কলামের পরে যোগ করবেন
            if (Schema::hasColumn('coupons', 'max_uses')) {
                $afterColumn = 'max_uses';
            } elseif (Schema::hasColumn('coupons', 'usage_limit')) {
                $afterColumn = 'usage_limit';
            }
            
            // ✅ নতুন কলাম যোগ করুন (চেক করে)
            if (!Schema::hasColumn('coupons', 'per_user_limit')) {
                $table->integer('per_user_limit')->nullable()->default(1)->after($afterColumn);
            }
            
            if (!Schema::hasColumn('coupons', 'allow_guest')) {
                $table->boolean('allow_guest')->default(false)->after('per_user_limit');
            }
            
            if (!Schema::hasColumn('coupons', 'min_order_amount')) {
                // min_order কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'min_order')) {
                    $table->integer('min_order_amount')->nullable()->after('min_order');
                } else {
                    $table->integer('min_order_amount')->nullable();
                }
            }
            
            if (!Schema::hasColumn('coupons', 'max_discount')) {
                // value কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'value')) {
                    $table->integer('max_discount')->nullable()->after('value');
                } else {
                    $table->integer('max_discount')->nullable();
                }
            }
            
            if (!Schema::hasColumn('coupons', 'is_active')) {
                // active কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'active')) {
                    $table->boolean('is_active')->default(true)->after('active');
                } else {
                    $table->boolean('is_active')->default(true);
                }
            }
            
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                // max_uses কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'max_uses')) {
                    $table->integer('usage_limit')->nullable()->after('max_uses');
                } else {
                    $table->integer('usage_limit')->nullable();
                }
            }
            
            if (!Schema::hasColumn('coupons', 'label')) {
                // code কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'code')) {
                    $table->string('label')->nullable()->after('code');
                } else {
                    $table->string('label')->nullable();
                }
            }
            
            if (!Schema::hasColumn('coupons', 'valid_from')) {
                // expires_at কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'expires_at')) {
                    $table->date('valid_from')->nullable()->after('expires_at');
                } else {
                    $table->date('valid_from')->nullable();
                }
            }
            
            if (!Schema::hasColumn('coupons', 'valid_to')) {
                // valid_from কলামের পর যোগ করুন
                if (Schema::hasColumn('coupons', 'valid_from')) {
                    $table->date('valid_to')->nullable()->after('valid_from');
                } else {
                    $table->date('valid_to')->nullable();
                }
            }
        });
        
        // ✅ ডাটা কপি করুন (শুধু যদি কলাম থাকে)
        if (Schema::hasColumn('coupons', 'min_order') && Schema::hasColumn('coupons', 'min_order_amount')) {
            DB::statement('UPDATE coupons SET min_order_amount = min_order WHERE min_order_amount IS NULL AND min_order IS NOT NULL');
        }
        
        if (Schema::hasColumn('coupons', 'active') && Schema::hasColumn('coupons', 'is_active')) {
            DB::statement('UPDATE coupons SET is_active = active WHERE is_active IS NULL AND active IS NOT NULL');
        }
        
        if (Schema::hasColumn('coupons', 'max_uses') && Schema::hasColumn('coupons', 'usage_limit')) {
            DB::statement('UPDATE coupons SET usage_limit = max_uses WHERE usage_limit IS NULL AND max_uses IS NOT NULL');
        }
        
        // ==================== 2. COUPON_USER_USAGE টেবিল ====================
        if (!Schema::hasTable('coupon_user_usage')) {
            Schema::create('coupon_user_usage', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('coupon_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('order_id');
                $table->decimal('discount_amount', 10, 2);
                $table->timestamps();
                
                // Foreign keys (চেক করে যোগ করুন)
                if (Schema::hasTable('coupons')) {
                    $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
                }
                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (Schema::hasTable('orders')) {
                    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                }
                
                // একই ইউজার একই কুপন একাধিকবার ব্যবহার করতে পারবে না
                $table->unique(['coupon_id', 'user_id']);
                
                $table->index(['coupon_id', 'user_id']);
                $table->index('order_id');
            });
        } else {
            // টেবিল থাকলে শুধু মিসিং কলাম যোগ করুন
            Schema::table('coupon_user_usage', function (Blueprint $table) {
                if (!Schema::hasColumn('coupon_user_usage', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->default(0);
                }
            });
        }
        
        // ==================== 3. ORDERS টেবিল ====================
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->integer('discount')->default(0);
            }
        });
    }

    public function down(): void
    {
        // Foreign keys ড্রপ করুন আগে
        if (Schema::hasTable('coupon_user_usage')) {
            Schema::table('coupon_user_usage', function (Blueprint $table) {
                $table->dropForeign(['coupon_id']);
                $table->dropForeign(['user_id']);
                $table->dropForeign(['order_id']);
            });
            Schema::dropIfExists('coupon_user_usage');
        }
        
        Schema::table('coupons', function (Blueprint $table) {
            $columns = ['per_user_limit', 'allow_guest', 'min_order_amount', 
                       'max_discount', 'is_active', 'usage_limit', 'label',
                       'valid_from', 'valid_to'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('coupons', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};