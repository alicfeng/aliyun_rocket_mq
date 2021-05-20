local result = redis.call('exists', KEYS[1])

-- 判断是否已经存在 不存在为0 存在为1
if 0 == result then
    redis.call('set', KEYS[1], 1) -- 设置健值
    redis.call('expire', KEYS[1], 60 * 60 * 24 * 10) -- 过期时间策略 10day
end

return result
