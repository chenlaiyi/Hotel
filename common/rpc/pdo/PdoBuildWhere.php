<?php

namespace common\rpc\pdo;

use common\rpc\utility\DebugHelper;
use Exception;

class PdoBuildWhere
{

    static int $paramCounter = 0;

    /**
     * @throws Exception
     */
    function parseWhereConditionsWithColonPlaceholders(array $where, $operator = 'AND'): array
    {
        if (empty($where)) {
            // 处理输入为空的情况
            return ['1=1', []];
        }

        $whereClause = [];
        $params = [];
        DebugHelper::consoleWrite('PdoBuildWhere-init', [
            'where' => $where,
            'whereClause' => $whereClause,
            'params' => $params,
            'paramCounter' => self::$paramCounter,
        ]);
        if (key_exists(0, $where) && is_string($where[0]) && in_array(strtoupper($where[0]), ['AND', 'OR'])) {
            $linkOperator = $where[0];
            // 处理键名为逻辑运算符的情况，合并当前层级的键值对作为嵌套条件
            unset($where[0]);
            $nestedConditions = array_values($where);

            DebugHelper::consoleWrite('pdobuildwhere-and/or', [
                'nestedConditions' => $nestedConditions,
            ]);
            $nestedResult = $this->parseWhereConditionsWithColonPlaceholders($nestedConditions, $linkOperator);

//            $nestedWhereClause = "(".ltrim($nestedResult[0]," $operator") .")";

            $nestedParams = $nestedResult[1];

            $whereClause[] = $nestedResult[0];
            $params = array_merge($params, $nestedParams);

        } else {
            DebugHelper::consoleWrite('pdobuildwhere-is_array-where', [
                'where' => $where

            ]);
            foreach ($where as $key => $relation) {
                // 按现有逻辑处理条件
                //['and', $where1, $where2]
                if (is_array($relation) && key_exists(0, $relation) && is_string($relation[0]) && in_array(strtoupper($relation[0]), ['AND', 'OR'])) {
                    $linkOperator = $relation[0];
                    unset($relation[0]);
                    $nestedConditions = array_values($relation);

                    DebugHelper::consoleWrite('pdobuildwhere-and/or', [
                        'nestedConditions' => $nestedConditions,
                    ]);
                    $nestedResult = $this->parseWhereConditionsWithColonPlaceholders($nestedConditions, $linkOperator);

                    $nestedWhereClause = "(" . $nestedResult[0] . ")";
                    $nestedParams = $nestedResult[1];
                    $whereClause[] = $nestedWhereClause;
                    $params = array_merge($params, $nestedParams);
                } else {
                    //[$where1,$where2]
                    $conditionData = $this->parseCondition($relation, self::$paramCounter, $key);
                    DebugHelper::consoleWrite('pdobuildwhere-is_array', [
                        'key' => $key,
                        'conditionData' => $conditionData,
                        'relation' => $relation,
                        'whereClause' => $whereClause,
                        'params' => $params,
                        'paramCounter' => self::$paramCounter,

                    ]);
                    self::$paramCounter = $conditionData['paramCounter'];
//                    $whereClause[] =  "(".$conditionData['clause'].")";
                    $whereClause[] = $conditionData['clause'];
                    $params = array_merge($params, $conditionData['params']);
                }
            }
        }

        $whereClauseStr = implode(' ' . $operator . ' ', $whereClause);

        return [$whereClauseStr, $params];
    }

