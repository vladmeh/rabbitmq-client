<?php

namespace Vladmeh\RabbitMQ;

use Closure;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Vladmeh\RabbitMQ\Services\Consumer;
use Vladmeh\RabbitMQ\Services\Producer;
use Vladmeh\RabbitMQ\Services\Rpc;

class Rabbit
{
    /**
     * @param string $message
     * @param string $exchange
     * @param string $routing_key
     * @param array  $parameters
     *
     * @throws BindingResolutionException
     *
     * @return Producer
     */
    public function publish(string $message, string $exchange, string $routing_key, array $parameters = []): Producer
    {
        return (new Producer($parameters))->publish($message, $exchange, $routing_key);
    }

    /**
     * @param string  $queue
     * @param Closure $callback
     * @param array   $parameters
     *
     * @throws Exception
     *
     * @return Consumer
     */
    public function consume(string $queue, Closure $callback, $parameters = []): Consumer
    {
        return (new Consumer($parameters))->consume($queue, $callback);
    }

    /**
     * @param string $message
     * @param string $queue
     * @param array  $parameters
     *
     * @throws BindingResolutionException
     *
     * @return string
     */
    public function rpc(string $message, string $queue, array $parameters = []): string
    {
        return (new Rpc($parameters))
            ->client($message, $queue)
            ->getResponse();
    }
}
