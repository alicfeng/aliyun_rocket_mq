<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Helper;

use Samego\RocketMQ\Exception\ParamInvalidException;

class ParamHelper
{
    /**
     * @function    checkEndpointAuth
     * @description 检查消息队列端点配置
     * @param  array                 $config 配置
     * @throws ParamInvalidException
     * @datetime    2021/5/20 下午11:49
     * @author      AlicFeng
     */
    public static function checkEndpointAuth(array $config)
    {
        if (false === isset($config['endpoint']) || empty($config['endpoint'])) {
            throw new ParamInvalidException('the endpoint parameter is not configured', 400);
        }
        if (false === isset($config['access_key']) || empty($config['access_key'])) {
            throw new ParamInvalidException('the access_key parameter is not configured', 400);
        }
        if (false === isset($config['secret_key']) || empty($config['secret_key'])) {
            throw new ParamInvalidException('the secret_key parameter is not configured', 400);
        }
    }

    /**
     * @function    checkSubscribeMessage
     * @description 检查处理句柄类是否存在
     * @param  array                 $config       订阅配置信息
     * @param  array                 $message_tags 订阅信息标签
     * @throws ParamInvalidException
     * @datetime    2021/5/20 上午12:38
     * @author      AlicFeng
     */
    public static function checkSubscribeMessage(array $config, array $message_tags)
    {
        // 1.检查处理基础目录命名空间
        if (empty($config['handler_base_namespace'])) {
            throw new ParamInvalidException('the handler_base_namespace param cannot be empty', 400);
        }

        if (empty($config['message_tags'])) {
            throw new ParamInvalidException('the message_tags param cannot be empty', 400);
        }

        if (empty($config['instance_id'])) {
            throw new ParamInvalidException('the instance_id param cannot be empty', 400);
        }

        foreach ($message_tags as $tag) {
            if (class_exists($config['handler_base_namespace'] . '\\' . ucfirst($tag) . 'Handler')) {
                continue;
            }

            throw new ParamInvalidException($tag . ' handler class not exist', 400);
        }
    }

    /**
     * @function 驼峰命名转下划线命名
     * @description 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     * @param $camelCaps
     * @param string $separator
     * @return string
     * @author Fsliu
     * @datetime 2021/7/27
     */
    public static function unCanalize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps));
    }
}
