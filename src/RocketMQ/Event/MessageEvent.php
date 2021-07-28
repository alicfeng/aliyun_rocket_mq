<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Event;

use MQ\Model\Message;
use Samego\RocketMQ\Exception\ParamInvalidException;
use Samego\RocketMQ\Helper\ParamHelper;
use Samego\RocketMQ\Listener\MessageListener;

/**
 * Class MessageEvent
 * 消息事件.
 * @version 1.0.0
 * @author  AlicFeng
 */
class MessageEvent
{
    /**
     * @var MessageListener 消息监听者
     */
    public $_listener;

    /**
     * @var array 消息标签
     */
    public $message_tags = [];

    /**
     * @var string 实例标识
     */
    public $instance_id;

    /**
     * @var string 分组标识
     */
    public $group_id;

    /**
     * @var string 消息主题
     */
    public $topic;

    /**
     * MessageEvent constructor.
     * @param  array                 $subscribe_config 订阅配置信息
     * @param  array                 $cache_config     缓存配置信息
     * @throws ParamInvalidException
     */
    public function __construct(array $subscribe_config, array $cache_config = [])
    {
        $this->message_tags = $subscribe_config['message_tags'] ?? [];

        ParamHelper::checkSubscribeMessage($subscribe_config, $this->message_tags);

        $this->_listener   = new MessageListener($subscribe_config['handler_base_namespace'], $cache_config);
        $this->instance_id = $subscribe_config['instance_id'];
        $this->group_id    = $subscribe_config['group_id'];
        $this->topic       = $subscribe_config['topic'];
    }

    /**
     * @function    notify
     * @description 事件通知监听器处理消息
     * @param Message $message
     * @return bool
     * @datetime    2021/5/20 上午12:39
     * @author      AlicFeng
     */
    public function notify(Message $message): bool
    {
        return $this->_listener->handler($message);
    }

    public function __destruct()
    {
        unset($this->_listener, $this->message_tags, $this->instance_id, $this->group_id, $this->topic);
    }
}
