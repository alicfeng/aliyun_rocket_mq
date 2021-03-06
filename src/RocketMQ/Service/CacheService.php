<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Service;

class CacheService
{
    private static $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function redis(array $config)
    {
        if (false == (self::$_instance instanceof \Redis)) {
            self::$_instance = new \Redis();
            self::$_instance->pconnect($config['host'], $config['port']);
            self::$_instance->auth($config['password'] ?? null);
            self::$_instance->select($config['database'] ?? 0);
        }

        return self::$_instance;
    }
}
