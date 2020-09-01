<?php

namespace Vladmeh\RabbitMQ\Tests;


class ExampleTest extends TestCase
{
    public function testExampleTest()
    {
        $this->assertEquals('request_server', config('rabbit.queues.request'));
    }
}
