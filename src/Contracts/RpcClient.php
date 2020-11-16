<?php


namespace Vladmeh\RabbitMQ\Contracts;


interface RpcClient
{
    /**
     * @return mixed
     */
    public function getResponse();
}