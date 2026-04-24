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

            Config::set('mail.default', 'smtp');

            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', 465)); // 587 না হয়ে 465
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password'));

            $encryption = Setting::get('mail_encryption', 'ssl');
            Config::set('mail.mailers.smtp.encryption', $encryption ?: null);
            Config::set('mail.from.address', Setting::get('mail_from', 'support@chillghor.com'));
            Config::set('mail.from.name', Setting::get('mail_from_name', 'Chill Ghor'));

            Log::info('Mail configuration applied', [
                'host' => $host,
                'port' => Setting::get('mail_port'),
                'encryption' => $encryption
            ]);
        } catch (\Throwable $e) {
            Log::error('Mail config error: ' . $e->getMessage());
        }
    }
}
