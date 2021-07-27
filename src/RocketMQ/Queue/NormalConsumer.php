<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Queue;

use Exception;
use MQ\Exception\AckMessageException;
use MQ\Exception\MessageNotExistException;
use MQ\Model\Message;
use MQ\MQClient;
use MQ\MQConsumer;
use Samego\RocketMQ\Event\MessageEvent;
use Samego\RocketMQ\Exception\ParamInvalidException;
use Samego\RocketMQ\Helper\ParamHelper;
use Samego\RocketMQ\Helper\StdLogHelper;

/**
 * Class Consumer
 * 消费者实体类.
 * @version 1.0.0
 * @author  AlicFeng
 */
class NormalConsumer
{
    /**
     * @var MQClient 消息队列客户端
     */
    private $_client;
    /**
     * @var MQConsumer 消息队列消费者
     */
    private $_consumer;

    /**
     * @var MessageEvent 消息事件
     */
    private $_event;

    /**
     * Consumer constructor.
     * @param  array                 $config 消息队列端点配置
     * @param  MessageEvent          $event  订阅事件
     * @throws ParamInvalidException
     */
    public function __construct(array $config, MessageEvent $event)
    {
        // 1.检查消息队列端点配置
        ParamHelper::checkEndpointAuth($config);

        // 2.定义消息队列客户端
        $this->_client = new MQClient($config['endpoint'], $config['access_key'], $config['secret_key']);

        // 3.初始化消息事件
        $this->_event = $event;
    }

    public function subscribe()
    {
        // 1.遍历所有消息标签 不断轮询连接取消息
        while (true) {
            $this->_consumer = null;
            foreach ($this->_event->message_tags as $message_tag) {
                // 2.定义消息消费者
                $this->_consumer = $this->_client->getConsumer(
                    $this->_event->instance_id,
                    $this->_event->topic,
                    $this->_event->group_id,
                    ParamHelper::unCanalize($message_tag)
                );

                try {
                    // 3.长轮询查询消费消息
                    // 长轮询表示如果topic没有消息则请求会在服务端挂住3s，3s内如果有消息可以消费则立即返回
                    // param.1 一次最多消费3条(最多可设置为16条) param.2 长轮询时间3秒（最多可设置为30秒）
                    $messages = $this->_consumer->consumeMessage(3, 3);
                } catch (MessageNotExistException $e) {
                    StdLogHelper::info('no message for request ' . $e->getRequestId());

                    continue;
                } catch (Exception $e) {
                    StdLogHelper::error('consume message request error : ' . $e->getMessage());
                    sleep(3);

                    continue;
                }

                // 4.处理业务逻辑
                $receipt_handles = [];
                /**
                 * @var Message $message
                 */
                foreach ($messages as $message) {
                    // 4.1事件通知监听器消费消息
                    StdLogHelper::info($message->getMessageBody());
                    $result = $this->_event->notify($message);

                    // 4.2只有消费成功才告知处理完成
                    if (true === $result) {
                        $receipt_handles[] = $message->getReceiptHandle();
                    }
                }

                // 5.消费成功的终端日志
                // $message->getNextConsumeTime() 前若不确认消息消费成功，则消息会重复消费
                // 消息句柄有时间戳，同一条消息每次消费拿到的都不一样
                StdLogHelper::info('consume message success', $receipt_handles);

                try {
                    if (empty($receipt_handles)) {
                        continue;
                    }
                    $this->_consumer->ackMessage($receipt_handles);
                } catch (AckMessageException $e) {
                    // E.某些消息的句柄可能超时了会导致确认不成功
                    StdLogHelper::error('ack error , request id is ' . $e->getRequestId(), $e->getAckMessageErrorItems());
                } catch (Exception $e) {
                    // E.其他原因
                    StdLogHelper::error('ack error that ' . $e->getMessage());
                }
            }
        }
    }
}
