<?php


namespace Vladmeh\RabbitMQ;


use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Vladmeh\RabbitMQ\Services\Consumer;
use Vladmeh\RabbitMQ\Services\Publisher;
use Vladmeh\RabbitMQ\Services\Rpc;

class Rabbit
{
    /**
     * @param string $message
     * @param string $exchange
     * @param string $routing_key
     * @param array $options
     * @return Publisher
     * @throws BindingResolutionException
     */
    public function publish(string $message, string $exchange, string $routing_key, array $options = []): Publisher
    {
        return (new Publisher($options))->publish($message, $exchange, $routing_key);
    }

    /**
     * @param string $queue
     * @param Closure $callback
     * @param array $options
     * @return void
     * @throws BindingResolutionException
     */
    public function consume(string $queue, Closure $callback, $options = []): void
    {
        (new Consumer($options))->consume($queue, $callback);
    }

    /**
     * @param string $message
     * @param string $queue
     * @param array $options
     * @return string
     * @throws BindingResolutionException
     */
    public function rpc(string $message, string $queue, array $options = []): string
    {
        return (new Rpc($options))->handle($message, $queue);
    }
}
