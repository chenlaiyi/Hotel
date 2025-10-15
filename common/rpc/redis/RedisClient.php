<?php

namespace common\rpc\redis;

use EasySwoole\Component\Singleton;
use RedisException;
use Swoole\Coroutine\Redis;
use Swoole\Database\RedisPool;

class RedisClient
{
    use Singleton,RedisTrait;


    /**
     * 设置键值对
     *
     * @param string $key
     * @param mixed $value
     * @param int $expire 过期时间（秒），默认为 0（永不过期）
     * @return bool
     * @throws RedisException
     */
    public function set(string $key,mixed $value, int $expire = 0): bool
    {
        $redis = $this->getConnection();
        $result = $redis->set($key, $value);
        if ($expire > 0) {
            $redis->expire($key, $expire);
        }
        $redis->close();
        return $result;
    }

    /**
     * @throws RedisException
     */
    public function delete(string $key): bool
    {
        $redis = $this->getConnection();
        $result = $redis->del($key);
        $redis->close();
        return $result;
    }

    /**
     * 获取键值
     *
     * @param string $key
     * @return mixed|false
     * @throws RedisException
     */
    public function get(string $key): mixed
    {
        $redis = $this->getConnection();
        $value = $redis->get($key);
        $redis->close();
        return $value;
    }

    /**
     * 设置哈希表中的字段值
     *
     * @param string $hashKey 哈希表键名
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @return bool
     * @throws RedisException
     */
    public function hset(string $hashKey, string $field, mixed $value): bool
    {
        $redis = $this->getConnection();
        $result = $redis->hset($hashKey, $field, $value);
        $redis->close();
        return $result;
    }

    /**
     * 获取哈希表中字段的值
     *
     * @param string $hashKey 哈希表键名
     * @param string $field 字段名
     * @return bool
     * @throws RedisException
     */
    public function hget(string $hashKey, string $field): bool
    {
        $redis = $this->getConnection();
        $value = $redis->hget($hashKey, $field);
        $redis->close();
        return $value;
    }

    /**
     * @throws RedisException
     */
    public function __call($name, $arguments)
    {
        if (!$this->multiOnGoing) {
            $this->connection = $this->pool->getConnection();
        }

        try {
            $data = $this->connection->{$name}(...$arguments);
        } catch (RedisException $e) {
            $this->close();
            throw $e;
        }

        if ($this->multiOnGoing) {
            return $this;
        }
        $this->close($this->connection);

        return $data;
    }

    /**
     * @throws RedisException
     */
    public function brPop($keys, $timeout)
    {
        $this->connection = $this->pool->getConnection();

        $data = [];

        try {
            $start = time();
            $data = $this->connection->brPop($keys, $timeout);
        } catch (RedisException $e) {
            $end = time();
            if ($end - $start < $timeout) {
                $this->close();
                throw $e;
            }
        }

        $this->close($this->connection);

        return $data;
    }

    /**
     * @throws RedisException
     */
    public function blPop($keys, $timeout)
    {
        $this->connection = $this->pool->getConnection();

        $data = [];

        try {
            $start = time();
            $data = $this->connection->blPop($keys, $timeout);
        } catch (RedisException $e) {
            $end = time();
            if ($end - $start < $timeout) {
                $this->close();
                throw $e;
            }
        }

        $this->close($this->connection);

        return $data;
    }

    /**
     * @throws RedisException
     */
    public function subscribe($channels, $callback)
    {
        $this->connection = $this->pool->getConnection();

        $this->connection->setOption(\Redis::OPT_READ_TIMEOUT, '-1');

        try {
            $data = $this->connection->subscribe($channels, $callback);
        } catch (RedisException $e) {
            $this->close();
            throw $e;
        }

        $this->connection->setOption(\Redis::OPT_READ_TIMEOUT, (string) $this->pool->getConfig()['time_out']);

        $this->close($this->connection);

        return $data;
    }

    /**
     * @throws RedisException
     */
    public function brpoplpush($srcKey, $dstKey, $timeout)
    {
        $this->connection = $this->pool->getConnection();

        try {
            $start = time();
            $data = $this->connection->brpoplpush($srcKey, $dstKey, $timeout);
        } catch (RedisException $e) {
            $end = time();
            if ($end - $start < $timeout) {
                $this->close();
                throw $e;
            }
            $data = false;
        }

        $this->close($this->connection);

        return $data;
    }

    public function fill(): void
    {
        $this->pool->fill();
    }

    /**
     * @throws RedisException
     */
    public function watch($key): static
    {
        if (!$this->multiOnGoing) {
            $this->connection = $this->pool->getConnection();

            try {
                $this->connection->watch($key);
            } catch (RedisException $e) {
                $this->close();
                throw $e;
            }

            $this->isWatching = true;
        }

        return $this;
    }

    /**
     * @throws RedisException
     */
    public function unwatch()
    {
        if (!$this->isWatching) {
            return true;
        }

        try {
            $result = $this->connection->unwatch();
        } catch (RedisException $e) {
            $this->isWatching = false;
            $this->close();
            throw $e;
        }

        $this->isWatching = false;

        $this->close($this->connection);

        return $result;
    }

    /**
     * @throws RedisException
     */
    public function multi($mode = \Redis::MULTI): static
    {
        if (!$this->multiOnGoing) {
            if (!$this->isWatching) {
                $this->connection = $this->pool->getConnection();
            }

            try {
                $this->connection->multi($mode);
            } catch (RedisException $e) {
                $this->close();
                throw $e;
            }

            $this->multiOnGoing = true;
        }

        return $this;
    }

    /**
     * @throws RedisException
     */
    public function exec()
    {
        if (!$this->multiOnGoing) {
            return null;
        }

        try {
            $result = $this->connection->exec();
        } catch (RedisException $e) {
            $this->isWatching = false;
            $this->multiOnGoing = false;
            $this->close();
            throw $e;
        }

        $this->isWatching = false;
        $this->multiOnGoing = false;

        $this->close($this->connection);

        return $result;
    }

    /**
     * @throws RedisException
     */
    public function discard()
    {
        if (!$this->multiOnGoing) {
            return null;
        }

        try {
            $result = $this->connection->discard();
        } catch (RedisException $e) {
            $this->isWatching = false;
            $this->multiOnGoing = false;
            $this->close();
            throw $e;
        }

        $this->isWatching = false;
        $this->multiOnGoing = false;

        $this->close($this->connection);

        return $result;
    }
}