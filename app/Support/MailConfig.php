<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class MailConfig
{
    public static function apply(): void
    {
        try {
            if (!Schema::hasTable('settings')) return;
            
            // ✅ সরাসরি cPanel SMTP ফোর্স করুন
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', 'mail.chillghor.com');
            Config::set('mail.mailers.smtp.port', 465);
            Config::set('mail.mailers.smtp.username', 'support@chillghor.com');
            Config::set('mail.mailers.smtp.password', 'chillghor12*');
            Config::set('mail.mailers.smtp.encryption', 'ssl');
            Config::set('mail.from.address', 'support@chillghor.com');
            Config::set('mail.from.name', 'Chill Ghor');
            
        } catch (\Throwable $e) {}
    }
}