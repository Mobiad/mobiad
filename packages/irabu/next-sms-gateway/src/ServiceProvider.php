<?php

namespace Irabu\NextSmsGateway;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/next-sms-gateway.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('next-sms-gateway.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'next-sms-gateway'
        );

        $this->app->bind('next-sms-gateway', function () {
            return new NextSmsGateway();
        });
    }
}
