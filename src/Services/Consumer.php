<?php


namespace Vladmeh\RabbitMQ\Services;


use Closure;
use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use Vladmeh\RabbitMQ\AbstractRabbit;

class Consumer extends AbstractRabbit
{
    /**
     * @param string $queue
     * @param Closure $callback
     * @return void
     */
    public function consume(string $queue, Closure $callback): void
    {
        $this->getConnection()->set_close_on_destruct();

        $this->getChannel()->queue_declare(
            $queue,
            $this->getProperty('queue_declare')['passive'],
            $this->getProperty('queue_declare')['durable'],
            $this->getProperty('queue_declare')['exclusive'],
            $this->getProperty('queue_declare')['auto_delete'],
            $this->getProperty('queue_declare')['nowait'],
            $this->getProperty('queue_declare')['arguments']
        );

        $object = $this;

        $this->getChannel()->basic_qos(null, 1, null);

        $this->getChannel()->basic_consume(
            $queue,
            $this->getProperty('consume')['consumer_tag'],
            $this->getProperty('consume')['no_local'],
            $this->getProperty('consume')['no_ack'],
            $this->getProperty('consume')['exclusive'],
            $this->getProperty('consume')['nowait'],
            function ($msg) use ($callback, $object) {
                $callback($msg, $object);
            },
            null,
            $this->getProperty('consumer')['arguments']
        );

        while ($this->getChannel()->callbacks) {
            try {
                $this->getChannel()->wait(null, false, Arr::get($this->getConnectOptions(), 'channel_rpc_timeout'));
            } catch (ErrorException $e) {
                Log::error($e->getMessage());
                abort(500, $e->getMessage());
            }
        }
    }

    /**
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message): void
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

//        if ($message->body === 'quit') {
//            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
//        }
    }
}
