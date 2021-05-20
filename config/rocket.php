<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

return [
    'client'   => [
        'endpoint'   => env('MQ_ROCKET_CLIENT_ENDPOINT'),
        'access_key' => env('MQ_ROCKET_CLIENT_ACCESS_KEY'),
        'secret_key' => env('MQ_ROCKET_CLIENT_SECRET_KEY'),
    ],
    'consumer' => [
        'handler_base_namespace' => env('MQ_ROCKET_CONSUMER_HANDLER_BASE_NAMESPACE'),
        'topic'                  => env('MQ_ROCKET_CONSUMER_TOPIC'),
        'message_tags'           => [
        ],
        'group_id'               => env('MQ_ROCKET_CONSUMER_GROUP_ID'),
        'instance_id'            => env('MQ_ROCKET_CONSUMER_INSTANCE_ID'),
    ],

    'cache' => [
        'enable'   => env('MQ_ROCKET_CACHE_ENABLE', true),
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port'     => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
];
