<?php

namespace common\rpc\pdo;

use common\rpc\utility\DebugHelper;
use EasySwoole\Component\Singleton;
use Exception;
use PDO;
use PDOException;

class PdoQuery
{
    use Singleton, QueryTrait;

    /**
     * 查询所有记录
     *
     * @param string $table 表名
     * @return array 查询结果集
     * @throws PDOException|Exception
     */
    public function findAll(string $table): array
    {
        $sql = "SELECT * FROM $table";
        return $this->executeQuery($sql);
    }


    /**
     * 根据条件查询单个记录
     *
     * @param string $table 表名
     * @param array $conditions 查询条件
     * @return array|null 查询结果，如果没有找到则返回null
     * @throws PDOException|Exception
     */
    public function findOneBy(string $table, array $conditions): ?array
    {
        $params = [];
        DebugHelper::consoleWrite('pdo-findOneBy', [
            'conditions' => $conditions
        ]);
        $sql = "SELECT * FROM $table WHERE ";
        list($whereClause, $params) = $this->PdoBuildWhere->parseWhereConditionsWithColonPlaceholders($conditions);
        DebugHelper::consoleWrite('pdo-findOneBy', [
            'conditions' => $conditions,
            'params' => $params,
            'whereSql' => $whereClause
        ]);
        $sql .= $whereClause;
        $sql .= " LIMIT 1";
        $results = $this->executeQuery($sql, $params);
        return $results ? $results[0] : [];
    }

    /**
     * 根据主键查询单个记录
     *
     * @param string $table 表名
     * @param mixed $id 主键值
     * @return array|null 查询结果，如果没有找到则返回null
     * @throws PDOException
     * @throws Exception
     */
    public function findById(string $table, mixed $id): ?array
    {
        $sql = "SELECT * FROM $table WHERE id = :id";
        $results = $this->executeQuery($sql, ['id' => $id]);
        return $results ? $results[0] : null;
    }

    /**
     * 查询单个字段的值
     *
     * @param string $table 表名
     * @param string $field 字段名
     * @param array $conditions 查询条件
     * @return mixed 查询结果，如果没有找到则返回null
     * @throws PDOException|Exception
     */
    public function findField(string $table, string $field, array $conditions): mixed
    {
        $sql = "SELECT $field FROM $table WHERE ";
        $params = [];
        $i = 1;
        foreach ($conditions as $column => $value) {
            $sql .= "$column = :$column";
            $params[$column] = $value;
            if ($i < count($conditions)) {
                $sql .= " AND ";
            }
            $i++;
        }
        $sql .= " LIMIT 1";
        $results = $this->executeQuery($sql, $params);
        return $results ? $results[0][$field] : null;
    }

