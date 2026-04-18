<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $settings = [
            ['key' => 'site_name', 'value' => 'চিল ঘর', 'type' => 'text', 'group' => 'general', 'label' => 'সাইটের নাম'],
            ['key' => 'site_title', 'value' => 'চিল ঘর — চা–কফির আড্ডা', 'type' => 'text', 'group' => 'general', 'label' => 'SEO টাইটেল'],
            ['key' => 'site_description', 'value' => 'বনগ্রাম স্কুল ও কলেজের সামনে', 'type' => 'textarea', 'group' => 'general', 'label' => 'SEO ডেসক্রিপশন'],
            ['key' => 'logo', 'value' => 'images/logo/logo.png', 'type' => 'image', 'group' => 'general', 'label' => 'লোগো'],
            ['key' => 'phone', 'value' => '+৮৮০ ১৭১১-০০০০০০', 'type' => 'text', 'group' => 'contact', 'label' => 'ফোন'],
            ['key' => 'email', 'value' => 'hello@chillghor.com', 'type' => 'text', 'group' => 'contact', 'label' => 'ইমেইল'],
            ['key' => 'address', 'value' => 'বনগ্রাম স্কুল ও কলেজের সামনে', 'type' => 'textarea', 'group' => 'contact', 'label' => 'ঠিকানা'],
            ['key' => 'opening_hours', 'value' => 'সকাল ৭টা – রাত ১১টা', 'type' => 'text', 'group' => 'contact', 'label' => 'খোলার সময়'],
            ['key' => 'delivery_charge', 'value' => '60', 'type' => 'number', 'group' => 'order', 'label' => 'ডেলিভারি চার্জ'],
            ['key' => 'free_delivery_min', 'value' => '500', 'type' => 'number', 'group' => 'order', 'label' => 'ফ্রি ডেলিভারি'],
            ['key' => 'promo_bar_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'promo', 'label' => 'প্রোমো বার'],
            ['key' => 'promo_bar_text', 'value' => '🎉 আজকের অফার — কাচ্চি বিরিয়ানিতে ১৫% ছাড়!', 'type' => 'text', 'group' => 'promo', 'label' => 'প্রোমো টেক্সট'],
            ['key' => 'facebook', 'value' => '#', 'type' => 'text', 'group' => 'social', 'label' => 'ফেসবুক'],
            ['key' => 'instagram', 'value' => '#', 'type' => 'text', 'group' => 'social', 'label' => 'ইন্সটাগ্রাম'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};