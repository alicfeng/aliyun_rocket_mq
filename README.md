<h1 align="center">
    <a href="https://github.com/alicfeng/aliyun_rocket_mq">
        阿里云RocketMQ增强组件
    </a>
</h1>
<p align="center">
    基于阿里云官方SDK增强组件
     <br>
    更加优雅的应用姿势、更加灵活的动态配置，让应用层服务组件更加标准规范
</p>
<p align="center">
    <a href="https://travis-ci.com/github/alicfeng/aliyun_rocket_mq">
        <img src="https://travis-ci.com/alicfeng/aliyun_rocket_mq.svg?branch=master" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/alicfeng/aliyun_rocket_mq">
        <img src="https://poser.pugx.org/alicfeng/aliyun_rocket_mq/v/stable.svg" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/alicfeng/aliyun_rocket_mq">
        <img src="https://poser.pugx.org/alicfeng/aliyun_rocket_mq/d/total.svg" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/alicfeng/aliyun_rocket_mq">
        <img src="https://poser.pugx.org/alicfeng/aliyun_rocket_mq/license.svg" alt="License">
    </a>
</p>


## 安装

```
composer require alicfeng/aliyun_rocket_mq -vvv
```


## 配置
```php

$config = [
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
    ]
];
```


## 使用

```php
use MQ\Model\TopicMessage;
use Samego\RocketMQ\Consumer;
use Samego\RocketMQ\Enum\MessageTagEnum;
use Samego\RocketMQ\Enum\TopicEnum;
use Samego\RocketMQ\Event\MessageEvent;
use Samego\RocketMQ\Producer;

$message = new TopicMessage('hello world');
$message->putProperty('timestamp', time());
$message->setMessageTag(MessageTagEnum::TRAINING_SERVICE_TRAINING_CONTROLLER);
$message->setMessageKey('uuid');

// 普通消息发送
Producer::normal($config['client'])->publish('MQ_xxx', TopicEnum::DEMO_SERVICE, $message);

// 普通消息订阅
Consumer::normal($config['client'], new MessageEvent($config['consumer'], $config['cache']))->subscribe();

```
