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
     * @return self
     */
    public function consume(string $queue, Closure $callback): self
    {
        $this->getConnection()->set_close_on_destruct();

        $this->queueDeclare($queue);

        $this->getChannel()->basic_qos(
            $this->getProperty('qos')['prefetch_size'],
            $this->getProperty('qos')['prefetch_count'],
            $this->getProperty('qos')['a_global']
        );

        $object = $this;
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

        return $this;
    }

    /**
     * @param AMQPMessage $message
     * @param bool $cancel
     */
    public function acknowledge(AMQPMessage $message, bool $cancel = false): void
    {
        $message->ack();
        if ($cancel && $message->getMessageCount() === null) {
            $message->getChannel()->basic_cancel($message->getConsumerTag());
        }
    }
}
