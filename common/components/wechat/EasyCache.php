<?php

namespace common\components\wechat;
use Psr\SimpleCache\CacheInterface;
use Yii;

class EasyCache implements CacheInterface
{

    public function get(string $key, mixed $default = null): mixed
    {
        // TODO: Implement get() method.
        return Yii::$app->cache->get($key);
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        // TODO: Implement set() method.
        Yii::$app->cache->set($key,$value,$ttl);
    }

    public function delete(string $key): bool
    {
        return Yii::$app->cache->delete($key);
    }

    public function clear(): bool
    {
        return Yii::$app->cache->flush();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        // TODO: Implement getMultiple() method.
        return Yii::$app->cache->multiGet($keys);
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        // TODO: Implement setMultiple() method.
        return Yii::$app->cache->multiSet($values,$ttl);
    }

    public function deleteMultiple(iterable $keys): bool
    {
        // TODO: Implement deleteMultiple() method.
        return Yii::$app->cache->deleteMultiple($values,$ttl);
    }

    public function has(string $key): bool
    {
        // TODO: Implement has() method.
        return Yii::$app->cache->exists($key);
    }
}