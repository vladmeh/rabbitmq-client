<?php


namespace Vladmeh\RabbitMQ\Services;


use PhpAmqpLib\Message\AMQPMessage;
use Vladmeh\RabbitMQ\AbstractRabbit;

class Producer extends AbstractRabbit
{

    /**
     * @param string $message
     * @param string $exchange
     * @param string $routing_key
     * @return self
     */
    function publish(string $message, string $exchange, string $routing_key): self
    {
        $this->getConnection()->set_close_on_destruct();

        $msg = new AMQPMessage($message, $this->getProperty('message'));

        $this->getChannel()->basic_publish(
            $msg,
            $exchange,
            $routing_key
        );

        return $this;
    }
}
