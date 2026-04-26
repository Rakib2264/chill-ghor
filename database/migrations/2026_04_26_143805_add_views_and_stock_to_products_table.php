<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // stock: -1 = unlimited, 0 = out of stock, 1+ = available
            $table->integer('stock')->default(-1)->after('active');
            $table->unsignedBigInteger('views_count')->default(0)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock', 'views_count']);
        });
    }
};