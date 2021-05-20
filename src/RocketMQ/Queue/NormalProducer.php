<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Queue;

use Exception;
use MQ\Model\TopicMessage;
use MQ\MQClient;
use Samego\RocketMQ\Exception\ParamInvalidException;
use Samego\RocketMQ\Exception\PublishMessageException;
use Samego\RocketMQ\Helper\ParamHelper;

class NormalProducer
{
    private $_client;

    /**
     * NormalProducer constructor.
     * @param  array                 $config 消息队列端点配置
     * @throws ParamInvalidException
     */
    public function __construct(array $config)
    {
        // 1.检查消息队列端点配置
        ParamHelper::checkEndpointAuth($config);

        // 2.定义消息队列客户端
        $this->_client = new MQClient($config['endpoint'], $config['access_key'], $config['secret_key']);
    }

    /**
     * @function    publish
     * @description 发布消息
     * @param  string                  $instance_id   实例标识
     * @param  string                  $topic         主题名称
     * @param  TopicMessage            $topic_message 主题消息
     * @throws PublishMessageException
     * @datetime    2021/5/21 上午12:01
     * @author      AlicFeng
     */
    public function publish(string $instance_id, string $topic, TopicMessage $topic_message)
    {
        try {
            $this->_client->getProducer($instance_id, $topic)->publishMessage($topic_message);
        } catch (Exception $exception) {
            throw new PublishMessageException(500, $exception->getMessage());
        }
    }
}
