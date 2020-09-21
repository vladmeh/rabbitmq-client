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
     * @param array $parameters
     * @return Producer
     * @throws BindingResolutionException
     */
    public function publish(string $message, string $exchange, string $routing_key, array $parameters = []): Producer
    {
        return (new Producer($parameters))->publish($message, $exchange, $routing_key);
    }

    /**
     * @param string $queue
     * @param Closure $callback
     * @param array $parameters
     * @return Consumer
     * @throws Exception
     */
    public function consume(string $queue, Closure $callback, $parameters = []): Consumer
    {
        return (new Consumer($parameters))->consume($queue, $callback);
    }

    /**
     * @param string $message
     * @param string $queue
     * @param array $parameters
     * @return string
     * @throws BindingResolutionException
     */
    public function rpc(string $message, string $queue, array $parameters = []): string
    {
        return (new Rpc($parameters))
            ->client($message, $queue)
            ->getResponse();
    }
}
