<?php

namespace Vladmeh\RabbitMQ\Tests\Services;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPIOException;
use Vladmeh\RabbitMQ\Facades\Rabbit;
use Vladmeh\RabbitMQ\Services\Producer;
use Vladmeh\RabbitMQ\Tests\TestCase;

class ProducerTest extends TestCase
{
    const QUEUE = 'default';

    /**
     * @var Producer
     */
    public $publish;

    /**
     * @test
     */
    public function it_can_be_publish_message_in_the_existing_queue(): void
    {
        try {
            $this->publish = Rabbit::publish('hello', '', self::QUEUE);
            $this->assertConnection($this->publish);
        } catch (AMQPIOException $e) {
            $this->markTestSkipped(
                'Для теста необходимо установить RabbitMQ'
            );
        }
    }

    /**
     * @param Producer $publish
     */
    protected function assertConnection(Producer $publish): void
    {
        $this->assertInstanceOf(Producer::class, $publish);
        $this->assertInstanceOf(AMQPStreamConnection::class, $publish->getConnection());
        $this->assertInstanceOf(AMQPChannel::class, $publish->getChannel());

        $this->assertTrue($publish->getConnection()->isConnected());
        $this->assertTrue($publish->getChannel()->is_open());
    }

    /**
     * @test
     */
    public function it_can_be_publish_message_in_the_existing_exchange(): void
    {
        try {
            $this->publish = Rabbit::publish('hello', 'amq.direct', self::QUEUE);
            $this->assertConnection($this->publish);
        } catch (AMQPIOException $e) {
            $this->markTestSkipped(
                'Для теста необходимо установить RabbitMQ'
            );
        }
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
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
