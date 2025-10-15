<?php

namespace common\rpc\pdo;

// 假设已经有一个名为 `PDOPool` 的 Swoole PDO 连接池类
use EasySwoole\FastDb\AbstractInterface\AbstractEntity;
use EasySwoole\FastDb\Attributes\Property;

/**
 * SwoolePDOActiveRecord - 使用 Swoole PDO 连接池的 Active Record 类
 *
 */
abstract class SwoolePDOActiveRecord extends AbstractEntity
{
    #[Property(isPrimaryKey: true)]
    public int $id;

//    private array $_attributes;


    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            } else {
                $this->__set($key, $value);
            }
        }
        parent::__construct($data);
    }

    public function __set(string $name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $this->_attributes[$name] ?? null;
    }
}
