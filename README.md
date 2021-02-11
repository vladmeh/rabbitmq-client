[![Build Status](https://travis-ci.org/vladmeh/rabbitmq-client.svg?branch=master)](https://travis-ci.org/vladmeh/rabbitmq-client)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/cb2107b6ab3b427cb043a23926e7b4ca)](https://www.codacy.com/gh/vladmeh/rabbitmq-client/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=vladmeh/rabbitmq-client&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://github.styleci.io/repos/297304814/shield?branch=master)](https://github.styleci.io/repos/297304814?branch=master)
[![Latest Stable Version](https://poser.pugx.org/vladmeh/rabbitmq-client/v)](//packagist.org/packages/vladmeh/rabbitmq-client)
[![Total Downloads](https://poser.pugx.org/vladmeh/rabbitmq-client/downloads)](//packagist.org/packages/vladmeh/rabbitmq-client)
[![License](https://poser.pugx.org/vladmeh/rabbitmq-client/license)](//packagist.org/packages/vladmeh/rabbitmq-client)

# vladmeh/rabbitmq-client

Wrapper to [php-amqplib](https://github.com/php-amqplib/php-amqplib) library for publishing and consuming [RabbitMQ](https://www.rabbitmq.com/tutorials/tutorial-six-php.html) messages using [Laravel framework](https://laravel.com/docs/master)

## Features
* php v7.2
* [php-amqplib v2.12](https://github.com/php-amqplib/php-amqplib)
* [Laravel from v6.* and above](https://laravel.com/docs/master)

### Version Compatibility

Laravel  | Rabbit Client
:---------|:----------
6.x      | 1.x
7.x      | 2.x
8.x      | --

## Installation

### Composer

```bash
$ composer require vladmeh/rabbit-client 
```

or add the following to your requirement part within the composer.json:

```json
{
    "require": {
        "vladmeh/rabbitmq-client": "^2.*"
    }
}
```

> Laravel will automatically register service provider (Vladmeh\RabbitMQ\RabbitMQClientProvider) and facade when is installed

### Configure

Add these properties to .env with proper values:

```dotenv
RABBITMQ_HOST=localhost
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
```

If you need advanced configuration properties run:

```bash
$ php artisan vendor:publish --tag=rabbit
```

This command will create a config file `\config\rabbit.php`

## Integration

### Producer
#### Publish a message in the existing queue
```php
Rabbit::publish('message', '', 'queue-name');
```

#### Publish a message in the existing exchange
```php
Rabbit::publish('message', 'exchange-name', 'routing-key');
```

### Publish a message, with custom settings
```php
Rabbit::publish('message', 'amq.fanout', '', [
    'hosts' => [
        'vhosts' => 'vhost3'
    ],
    'message' => [
        'content_type' => 'application/json',
    ],
    'exchange_declare' => [
        'type' => 'fanout',
        'auto_delete' => true,
    ]
]);
```

> All default settings are defined in `\config\rabbit.php`.

### Consumer
#### Consume messages to an existing queue
```php
Rabbit::consume('queue-name', function (AMQPMessage $msg) {
    $msg->ack();
    var_dump($msg->body);
    if ($msg->getMessageCount() === null) {
        $msg->getChannel()->basic_cancel($msg->getConsumerTag());
    }
});
```

### RPC client
```php
$response = Rabbit::rpc('message', 'queue-name', ['connection' => [
    'read_write_timeout' => 10.0,
    'channel_rpc_timeout' => 10.0
]]);

var_dump($response);
```
