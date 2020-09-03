<?php

namespace Vladmeh\RabbitMQ\Tests\Services;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Vladmeh\RabbitMQ\Facades\Rabbit;
use Vladmeh\RabbitMQ\Services\Publisher;
use Vladmeh\RabbitMQ\Tests\TestCase;

class PublisherTest extends TestCase
{

    const QUEUE = 'default';

    /**
     * @var Publisher
     */
    public $publish;

    /**
     * @test
     */
    public function it_can_be_publish_message_in_the_existing_queue(): void
    {
        $this->publish = Rabbit::publish('hello', '', self::QUEUE);
        $this->assertConnection($this->publish);
    }

    /**
     * @test
     */
    public function it_can_be_publish_message_in_the_existing_exchange(): void
    {
        $this->publish = Rabbit::publish('hello', 'default', self::QUEUE);
        $this->assertConnection($this->publish);
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->publish->getChannel()->queue_purge(self::QUEUE);
        try {
            $this->publish->disconnect();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
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
