<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('email_logs')) {
            Schema::create('email_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('email_template_id')->nullable()->constrained('email_templates')->nullOnDelete();
                $table->string('recipient_email');
                $table->string('recipient_name')->nullable();
                $table->string('subject');
                $table->string('audience')->nullable();
                $table->string('status')->default('sent');
                $table->text('error_message')->nullable();
                $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
                $table->index(['status', 'sent_at']);
                $table->index('recipient_email');
            });
        }
    }
    public function down(): void { Schema::dropIfExists('email_logs'); }
};
