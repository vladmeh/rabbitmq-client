<?php


namespace Vladmeh\RabbitMQ\Tests;


use Orchestra\Testbench\TestCase as BaseTestCase;
use Vladmeh\RabbitMQ\Facades\Rabbit;
use Vladmeh\RabbitMQ\RabbitMQClientProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function getPackageProviders($app)
    {
        return [
            RabbitMQClientProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Rabbit' => Rabbit::class
        ];
    }


}
