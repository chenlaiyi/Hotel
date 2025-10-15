<?php

namespace common\rpc\pdo;

use common\helpers\ErrorsHelper;
use common\rpc\utility\DebugHelper;
use Exception;
use PDO;
use PDOException;
use RuntimeException;
use Swoole\Coroutine;
use Swoole\Database\PDOPool;

trait QueryTrait
{
    use \yii\db\QueryTrait;

    /** @var PDO */
    protected $pdo;
    private bool $in_transaction = false;
    private PDOPool $pool;
    private PdoBuildWhere $PdoBuildWhere;

    public string $lastExecutedSql = '';

    private string $table;
    private string $select;


    public function __construct()
    {
        $pool = PdoPoolContainer::getInstance()->get('pdoPool');
        $this->pool = $pool;
        $this->PdoBuildWhere = new PdoBuildWhere();
    }

    public function getConnection()
    {
        return $this->pool->get();
    }

    public function close($connection = null)
    {
        $this->pool->put($connection);
    }

    function getLastSql(): string
    {
        return $this->lastExecutedSql;
    }


    public function release($connection = null): bool
    {
        if ($connection === null) {
            $this->in_transaction = false;
        }

        if (!$this->in_transaction) {
            $this->close($connection);
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function query(string $sql, array $params = []): array
    {
        return $this->executeQuery($sql, $params);
    }

    /**
     * 将原始SQL与参数结合，生成实际执行的SQL语句。
     *
     * @param string $sql 原始SQL语句，包含参数占位符
     * @param array $params 参数数组，键值对应占位符位置，值为实际参数值
     * @return void 替换参数后的实际执行SQL语句
     */
    public function getRealSql(string $sql, array $params): void
    {
        foreach ($params as $key => $value) {
            $placeholder = is_int($key) ? '?' : ":$key";
            $value = is_string($value) ? "'$value'" : $value;
            $sql = str_replace($placeholder, (string)$value, $sql);
        }
        DebugHelper::consoleWrite('pdo-getRealSql', [
            'sql' => $sql,
            'params' => $params
        ]);
        $this->lastExecutedSql = $sql;
    }

    /**
     * 执行查询并返回结果集
     *
     * @param string $sql SQL查询语句
     * @param array $params SQL查询参数
     * @return array 查询结果集
     * @throws PDOException
     * @throws Exception
     */
    public function executeQuery(string $sql, array $params = []): array
    {
        $pdo = $this->getConnection();
        try {
            $stmt = $pdo->prepare($sql);
            $stmt = $this->bindParams($stmt, $params);
            DebugHelper::consoleWrite('pdo-executeQuery', [
                'sql' => $sql,
                'params' => $params
            ]);
            // 获取SQL查询中的占位符数量
            $countPlaceholders = substr_count($sql, ':');
            // 获取$params数组中的元素数量
            $countParams = count($params);
            if ($countPlaceholders !== $countParams) {
                throw new Exception(
                    "Invalid number of parameters: expected $countPlaceholders, received $countParams. sql $sql"
                );
            }
            $stmt->execute();
            var_dump($stmt->queryString);
            $this->getRealSql($stmt->queryString, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handlePDOException($e); // 专门处理PDO异常
        } finally {
            $this->close($pdo);
        }
    }



    /**
     * 绑定参数
     * @param mixed $stmt
     * @param array $params
     * @return mixed
     */
    function bindParams(mixed $stmt, array $params): mixed
    {
        foreach ($params as $key => $value) {
            $type = PDO::PARAM_STR;
            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($value)) {
                $type = PDO::PARAM_NULL;
            }
            DebugHelper::consoleWrite('pdo-bindParams', [
                'value' => $value,
                'type' => $type,
                'key' => $key,
            ]);
            $stmt->bindParam(":$key", $value, $type);
        }

        return $stmt;
    }




    /**
     * 处理PDOException
     * @param PDOException $e
     * @throws Exception
     */
    private function handlePDOException(PDOException $e): void
    {
        DebugHelper::consoleWrite('错误准备回滚',[
            'in_transaction'=>$this->in_transaction
        ]);
        if ($this->in_transaction) {
            $this->rollBack(); // 在事务上下文中自动回滚
        }
        var_dump(ErrorsHelper::throwMsg($e));
        // 记录日志而非直接输出
        // log_error($e->getMessage());
        throw new Exception("PDO Error: " . $e->getMessage());
    }

    /**
     * 处理其他异常
     * @param Exception $e
     * @throws Exception
     */
    private function handleException(Exception $e): void
    {
        // 记录日志而非直接输出
        // log_error($e->getMessage());
        throw new Exception("Error: " . $e->getMessage());
    }
}