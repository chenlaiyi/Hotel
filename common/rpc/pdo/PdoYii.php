<?php

namespace common\rpc\pdo;

use common\rpc\utility\DebugHelper;
use Exception;
use Swoole\Database\PDOPool;

class PdoYii
{
    use QueryTrait;

    /**
     * @param callable|string|null $indexBy
     * @return PdoYii
     */
    public function setIndexBy(callable|string|null $indexBy): static
    {
        $this->indexBy = $indexBy;
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function table(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function where($condition): static
    {
        $this->where[] = $condition;
        return $this;
    }

    public function select(array $columns = []): static
    {
        if (empty($columns)) {
            $select = "*";
        } else {
            $select = implode(',', $columns);
        }
        $this->select = $select;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function one(): array
    {
        $sql = "SELECT * FROM $this->table";
        $params = [];
        if (!empty($this->where)) {
            DebugHelper::consoleWrite( 'pdo-one-where-init', $this->where);
            print_r($this->where);
            list($whereClause, $params) = $this->PdoBuildWhere->parseWhereConditionsWithColonPlaceholders($this->where);
            DebugHelper::consoleWrite( '组装后条件', $this->where);

            print_r($whereClause);
            $sql .= ' where'. $whereClause;
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(',', $this->orderBy);
        }
        DebugHelper::consoleWrite( 'pdo-one', [
            'sql' => $sql,
            'params' => $params
        ]);
        return $this->executeQuery($sql, $params);
    }

    /**
     * @throws Exception
     */
    public function query(string $sql, array $params = []): array
    {
        return $this->executeQuery($sql, $params);
    }

    /**
     * @throws Exception
     */
    public function andWhere($condition): static
    {
        if (empty($this->where)){
            throw new Exception('WHERE clause is empty. Cannot add AND condition without existing conditions.');
        }
        if (!is_array($condition)){
            throw new Exception( 'AND condition must be an array.');
        }
        $this->where[] = $condition;
        return $this;
    }


    /**
     * @throws Exception
     */
    function orWhere($condition): static
    {
        if (empty($this->where)){
            throw new Exception('WHERE clause is empty. Cannot add OR condition without existing conditions.');
        }

        if (!is_array($condition)){
            throw new Exception( 'OR condition must be an array.');
        }
        $where = [];
        if (key_exists('0', $this->where) &&  $this->where[0] !== 'or'){
            $where[] = 'or';
        }

        foreach ($this->where as $item){
            if (is_array($item) && count($item) >1){
                array_unshift($item,'and');
            }
            $where[] = $item;
        }
        $where[] = $condition;

        $this->where = $where;
        print_r($this->where);
        return $this;
    }


    function orderBy($columns, $order = 'ASC'): static
    {
        $condition = "$columns $order";
        $this->where[] = $condition;
        return $this;
    }

    public function all($db = null)
    {
        // TODO: Implement all() method.
    }

    public function count($q = '*', $db = null)
    {
        // TODO: Implement count() method.
    }

    public function exists($db = null)
    {
        // TODO: Implement exists() method.
    }
}