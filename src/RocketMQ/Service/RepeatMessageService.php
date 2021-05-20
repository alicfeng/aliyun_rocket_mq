<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\RocketMQ\Service;

class RepeatMessageService
{
    /**
     * @var array
     */
    private $config;
    private static $lua_script;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function first(string $ticket): bool
    {
        // 2.加载 lua 脚本
        if (null == self::$lua_script) {
            self::$lua_script = file_get_contents(__DIR__ . '/../../../sbin/handle_unique.lua');
        }

        // 2.判断票据是否存在
        return (bool) CacheService::redis($this->config)->eval(self::$lua_script, [$ticket], 1);
    }

    public function ignore(string $ticket): bool
    {
        return CacheService::redis($this->config)->del($ticket);
    }
}
