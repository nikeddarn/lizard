<?php

namespace App\Providers;

use App\Channels\Providers\AlphaSmsSender;
use App\Contracts\Channels\SmsChannelSenderInterface;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Sms channel sender
        $this->app->bind(SmsChannelSenderInterface::class, AlphaSmsSender::class);
    }
}
