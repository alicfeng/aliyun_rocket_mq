<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Listener;

use MQ\Model\Message;
use Samego\RocketMQ\Service\RepeatMessageService;

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

    public function handler(Message $message): bool
    {
        // 1.预防重复消费( 幂等原则 )
        if (true == $this->cache_config['enable'] && false == $this->_svc->first($message->getMessageKey())) {
            return true;
        }

        // 2.分发处理业务逻辑
        $result = call_user_func_array([new ($this->handler_base_namespace . '\\' . ucfirst($message->getMessageTag()) . 'Handler'), 'handler'], [$message]);

        // 3.如果执行失败 触发失败定义监听事件
        if (false === $result) {
            $this->failure($message);
        }

        return $result;
    }

    public function failure(Message $message): void
    {
        // 1.分发业务失败逻辑
        call_user_func_array([new ($this->handler_base_namespace . '\\' . ucfirst($message->getMessageTag()) . 'Handler'), 'failure'], [$message]);
    }
}
