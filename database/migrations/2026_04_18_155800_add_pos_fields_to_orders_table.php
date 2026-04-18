<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['dine_in', 'takeaway', 'delivery'])->default('dine_in')->after('status');
            $table->string('table_number')->nullable()->after('order_type');
            $table->decimal('discount', 10, 2)->default(0)->after('delivery_fee');
            $table->decimal('tax', 10, 2)->default(0)->after('discount');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total');
            $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
            $table->string('payment_status')->default('pending')->after('due_amount'); // pending, paid, partial
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'order_type', 'table_number', 'discount', 'tax', 
                'paid_amount', 'due_amount', 'payment_status', 'created_by'
            ]);
        });
    }
};