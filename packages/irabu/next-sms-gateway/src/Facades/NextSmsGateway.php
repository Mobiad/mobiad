<?php

namespace Irabu\NextSmsGateway\Facades;

use Illuminate\Support\Facades\Facade;

class NextSmsGateway extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'next-sms-gateway';
    }
}
