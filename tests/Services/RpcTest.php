<?php

namespace Vladmeh\RabbitMQ\Tests\Services;

use PhpAmqpLib\Exception\AMQPIOException;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Vladmeh\RabbitMQ\Facades\Rabbit;
use Vladmeh\RabbitMQ\Tests\TestCase;

class RpcTest extends TestCase
{
    const QUEUE = 'rpc-queue';

    /**
     * @test
     */
    public function client()
    {
        try {
            $response = Rabbit::rpc('ping', self::QUEUE, ['connection' => [
                'read_write_timeout' => 3.0,
                'channel_rpc_timeout' => 3.0
            ]]);
            $this->assertEquals('pong', $response);
        }
        catch (AMQPIOException $e) {
            $this->markTestSkipped(
                'Для теста необходимо установить RabbitMQ'
            );
        }
        catch (AMQPTimeoutException $e) {
            $this->markTestSkipped(
                'Для теста необходим RabbitMQ RPC server'
            );
        }

    }
}
