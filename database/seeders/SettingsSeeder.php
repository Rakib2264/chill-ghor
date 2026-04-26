<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ==================== General ====================
            ['key' => 'site_name',        'value' => 'চিল ঘর',                          'type' => 'text',     'group' => 'general',       'label' => 'সাইটের নাম'],
            ['key' => 'site_title',       'value' => 'চিল ঘর — চা–কফির আড্ডা',          'type' => 'text',     'group' => 'general',       'label' => 'SEO টাইটেল'],
            ['key' => 'site_description', 'value' => 'বনগ্রাম স্কুল ও কলেজের সামনে',    'type' => 'textarea', 'group' => 'general',       'label' => 'SEO ডেসক্রিপশন'],
            ['key' => 'logo',             'value' => 'images/logo/logo.png',             'type' => 'image',    'group' => 'general',       'label' => 'লোগো'],

            // ==================== Contact ====================
            ['key' => 'phone',            'value' => '01729542809',                      'type' => 'text',     'group' => 'contact',       'label' => 'ফোন নম্বর'],
            ['key' => 'email',            'value' => 'hello@chillghor.com',              'type' => 'text',     'group' => 'contact',       'label' => 'ইমেইল'],
            ['key' => 'address',          'value' => 'বনগ্রাম স্কুল ও কলেজের সামনে',    'type' => 'textarea', 'group' => 'contact',       'label' => 'ঠিকানা'],
            ['key' => 'opening_hours',    'value' => 'সকাল ৭টা – রাত ১১টা',             'type' => 'text',     'group' => 'contact',       'label' => 'খোলার সময়'],
            ['key' => 'contact_phone',    'value' => '01729542809',                      'type' => 'text',     'group' => 'contact',       'label' => 'অতিরিক্ত ফোন'],
            ['key' => 'contact_email',    'value' => 'info@chillghor.com',               'type' => 'text',     'group' => 'contact',       'label' => 'অতিরিক্ত ইমেইল'],
            ['key' => 'contact_address',  'value' => 'বনগ্রাম স্কুল ও কলেজের সামনে',    'type' => 'textarea', 'group' => 'contact',       'label' => 'পূর্ণ ঠিকানা'],

            // ==================== Order ====================
            ['key' => 'delivery_charge',  'value' => '60',                               'type' => 'number',   'group' => 'order',         'label' => 'ডেলিভারি চার্জ (৳)'],
            ['key' => 'free_delivery_min','value' => '500',                              'type' => 'number',   'group' => 'order',         'label' => 'ফ্রি ডেলিভারি মিনিমাম (৳)'],
            ['key' => 'minimum_order',    'value' => '100',                              'type' => 'number',   'group' => 'order',         'label' => 'ন্যূনতম অর্ডার (৳)'],

            // ==================== Delivery ====================
            ['key' => 'delivery_time',         'value' => '২০-৩০ মিনিট',  'type' => 'text', 'group' => 'delivery', 'label' => 'ডেলিভারি সময়'],
            ['key' => 'delivery_time_label',   'value' => 'দ্রুত ডেলিভারি','type' => 'text', 'group' => 'delivery', 'label' => 'ডেলিভারি সময় লেবেল'],
            ['key' => 'free_delivery_text',    'value' => 'ফ্রি ডেলিভারি', 'type' => 'text', 'group' => 'delivery', 'label' => 'ফ্রি ডেলিভারি টেক্সট'],
            ['key' => 'free_delivery_condition','value' => '৫০০৳+ অর্ডারে','type' => 'text', 'group' => 'delivery', 'label' => 'ফ্রি ডেলিভারি শর্ত'],

            // ==================== Promo ====================
            ['key' => 'promo_bar_enabled',  'value' => '1',                                              'type' => 'boolean', 'group' => 'promo', 'label' => 'প্রোমো বার সক্রিয়'],
            ['key' => 'promo_bar_text',     'value' => '🎉 আজকের অফার — কাচ্চি বিরিয়ানিতে ১৫% ছাড়!', 'type' => 'text',    'group' => 'promo', 'label' => 'প্রোমো বার টেক্সট'],
            ['key' => 'promo_bar_link',     'value' => '/menu',                                          'type' => 'text',    'group' => 'promo', 'label' => 'প্রোমো বার লিংক'],
            ['key' => 'promo_button_text',  'value' => 'অর্ডার করুন →',                                 'type' => 'text',    'group' => 'promo', 'label' => 'প্রোমো বাটন টেক্সট'],

            // ==================== Social ====================
            ['key' => 'facebook',        'value' => 'https://facebook.com/chillghor',    'type' => 'text', 'group' => 'social', 'label' => 'ফেসবুক'],
            ['key' => 'instagram',       'value' => 'https://instagram.com/chillghor',   'type' => 'text', 'group' => 'social', 'label' => 'ইন্সটাগ্রাম'],
            ['key' => 'social_twitter',  'value' => 'https://twitter.com/chillghor',     'type' => 'text', 'group' => 'social', 'label' => 'টুইটার'],
            ['key' => 'social_youtube',  'value' => 'https://youtube.com/chillghor',     'type' => 'text', 'group' => 'social', 'label' => 'ইউটিউব'],
            ['key' => 'social_whatsapp', 'value' => 'https://wa.me/8801729542809',       'type' => 'text', 'group' => 'social', 'label' => 'হোয়াটসঅ্যাপ'],

            // ==================== Footer ====================
            ['key' => 'footer_description', 'value' => 'ঘরের স্বাদ, রেস্টুরেন্টে। গ্রামীণ পরিবেশে শহরের আধুনিক ফিল।', 'type' => 'textarea', 'group' => 'footer', 'label' => 'ফুটার বিবরণ'],
            ['key' => 'footer_copyright',   'value' => 'চিল ঘর রেস্টুরেন্ট। সর্বস্বত্ব সংরক্ষিত।',                  'type' => 'text',     'group' => 'footer', 'label' => 'কপিরাইট টেক্সট'],

            // ==================== SEO ====================
            ['key' => 'meta_keywords',    'value' => 'চিল ঘর, রেস্টুরেন্ট, বিরিয়ানি, ফুচকা, চা, কফি', 'type' => 'text',     'group' => 'seo', 'label' => 'মেটা কীওয়ার্ড'],
            ['key' => 'meta_author',      'value' => 'চিল ঘর',                                           'type' => 'text',     'group' => 'seo', 'label' => 'মেটা অথর'],
            ['key' => 'google_analytics', 'value' => '',                                                  'type' => 'textarea', 'group' => 'seo', 'label' => 'গুগল অ্যানালিটিক্স কোড'],

            // ==================== Payment ====================
            ['key' => 'bkash_number',  'value' => '01729542809', 'type' => 'text', 'group' => 'payment', 'label' => 'বিক্যাশ নম্বর'],
            ['key' => 'nagad_number',  'value' => '01729542809', 'type' => 'text', 'group' => 'payment', 'label' => 'নগদ নম্বর'],
            ['key' => 'rocket_number', 'value' => '01729542809', 'type' => 'text', 'group' => 'payment', 'label' => 'রকেট নম্বর'],

            // ==================== Notification ====================
            ['key' => 'order_notification_email', 'value' => 'orders@chillghor.com', 'type' => 'text', 'group' => 'notification', 'label' => 'অর্ডার নোটিফিকেশন ইমেইল'],
            ['key' => 'admin_phone',              'value' => '01729542809',          'type' => 'text', 'group' => 'notification', 'label' => 'অ্যাডমিন ফোন'],

            // ==================== Email (SMTP) ====================
            ['key' => 'mail_host',       'value' => 'mail.chillghor.com',    'type' => 'text', 'group' => 'email', 'label' => 'SMTP Host'],
            ['key' => 'mail_port',       'value' => '465',                   'type' => 'text', 'group' => 'email', 'label' => 'SMTP Port'],
            ['key' => 'mail_username',   'value' => 'support@chillghor.com', 'type' => 'text', 'group' => 'email', 'label' => 'SMTP Username'],
            ['key' => 'mail_password',   'value' => '',                      'type' => 'text', 'group' => 'email', 'label' => 'SMTP Password'],
            ['key' => 'mail_encryption', 'value' => 'ssl',                   'type' => 'text', 'group' => 'email', 'label' => 'Encryption'],
            ['key' => 'mail_from',       'value' => 'support@chillghor.com', 'type' => 'text', 'group' => 'email', 'label' => 'From Address'],
            ['key' => 'mail_from_name',  'value' => 'Chill Ghor',            'type' => 'text', 'group' => 'email', 'label' => 'From Name'],

            // ==================== Hero ====================
            ['key' => 'hero_tagline',              'value' => '☕ চা–কফির আড্ডা, ফাস্ট ফুডের আসল স্বাদ', 'type' => 'text',     'group' => 'hero', 'label' => 'হিরো ট্যাগলাইন'],
            ['key' => 'hero_title',                'value' => 'ঘরের স্বাদ,<br><span style="color:#f5a623">রেস্টুরেন্টে</span>', 'type' => 'textarea', 'group' => 'hero', 'label' => 'হিরো টাইটেল'],
            ['key' => 'hero_description',          'value' => '🌿 গ্রামীণ পরিবেশে শহরের আধুনিক ফিল।',   'type' => 'textarea', 'group' => 'hero', 'label' => 'হিরো বিবরণ'],
            ['key' => 'hero_button_text',          'value' => 'মেনু দেখুন',                              'type' => 'text',     'group' => 'hero', 'label' => 'হিরো বাটন'],
            ['key' => 'hero_secondary_button_text','value' => 'আমাদের গল্প →',                           'type' => 'text',     'group' => 'hero', 'label' => 'হিরো সেকেন্ডারি বাটন'],
            ['key' => 'hero_image',                'value' => 'images/food/hero.jpeg',                   'type' => 'image',    'group' => 'hero', 'label' => 'হিরো ইমেজ'],
            ['key' => 'hero_stats', 'value' => json_encode([
                ['৪০+', 'পদের মেনু'],
                ['১০K+', 'খুশি গ্রাহক'],
                ['⭐৪.৮', 'গ্রাহক রেটিং'],
            ]), 'type' => 'json', 'group' => 'hero', 'label' => 'হিরো স্ট্যাটস'],

            // ==================== Categories ====================
            ['key' => 'categories_badge',     'value' => 'ক্যাটাগরি',           'type' => 'text', 'group' => 'categories', 'label' => 'ক্যাটাগরি ব্যাজ'],
            ['key' => 'categories_title',     'value' => 'আপনার পছন্দ বেছে নিন','type' => 'text', 'group' => 'categories', 'label' => 'ক্যাটাগরি টাইটেল'],
            ['key' => 'categories_link_text', 'value' => 'সব দেখুন →',           'type' => 'text', 'group' => 'categories', 'label' => 'লিংক টেক্সট'],

            // ==================== Popular ====================
            ['key' => 'popular_badge',     'value' => '🏆 জনপ্রিয়',             'type' => 'text', 'group' => 'popular', 'label' => 'পপুলার ব্যাজ'],
            ['key' => 'popular_title',     'value' => 'আজকের সবচেয়ে বেশি অর্ডার','type' => 'text','group' => 'popular', 'label' => 'পপুলার টাইটেল'],
            ['key' => 'popular_link_text', 'value' => 'সব দেখুন →',              'type' => 'text', 'group' => 'popular', 'label' => 'লিংক টেক্সট'],

            // ==================== Features ====================
            ['key' => 'features', 'value' => json_encode([
                ['🚚', 'ফ্রি ডেলিভারি',     '৫০০ টাকার উপরে অর্ডারে'],
                ['⏱️', '২০-৩০ মিনিট',       'দ্রুত ডেলিভারি গ্যারান্টি'],
                ['💰', 'ক্যাশ অন ডেলিভারি', 'খাবার পেয়ে পেমেন্ট করুন'],
            ]), 'type' => 'json', 'group' => 'features', 'label' => 'ফিচার সমূহ'],

            // ==================== CTA ====================
            ['key' => 'cta_badge',                 'value' => 'পরিবারের জন্য অর্ডার করুন',            'type' => 'text',     'group' => 'cta', 'label' => 'CTA ব্যাজ'],
            ['key' => 'cta_title',                 'value' => 'ফ্যামিলি প্যাক ও ইভেন্ট ক্যাটারিং',   'type' => 'text',     'group' => 'cta', 'label' => 'CTA টাইটেল'],
            ['key' => 'cta_description',           'value' => 'কর্পোরেট লাঞ্চ, জন্মদিন, বিয়ের অনুষ্ঠান — সব আয়োজনে চিল ঘর।', 'type' => 'textarea', 'group' => 'cta', 'label' => 'CTA বিবরণ'],
            ['key' => 'cta_button_text',           'value' => 'মেনু দেখুন',                            'type' => 'text',     'group' => 'cta', 'label' => 'CTA বাটন'],
            ['key' => 'cta_secondary_button_text', 'value' => 'যোগাযোগ করুন',                          'type' => 'text',     'group' => 'cta', 'label' => 'CTA সেকেন্ডারি বাটন'],

            // ==================== About Page ====================
            ['key' => 'about_title',        'value' => 'আমাদের সম্পর্কে — চিল ঘর',  'type' => 'text',     'group' => 'about', 'label' => 'পেজ টাইটেল'],
            ['key' => 'about_hero_badge',   'value' => 'আমাদের সম্পর্কে',             'type' => 'text',     'group' => 'about', 'label' => 'হিরো ব্যাজ'],
            ['key' => 'about_hero_title',   'value' => 'যেখানে স্বাদ মিশে যায়',       'type' => 'text',     'group' => 'about', 'label' => 'হিরো টাইটেল'],
            ['key' => 'about_hero_subtitle','value' => 'গ্রামীণ আন্তরিকতায়, শহুরে স্বাদে','type' => 'text','group' => 'about', 'label' => 'হিরো সাবটাইটেল'],
            ['key' => 'about_hero_image',   'value' => 'images/about/hero.jpg',        'type' => 'image',    'group' => 'about', 'label' => 'হিরো ইমেজ'],
            ['key' => 'about_story',        'value' => '<p>১৯৯৩ সালে নানার ছোট্ট রান্নাঘর থেকে শুরু।</p>', 'type' => 'textarea', 'group' => 'about', 'label' => 'গল্প/বিবরণ'],
            ['key' => 'about_values', 'value' => json_encode([
                ['❤️', 'ঘরোয়া রেসিপি',        'মা-নানির হাতের স্বাদ অপরিবর্তিত।'],
                ['🌿', 'ফ্রেশ উপকরণ',          'প্রতিদিন তাজা মাছ, মাংস ও সবজি।'],
                ['👨‍🍳', '৩০+ বছরের অভিজ্ঞতা', 'তিন প্রজন্মের রন্ধনশিল্পী।'],
                ['😊', '১০,০০০+ সন্তুষ্ট গ্রাহক','আস্থা ও ভালোবাসা।'],
            ]), 'type' => 'json', 'group' => 'about', 'label' => 'ভ্যালু কার্ড'],

            // ==================== Contact Page ====================
            ['key' => 'contact_title',          'value' => 'যোগাযোগ — চিল ঘর',                    'type' => 'text',     'group' => 'contact_page', 'label' => 'পেজ টাইটেল'],
            ['key' => 'contact_heading',        'value' => 'আমাদের সাথে কথা বলুন',                 'type' => 'text',     'group' => 'contact_page', 'label' => 'পেজ হেডিং'],
            ['key' => 'contact_subheading',     'value' => 'অর্ডার, ক্যাটারিং, ফিডব্যাক — যেকোনো প্রয়োজনে জানান।', 'type' => 'textarea', 'group' => 'contact_page', 'label' => 'সাবহেডিং'],
            ['key' => 'contact_form_title',     'value' => 'বার্তা পাঠান',                         'type' => 'text',     'group' => 'contact_page', 'label' => 'ফর্ম টাইটেল'],
            ['key' => 'contact_button_text',    'value' => 'পাঠান →',                              'type' => 'text',     'group' => 'contact_page', 'label' => 'সাবমিট বাটন'],
            ['key' => 'contact_success_message','value' => '✅ আপনার বার্তা পাঠানো হয়েছে।',        'type' => 'textarea', 'group' => 'contact_page', 'label' => 'সফলতার বার্তা'],
            ['key' => 'contact_info_cards', 'value' => json_encode([
                ['📍', 'ঠিকানা',      'বনগ্রাম স্কুল ও কলেজের সামনে'],
                ['📞', 'ফোন',         '01729542809'],
                ['✉️', 'ইমেইল',      'hello@chillghor.com'],
                ['🕐', 'খোলার সময়', 'সকাল ৭টা – রাত ১১টা'],
            ]), 'type' => 'json', 'group' => 'contact_page', 'label' => 'যোগাযোগ তথ্য কার্ড'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ Settings seeded: ' . count($settings) . ' টি সেটিংস যোগ হয়েছে।');
    }
}