    /**
     * 单独更新一条记录
     *
     * @param string $table 表名
     * @param array $data 要更新的数据
     * @param array $conditions 更新条件
     * @return bool 更新是否成功
     * @throws PDOException
     * @throws Exception
     */
    public function update(string $table, array $data, array $conditions): bool
    {
        $set = [];
        $params = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $where = [];

        foreach ($conditions as $column => $value) {
            $where[] = "$column = :cond_$column";
            $params[":cond_$column"] = $value;
        }

        $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);
        $pdo = $this->pool->get();
        try {
            $stmt = $pdo->prepare($sql);

            DebugHelper::consoleWrite('pdo-update', [
                'sql' => $sql,
                'data' => $data,
                'conditions' => $conditions,
                'params' => $params
            ]);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            // 处理异常
            $this->handlePDOException($e); // 专门处理PDO异常
        } finally {
            $this->pool->put($pdo);
        }
    }

    /**
     * 批量更新记录
     *
     * @param string $table 表名
     * @param array $data 要更新的数据数组，每个元素是一个关联数组
     * @param string $keyColumn 用于匹配记录的关键列
     * @return bool 批量更新是否成功
     * @throws PDOException
     * @throws Exception
     */
    public function batchUpdate(string $table, array $data, string $keyColumn): bool
    {
        if (empty($data)) {
            return false;
        }
        $whereParams = [];
        $setParams = [];
        $sqlParts = [];
        foreach ($data as $row) {
            $set = [];
            $where = [];
            foreach ($row as $column => $value) {
                if ($column === $keyColumn) {
                    $where[] = "$column = :{$column}_$keyColumn";
                    $whereParams[":{$column}_$keyColumn"] = $value;
                } else {
                    $set[] = "$column = :{$column}_$keyColumn";
                    $setParams[":{$column}_$keyColumn"] = $value;
                }
            }
            $sqlParts[] = "UPDATE $table SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);
        }

        $pdo = $this->pool->get();
        try {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $pdo->prepare(implode(';', $sqlParts));
            $params = array_merge($whereParams, $setParams);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            // 处理异常
            $this->handlePDOException($e); // 专门处理PDO异常
        } finally {
            $this->pool->put($pdo);
        }
    }


    /**
     * 单独插入一条记录
     *
     * @param string $table 表名
     * @param array $data 要插入的数据
     * @return ?int 插入成功后的主键值
     * @throws Exception
     */
    public function insert(string $table, array $data): ?int
    {
        $PdoQuery = self::getInstance();
        echo '修改获取方式'.PHP_EOL;
        print_r($PdoQuery->getConnection());
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $pdo = $this->getConnection();
        echo 'inster pdo 连接'.PHP_EOL;
        print_r($pdo);
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            $this->getRealSql($stmt->queryString, $data);
            // Retrieve the last inserted ID
            DebugHelper::consoleWrite('pdo-insert', [
                'id' => $pdo->lastInsertId(),
                'sql' =>$this->getLastSql()
            ]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            // 处理异常
            $this->handlePDOException($e); // 专门处理PDO异常
        } finally {
            $this->pool->put($pdo);
        }
    }

    /**
     * 批量插入记录
     *
     * @param string $table 表名
     * @param array $data 要插入的数据数组，每个元素是一个关联数组
     * @return bool 批量插入是否成功
     * @throws PDOException
     * @throws Exception
     */
    public function insertBatch(string $table, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $columns = implode(',', array_keys($data[0]));
        $placeholders = ':' . implode(', :', array_keys($data[0]));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $pdo = $this->pool->get();
        try {
            $stmt = $pdo->prepare($sql);
            foreach ($data as $row) {
                $stmt->execute(array_values($row));
            }
            return true;
        } catch (PDOException $e) {
            // 处理异常
            $this->handlePDOException($e); // 专门处理PDO异常
        } finally {
            $this->pool->put($pdo);
        }
    }


    /**
     * 分页查询
     *
     * @param string $table 表名
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param array $conditions 查询条件
     * @param array $order 排序条件
     * @return array 分页查询结果
     * @throws PDOException|Exception
     */
    public function findPaginated(string $table, int $page, int $limit, array $conditions = [], array $order = []): array
    {
        $sqlCount = "SELECT count(*) as total FROM $table";
        $sql = "SELECT * FROM $table";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $i = 1;
            foreach ($conditions as $column => $value) {
                $sql .= "$column = :$column";
                $params[] = $value;
                if ($i < count($conditions)) {
                    $sql .= " AND ";
                }
                $i++;
            }

        }

        if (!empty($order)) {
            $sql .= " ORDER BY " . implode(', ', $order);
            $sqlCount .= $sql;
        }

        $results = $this->executeQuery($sqlCount, $params);
        $count = $results ? $results[0]['total'] : 0;
        $sql .= " LIMIT :offset, :limit";
        $offset = ($page - 1) * $limit;
        $params['offset'] = (int)$offset;
        $params['limit'] = $limit;
        // 确保参数值合理
        if ($params['offset'] < 0 || $params['limit'] < 1) {
            throw new Exception('Offset must be non-negative and limit must be positive.');
        }
        $list = $this->executeQuery($sql, $params);
        return [
            'count' => $count,
            'list' => $list
        ];
    }


}