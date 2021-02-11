<?php

namespace Vladmeh\RabbitMQ\Facades;

use Illuminate\Support\Facades\Facade;
use Vladmeh\RabbitMQ\Services\Consumer;
use Vladmeh\RabbitMQ\Services\Producer;

/**
 * @see \Vladmeh\RabbitMQ\Rabbit
 *
 * @method static string rpc(string $message, string $queue, array $parameters = [])
 * @method static Producer publish(string $message, string $exchange, string $routing_key, array $parameters = [])
 * @method static Consumer consume(string $queue, callable $callable, array $parameters = [])
 */
class Rabbit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Rabbit';
    }
}
