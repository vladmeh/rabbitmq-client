<?php


namespace Vladmeh\RabbitMQ\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @see \Vladmeh\RabbitMQ\Rabbit
 * @method static rpc(string $message, string $queue, array $parameters = [])
 * @method static publish(string $message, string $exchange, string $routing_key, array $parameters = [])
 * @method static consume(string $queue, callable $callable, array $parameters = [])
 */
class Rabbit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Rabbit';
    }
}
