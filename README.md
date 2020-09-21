# vladmeh/rabbitmq-client

Wrapper to [php-amqplib](https://github.com/php-amqplib/php-amqplib) library for publishing and consuming [RabbitMQ](https://www.rabbitmq.com/tutorials/tutorial-six-php.html) messages using [Laravel framework](https://laravel.com/docs/6.x)

## Features
* php v7.2
* [Laravel v6.*](https://laravel.com/docs/6.x)
* [php-amqplib v2.12](https://github.com/php-amqplib/php-amqplib)

## Installation

### Composer

```bash
$ composer require vladmeh/rabbit-client 
```

or add the following to your require part within the composer.json:

```json
{
    "require": {
        "vladmeh/rabbitmq-client": "^1.*"
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
