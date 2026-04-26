<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title'         => 'ফুচকা খাওয়ার প্রতিযোগিতা!',
                'body'          => 'আজ বিকেল ৪টায় চিল ঘরে ৫ মিনিটের ফুচকা খাওয়ার প্রতিযোগিতা। বিজয়ীর জন্য বিশেষ পুরস্কার!',
                'emoji'         => '🥟',
                'badge'         => '🔥 আজকের ইভেন্ট',
                'bg_color'      => '#c0392b',
                'text_color'    => '#ffffff',
                'cta_text'      => 'এখনই আসুন →',
                'cta_url'       => '/contact',
                'cta_color'     => '#ffffff',
                'style'         => 'popup',
                'show_on_pages' => ['home'],
                'is_active'     => true,
                'sort_order'    => 1,
            ],
            [
                'title'         => 'কাচ্চি বিরিয়ানিতে ১৫% ছাড়!',
                'body'          => 'আজকের বিশেষ অফার — যেকোনো কাচ্চি অর্ডারে ১৫% ছাড় পাচ্ছেন। সীমিত সময়ের জন্য!',
                'emoji'         => '🍛',
                'badge'         => '⚡ সীমিত অফার',
                'bg_color'      => '#1c0f09',
                'text_color'    => '#ffffff',
                'cta_text'      => 'অর্ডার করুন',
                'cta_url'       => '/menu',
                'cta_color'     => '#f5a623',
                'style'         => 'banner',
                'show_on_pages' => ['home', 'menu'],
                'is_active'     => true,
                'sort_order'    => 2,
            ],
            [
                'title'         => 'ফ্রি ডেলিভারি চলছে!',
                'body'          => '৫০০ টাকার উপরে অর্ডার করলে সম্পূর্ণ ফ্রি ডেলিভারি পাচ্ছেন। আজই অর্ডার করুন!',
                'emoji'         => '🚚',
                'badge'         => '🎉 বিশেষ অফার',
                'bg_color'      => '#1a6b3c',
                'text_color'    => '#ffffff',
                'cta_text'      => 'মেনু দেখুন',
                'cta_url'       => '/menu',
                'cta_color'     => '#ffffff',
                'style'         => 'slide',
                'show_on_pages' => ['all'],
                'is_active'     => true,
                'sort_order'    => 3,
            ],
            [
                'title'         => 'ফ্যামিলি প্যাক অর্ডার করুন',
                'body'          => '৪ জনের ফ্যামিলি প্যাক — কাচ্চি, ভুনা, রুটি, ফুচকা সব মিলিয়ে মাত্র ৮৯৯ টাকায়!',
                'emoji'         => '👨‍👩‍👧‍👦',
                'badge'         => '💰 সেরা মূল্য',
                'bg_color'      => '#2c3e50',
                'text_color'    => '#ffffff',
                'cta_text'      => 'অর্ডার করুন →',
                'cta_url'       => '/menu',
                'cta_color'     => '#f5a623',
                'style'         => 'popup',
                'show_on_pages' => ['menu'],
                'is_active'     => false, // inactive — example
                'sort_order'    => 4,
            ],
        ];

        foreach ($ads as $ad) {
            Advertisement::updateOrCreate(
                ['title' => $ad['title']],
                $ad
            );
        }

        $this->command->info('✅ Advertisement seeded: ' . count($ads) . ' টি বিজ্ঞাপন যোগ হয়েছে।');
    }
}