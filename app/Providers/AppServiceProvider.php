<?php

namespace App\Providers;

use App\Support\MailConfig;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        MailConfig::apply();
    }
}
