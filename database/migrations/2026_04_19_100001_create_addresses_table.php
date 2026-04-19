<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('addresses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('label', 40)->default('Home'); // Home / Office / Other
            $t->string('recipient_name', 120);
            $t->string('phone', 30);
            $t->string('area', 120)->nullable();
            $t->text('address_line');
            $t->boolean('is_default')->default(false);
            $t->timestamps();
            $t->index(['user_id', 'is_default']);
        });
    }
    public function down(): void { Schema::dropIfExists('addresses'); }
};
