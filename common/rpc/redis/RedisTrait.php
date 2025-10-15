<?php

namespace common\rpc\redis;

use common\helpers\ErrorsHelper;
use Exception;
use Redis;
use RedisException;
use Swoole\Database\RedisPool;

trait RedisTrait
{
    private RedisPool $pool;

    protected $connection;

    protected $multiOnGoing = false;

    protected $isWatching = false;

    public function __construct()
    {
        $this->pool = RedisPoolContainer::getInstance()->get('redisPool');
    }

    public function getConnection(): Redis
    {
        return $this->pool->get();
    }

    public function close($connection = null)
    {
        $this->pool->put($connection);
    }

    public function fill(): void
    {
        $this->pool->fill();
    }

    /**
     * ����PDOException
     * @param RedisException $e
     * @throws Exception
     */
    public function handlRedisException(RedisException $e): void
    {
        var_dump(ErrorsHelper::throwMsg($e));
        throw new Exception("Redis Error: " . $e->getMessage());
    }

    /**
     * ���������쳣
     * @param Exception $e
     * @throws Exception
     */
    public function handleException(Exception $e): void
    {
        // ��¼��־����ֱ�����
        // log_error($e->getMessage());
        throw new Exception("Error: " . $e->getMessage());
    }
}