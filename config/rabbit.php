<?php

return [
    'hosts' => [
        'host' => env('RABBITMQ_HOST', 'localhost'),
        'port' => env('RABBITMQ_PORT', 5672),
        'user' => env('RABBITMQ_USER', 'guest'),
        'password' => env('RABBITMQ_PASSWORD', 'guest'),
        'vhost' => env('RABBITMQ_VHOST', '/')
    ],
    'connection' => [],

    'exchange_declare' => [
        'type'              => 'direct',
        'passive'           => false,
        'durable'           => true,
        'auto_delete'       => false,
        'nowait'            => false,
        'arguments'         => [],
    ],

    'queue_declare' => [
        'passive'           => false,
        'durable'           => true,
        'exclusive'         => false,
        'auto_delete'       => false,
        'nowait'            => false,
        'arguments'         => [],
    ],

    'queue_bind' => [
        'nowait'            => false,
        'arguments'         => [],
    ],

    'consume' => [
        'consumer_tag'      => '',
        'no_local'          => false,
        'no_ack'            => false,
        'exclusive'         => true,
        'nowait'            => false,
        'arguments'         => [],
    ],

    'qos' => [
        'prefetch_size'     => null,
        'prefetch_count'    => 1,
        'a_global'          => null
    ],

    'message' => [
        'content_type'      => 'text/plain',
        'delivery_mode'     => 2
    ],

    'queues' => [
        'request' => env('RABBIT_REQUEST_QUEUE', 'request_server'),
        'mail' => env('RABBIT_MAIL_QUEUE', 'sender'),
    ],
];
