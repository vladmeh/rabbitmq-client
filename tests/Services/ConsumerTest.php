<?php

namespace Vladmeh\RabbitMQ\Tests\Services;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Vladmeh\RabbitMQ\Facades\Rabbit;
use Vladmeh\RabbitMQ\Services\Consumer;
use Vladmeh\RabbitMQ\Tests\TestCase;

class ConsumerTest extends TestCase
{
    const QUEUE = 'default';

    /**
     * @var string
     */
    public $response;

    /**
     * @test
     */
    public function it_can_be_listen_to_an_existing_queue(): void
    {
        $consumer = Rabbit::consume(self::QUEUE, function (AMQPMessage $msg) {
            $msg->ack();
            $this->response = $msg->body;
            if ($msg->getMessageCount() === null) {
                $msg->getChannel()->basic_cancel($msg->getConsumerTag());
            }
        });

        $this->assertInstanceOf(Consumer::class, $consumer);
        $this->assertInstanceOf(AMQPStreamConnection::class, $consumer->getConnection());
        $this->assertInstanceOf(AMQPChannel::class, $consumer->getChannel());

        $this->assertTrue($consumer->getConnection()->isConnected());
        $this->assertTrue($consumer->getChannel()->is_open());

        $this->assertEquals('hello', $this->response);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Rabbit::publish('hello', '', self::QUEUE);
    }
}
