<?php


namespace Vladmeh\RabbitMQ\Services;


use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use Vladmeh\RabbitMQ\AbstractRabbit;

class Rpc extends AbstractRabbit
{
    /**
     * @var string
     */
    private $callback_queue;

    /**
     * @var string
     */
    private $response;

    /**
     * @var string
     */
    private $corr_id;

    /**
     * @param $message
     * @param $queue
     * @return Rpc
     */
    public function client($message, $queue): self
    {
        $this->getConnection()->set_close_on_destruct();
        list($this->callback_queue, ,) = $this->getChannel()->queue_declare(
            '',
            false,
            false,
            true,
            false
        );

        $this->getChannel()->basic_consume(
            $this->callback_queue,
            '',
            false,
            true,
            false,
            false,
            [
                $this,
                'onResponse'
            ]
        );

        return $this->call($message, $queue);
    }

    /**
     * @param string $message
     * @param string $queue
     * @return Rpc|void
     */
    private function call(string $message, string $queue)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            $message,
            [
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            ]
        );

        $this->getChannel()->basic_publish(
            $msg,
            '',
            $queue
        );

        try {
            $this->getChannel()->wait(null, false, Arr::get($this->getConnectOptions(), 'channel_rpc_timeout'));
        } catch (ErrorException $e) {
            Log::error($e->getMessage());
            return abort(500, $e->getMessage());
        }

        return $this;
    }


    /**
     * @param AMQPMessage $rep
     */
    public function onResponse(AMQPMessage $rep): void
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }
}
