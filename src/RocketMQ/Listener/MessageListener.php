<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Listener;

use MQ\Model\Message;
use Samego\RocketMQ\Helper\ParamHelper;
use Samego\RocketMQ\Service\RepeatMessageService;

/**
 * Class MessageListener
 * 订阅消息监听
 * @package Samego\RocketMQ\Listener
 * @version 1.0.0
 * @author  AlicFeng
 */
class MessageListener
{
    private $handler_base_namespace;
    private $cache_config;
    private $_svc;

    public function __construct(string $handler_base_namespace, array $cache_config)
    {
        $this->handler_base_namespace = $handler_base_namespace;
        $this->cache_config           = $cache_config;
        $this->_svc                   = new RepeatMessageService($cache_config);
    }

    /**
     * @function    handler
     * @description 消息事件处理
     * @param Message $message
     * @return bool
     * @datetime    2021/7/28 11:44 上午
     * @author      AlicFeng
     */
    public function handler(Message $message): bool
    {
        // 1.预防重复消费( 幂等原则 )
        if (true == $this->cache_config['enable'] && false == $this->_svc->first($message->getMessageKey())) {
            return true;
        }

        // 2.分发处理业务逻辑
        $result = call_user_func_array([new ($this->handler_base_namespace . '\\' . ParamHelper::camelize($message->getMessageTag()) . 'Handler'), 'handler'], [$message]);

        // 3.如果执行失败 触发失败定义监听事件
        if (false === $result) {
            $this->failure($message);
        }

        return $result;
    }

    /**
     * @function    failure
     * @description 消息事件处理失败后置处理
     * @param Message $message
     * @datetime    2021/7/28 12:34 下午
     * @author      AlicFeng
     */
    public function failure(Message $message): void
    {
        // 1.分发业务失败逻辑
        call_user_func_array([new ($this->handler_base_namespace . '\\' . ParamHelper::camelize($message->getMessageTag()) . 'Handler'), 'failure'], [$message]);
    }
}
