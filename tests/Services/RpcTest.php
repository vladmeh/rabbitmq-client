<?php

namespace Vladmeh\RabbitMQ\Tests\Services;

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

        $response = Rabbit::rpc('ping', self::QUEUE, ['connection' => [
            'read_write_timeout' => 10.0,
            'channel_rpc_timeout' => 10.0
        ]]);

        $this->assertEquals('pong', $response);
    }
}
