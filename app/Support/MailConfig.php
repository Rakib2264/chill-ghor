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
            $host = Setting::get('mail_host');
            if (!$host) return;
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', 587));
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password'));
            Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', 'tls') ?: null);
            Config::set('mail.from.address', Setting::get('mail_from', 'noreply@example.com'));
            Config::set('mail.from.name', Setting::get('mail_from_name', 'Chill Ghor'));
        } catch (\Throwable $e) {}
    }
}
