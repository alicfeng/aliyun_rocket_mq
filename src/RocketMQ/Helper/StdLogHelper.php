<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Helper;

class StdLogHelper
{
    /**
     * @function    prefix
     * @description 标准输出前缀
     * @param string $level 级别
     * @return string
     * @datetime    2021/5/20 下午11:22
     * @author      AlicFeng
     */
    public static function prefix(string $level)
    {
        return date('Y-m-d h:i:s') . ' [' . strtoupper($level) . '] ';
    }

    /**
     * @function    info
     * @description 终端打印输出 INFO 级别信息
     * @param string $text    信息内容
     * @param array  $context 上下文
     * @datetime    2021/5/20 上午2:21
     * @author      AlicFeng
     */
    public static function info(string $text, array $context = []): void
    {
        echo chr(27) . '[34m' .
             self::prefix(__FUNCTION__) .
             "$text" .
             (empty($context) ? '' : json_encode($context)) . chr(27)
             . '[0m' . "\n";
    }

    /**
     * @function    error
     * @description 终端打印输出 ERROR 级别信息
     * @param string $text    信息内容
     * @param array  $context 上下文
     * @datetime    2021/5/20 上午2:21
     * @author      AlicFeng
     */
    public static function error(string $text, array $context = []): void
    {
        echo chr(27) . '[31m ' .
             self::prefix(__FUNCTION__) .
             "$text" .
             (empty($context) ? '' : json_encode($context)) . chr(27)
             . '[0m' . "\n";
    }
}
