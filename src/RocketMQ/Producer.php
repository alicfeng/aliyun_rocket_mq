<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ;

use Samego\RocketMQ\Queue\NormalProducer;

/**
 * Class Producer.
 * @method static NormalProducer normal(array $config)
 * @version 1.0.0
 * @author  AlicFeng
 */
class Producer
{
    private static $container = [];

    /**
     * @description make application container(obj) using single instance
     * @param string $name      容器名称
     * @param array  $arguments 透传参数
     * @return mixed
     * @author      AlicFeng
     */
    private static function make($name, $arguments)
    {
        $namespace   = ucfirst($name);
        $application = "\\Samego\\RocketMQ\\Queue\\{$namespace}Producer";

        if (false === array_key_exists($name, static::$container)) {
            static::$container[$name] = new $application(...$arguments);
        }

        return static::$container[$name];
    }

    /**
     * @description dynamically pass methods to the application
     * @param string $name      容器名称
     * @param array  $arguments 透传参数
     * @return mixed
     * @author      AlicFeng
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, $arguments);
    }
}
