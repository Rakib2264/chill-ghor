<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('emoji')->default('🎉');
            $table->string('badge')->nullable();
            $table->string('bg_color')->default('#c0392b');
            $table->string('text_color')->default('#ffffff');
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('cta_color')->default('#ffffff');
            $table->enum('style', ['banner', 'popup', 'slide'])->default('popup');
            $table->json('show_on_pages')->nullable(); // ['home','menu','all']
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};