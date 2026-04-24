<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class MailConfig
{
    public static function apply(): void
    {
        try {
            if (!Schema::hasTable('settings')) return;

            $host = Setting::get('mail_host');
            if (!$host) return;

            // ✅ মেইল ড্রাইভার smtp সেট করুন
            Config::set('mail.default', 'smtp');

            // ✅ SMTP কনফিগারেশন সেট করুন
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', 465)); // 587 না হয়ে 465
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password'));

            // ✅ এনক্রিপশন সঠিকভাবে সেট করুন (ssl বা tls)
            $encryption = Setting::get('mail_encryption', 'ssl');
            Config::set('mail.mailers.smtp.encryption', $encryption ?: null);

            // ✅ ফ্রম এড্রেস সেট করুন
            Config::set('mail.from.address', Setting::get('mail_from', 'support@chillghor.com'));
            Config::set('mail.from.name', Setting::get('mail_from_name', 'Chill Ghor'));

            // ✅ ডিবাগ লগ (ঐচ্ছিক)
            Log::info('Mail configuration applied', [
                'host' => $host,
                'port' => Setting::get('mail_port'),
                'encryption' => $encryption
            ]);
        } catch (\Throwable $e) {
            // সাইলেন্টলি ফেল করবে
            Log::error('Mail config error: ' . $e->getMessage());
        }
    }
}
