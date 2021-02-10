<?php

namespace Vladmeh\RabbitMQ\Contracts;

interface RpcClient
{
    /**
     * @param $message
     * @param $queue
     * @return mixed
     */
    public function request($message, $queue);

    /**
     * @return mixed
     */
    public function getResponse();
    
}