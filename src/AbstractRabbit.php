<?php

namespace Vladmeh\RabbitMQ;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class AbstractRabbit
{
    const CONFIG_KEY = 'rabbit';

    private $properties = [];

    /**
     * @var array
     */
    private $connect_options;

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * RabbitConnection constructor.
     *
     * @param array $options
     *
     * @throws BindingResolutionException
     */
    public function __construct(array $options = [])
    {
        if (config()->has(self::CONFIG_KEY)) {
            $this->properties = config(self::CONFIG_KEY);
        }

        $this->properties = array_replace_recursive($this->properties, $options);
        $this->connection();
    }

    /**
     * @throws BindingResolutionException
     */
    public function connection()
    {
        $this->connect_options = array_merge(
            $this->getProperty('hosts'),
            $this->getProperty('connection')
        );

        $this->connection = app()->make(
            AMQPStreamConnection::class,
            $this->getConnectOptions()
        );

        $this->channel = $this->connection->channel();
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getProperty(string $key)
    {
        return array_key_exists($key, $this->properties) ? $this->properties[$key] : null;
    }

    /**
     * @return array
     */
    public function getConnectOptions(): array
    {
        return $this->connect_options;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array|mixed $properties
     *
     * @return AbstractRabbit
     */
    public function setProperties(array $properties): self
    {
        $this->properties = array_replace_recursive($this->properties, $properties);

        return $this;
    }

    /**
     * @param string $exchange
     * @param array $properties
     *
     * @return mixed|null
     */
    public function exchangeDeclare(string $exchange, array $properties = [])
    {
        $properties = array_replace_recursive(
            $this->getProperty('exchange_declare'),
            $properties
        );

        return $this->getChannel()->exchange_declare(
            $exchange,
            $properties['type'],
            $properties['passive'],
            $properties['durable'],
            $properties['auto_delete'],
            $properties['nowait'],
            $properties['arguments']
        );
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    /**
     * @param string $queue
     * @param array $properties
     *
     * @return array|null
     */
    public function queueDeclare(string $queue, array $properties = [])
    {
        $properties = array_replace_recursive(
            $this->getProperty('queue_declare'),
            $properties
        );

        return $this->getChannel()->queue_declare(
            $queue,
            $properties['passive'],
            $properties['durable'],
            $properties['exclusive'],
            $properties['auto_delete'],
            $properties['nowait'],
            $properties['arguments']
        );
    }

    /**
     * @param string $queue
     * @param string $exchange
     * @param string $routing_key
     * @param array $properties
     *
     * @return mixed|null
     */
    public function queueBind(string $queue, string $exchange, string $routing_key, array $properties = [])
    {
        $properties = array_replace_recursive(
            $this->getProperty('queue_bind'),
            $properties
        );

        return $this->getChannel()->queue_bind(
            $queue,
            $exchange,
            $routing_key,
            $properties['nowait'],
            $properties['arguments']
        );
    }

    /**
     * @throws Exception
     */
    public function disconnect(): void
    {
        $this->getChannel()->close();
        $this->getConnection()->close();
    }

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }
}
