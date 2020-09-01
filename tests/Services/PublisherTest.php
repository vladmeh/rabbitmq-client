<?php

namespace Vladmeh\RabbitMQ\Tests\Services;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Vladmeh\RabbitMQ\Facades\Rabbit;
use Vladmeh\RabbitMQ\Services\Publisher;
use Vladmeh\RabbitMQ\Tests\TestCase;

class PublisherTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_publish_message_in_the_existing_queue(): void
    {
        $publish = Rabbit::publish('hello', '', 'default');

        $this->assertConnection($publish);
    }

    /**
     * @test
     */
    public function it_can_be_publish_message_in_the_existing_exchange(): void
    {
        $publish = Rabbit::publish('hello', 'default', 'default');

        $this->assertConnection($publish);
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param Publisher $publish
     */
    protected function assertConnection(Publisher $publish): void
    {
        $this->assertInstanceOf(Publisher::class, $publish);
        $this->assertInstanceOf(AMQPStreamConnection::class, $publish->getConnection());
        $this->assertInstanceOf(AMQPChannel::class, $publish->getChannel());

        $this->assertTrue($publish->getConnection()->isConnected());
        $this->assertTrue($publish->getChannel()->is_open());
    }
}
