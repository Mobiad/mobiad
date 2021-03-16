<?php

namespace Irabu\NextSmsGateway\Tests;

use Irabu\NextSmsGateway\Facades\NextSmsGateway;
use Irabu\NextSmsGateway\ServiceProvider;
use Orchestra\Testbench\TestCase;

class NextSmsGatewayTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'next-sms-gateway' => NextSmsGateway::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
