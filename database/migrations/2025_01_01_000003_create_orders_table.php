<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->string('customer_name');
            $table->string('phone');
            $table->text('address');
            $table->text('notes')->nullable();
            $table->enum('payment_method', ['cod', 'bkash', 'nagad']);
            $table->string('trx_id')->nullable();
            $table->integer('subtotal');
            $table->integer('delivery_fee')->default(0);
            $table->integer('total');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'delivered', 'cancelled'])
                ->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('line_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