    /**
     * @throws Exception
     */
    function parseCondition($condition, $paramCounter, $key): array
    {
        $operator = 'AND';//and
        $column = '';
        $value = '';
        print_r($condition);
        DebugHelper::consoleWrite('PdoBuildWhere-parseCondition', [
            'condition' => $condition,
            'paramCounter' => self::$paramCounter,
            'key' => $key
        ]);
        //[$where1,$where2] $where1 is array
        if (is_array($condition)) {
            if (key_exists(0, $condition) && is_string($condition[0]) && in_array(strtoupper($condition[0]), ['AND', 'OR'])) {
                DebugHelper::consoleWrite('PdoBuildWhere-parseCondition-logical', $condition);
                // 嵌套条件处理
                list($logicalOperator, $nestedConditions) = array_pad($condition, 2, null);

                $nestedResult = $this->parseWhereConditionsWithColonPlaceholders($nestedConditions);

                print_r($nestedResult);

                $nestedWhereClause = " $operator (" . ltrim($nestedResult[0], " $operator ") . ")";
//                $nestedWhereClause = " $logicalOperator ($nestedResult[0])";
                $nestedParams = $nestedResult[1];

                return ['clause' => $nestedWhereClause, 'params' => $nestedParams, 'paramCounter' => $paramCounter];
            }


            if (count($condition) === 1) {
//                ['age'=>1]
                DebugHelper::consoleWrite('PdoBuildWhere-parseCondition-1', $condition);
                $column = array_key_first($condition);
                $value = reset($condition);
            } elseif (count($condition) === 2) {
                DebugHelper::consoleWrite('PdoBuildWhere-parseCondition-2-array', $condition);
                //['age',12] 默认逻辑运算符为=

                if (key_exists(0, $condition) && is_string($condition[0])) {
                    $operator = '=';
                    list($column, $value) = array_pad($condition, 2, null);
                } else {
                    //                    $operator = 'AND';
                    $nestedResult = $this->parseWhereConditionsWithColonPlaceholders($condition);

                    print_r($nestedResult);

                    $nestedWhereClause = " (" . ltrim($nestedResult[0], " $operator ") . ")";
//                $nestedWhereClause = " $logicalOperator ($nestedResult[0])";
                    $nestedParams = $nestedResult[1];

                    return ['clause' => $nestedWhereClause, 'params' => $nestedParams, 'paramCounter' => $paramCounter];


                }


                DebugHelper::consoleWrite('PdoBuildWhere-parseCondition-2', $condition);
//                list($column, $value) = array_pad($condition, 2, null);
            } elseif (count($condition) === 3) {
                //['=','age',12]
                DebugHelper::consoleWrite('PdoBuildWhere-parseCondition-3', $condition);
                list($operator, $column, $value) = array_pad($condition, 3, null);
            }
        } else {
            $column = $key;
            $value = $condition;
        }


        if (is_int($column)) {
            throw new Exception('The column name cannot be an integer');
        }

        if (is_array($value)) {
            //['age'=>[1,2,3]]
            DebugHelper::consoleWrite('PdoBuildWhere-parseCondition-2-array', $condition);
            $operator = 'IN';
        }

        switch ($operator) {
            case 'IN':
            case 'NOT IN':
                return $this->handleInOperator($operator, $column, $value, $paramCounter);
            case 'BETWEEN':
                return $this->handleBetweenOperator($column, $value, $paramCounter);
            case 'LIKE':
            case 'NOT LIKE':
                return $this->handleLikeOperator($operator, $column, $value, $paramCounter);
            default:
                return $this->handleDefaultOperator($operator, $column, $value, $paramCounter);
        }
    }

    function handleInOperator($operator, $column, $value, $paramCounter): array
    {
        if (!is_array($value)) {
            throw new Exception("Invalid value for IN operator");
        }
        $placeholders = implode(', ', array_fill(0, count($value), ':p' . $paramCounter));
        $params = array_combine(array_map(fn($index) => "p{$index}", range($paramCounter, $paramCounter + count($value) - 1)), $value);
        $paramCounter += count($value);
        return ['clause' => " $column $operator ({$placeholders})", 'params' => $params, 'paramCounter' => $paramCounter];
    }

    /**
     * @throws Exception
     */
    function handleBetweenOperator($column, $value, $paramCounter): array
    {
        if (!is_array($value) || count($value) !== 2) {
            throw new Exception("Invalid number of arguments for BETWEEN operator");
        }
        $params = [
            "p$paramCounter" => $value[0],
            "p" . ($paramCounter + 1) => $value[1]
        ];
        $paramCounter += 2;
        return ['clause' => " $column BETWEEN :p" . ($paramCounter - 2) . " AND :p" . ($paramCounter - 1), 'params' => $params, 'paramCounter' => $paramCounter];
    }

    function handleLikeOperator($operator, $column, $value, $paramCounter): array
    {
        if (!is_string($value)) {
            throw new Exception("Invalid value for LIKE operator");
        }
        $params = ["p$paramCounter" => $value];
        $paramCounter++;
        return ['clause' => " $column $operator :p$paramCounter", 'params' => $params, 'paramCounter' => $paramCounter];
    }

    /**
     * @throws Exception
     */
    private function handleDefaultOperator($operator, mixed $column, mixed $value, $paramCounter): array
    {
        if (is_array($value)) {
            throw new Exception("Invalid value for default operator");
        }
        $params = ["p$paramCounter" => $value];
//        $clause =  " $operator $column = :p$paramCounter";
        $clause = " $column = :p$paramCounter";
        DebugHelper::consoleWrite('PdoBuildWhere-handleDefaultOperator', [
            'column' => $column,
            'clause' => $clause,
            'value' => $value,
            'paramCounter' => $paramCounter,
            'params' => $params,
        ]);
        $paramCounter++;
        return ['clause' => $clause, 'params' => $params, 'paramCounter' => $paramCounter];
    }
}