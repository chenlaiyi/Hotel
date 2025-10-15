<?php

namespace common\components;
//
use common\rpc\pdo\SwoolePDOActiveRecord;
use yii\base\Exception;

class ActiveRecord
{
    // 使用私有属性存储策略对象，以实现懒加载
    private $strategy = null;

    /**
     * 动态调用方法，通过策略模式转发到具体实现。
     *
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return mixed 方法返回值
     * @throws MethodNotFoundException 如果方法不存在
     */
    public function __call($name, $arguments)
    {
        return $this->forwardMethodCall($name, $arguments);
    }

    /**
     * 静态方法调用的转发处理。
     *
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return mixed 方法返回值
     * @throws MethodNotFoundException 如果方法不存在
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = new self();
        return $instance->forwardMethodCall($name, $arguments);
    }

    /**
     * 前置方法，用于处理方法转发逻辑。
     *
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return mixed
     * @throws MethodNotFoundException
     */
    private function forwardMethodCall($name, $arguments)
    {
        // 懒加载策略对象
        $parentClass = $this->getActiveRecordClass();
        $strategy = new $parentClass();
        if (method_exists($this->strategy, $name)) {
            return call_user_func_array([$strategy, $name], $arguments);
        } else {
            throw new MethodNotFoundException("Method {$name} not found in ActiveRecord.");
        }
    }


    public  function setStrategy(ActiveRecordStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public  function getActiveRecordClass()
    {
        if (!$this->strategy) {
            $this->setStrategy(ActiveRecordStrategyFactory::createStrategy());
        }
        return $this->strategy->getActiveRecordClass();
    }
}




// 策略接口（或抽象类）
interface ActiveRecordStrategy
{
    public function getActiveRecordClass();
}

// 具体策略类：继承自 \yii\db\ActiveRecord
class YiiActiveRecordStrategy implements ActiveRecordStrategy
{
    public function getActiveRecordClass()
    {
        return '\yii\db\ActiveRecord';
    }
}

// 具体策略类：继承自 common\rpc\pdo\SwoolePDOActiveRecord
class SwooleActiveRecordStrategy implements ActiveRecordStrategy
{
    public function getActiveRecordClass()
    {
        return 'common\rpc\pdo\SwoolePDOActiveRecord';
    }
}

// 工厂类用于根据条件返回适当的策略
class ActiveRecordStrategyFactory
{
    public static function createStrategy()
    {
        if (!defined('YII_RPC')) {
            return new YiiActiveRecordStrategy();
        } else {
            return new SwooleActiveRecordStrategy();
        }
    }
}

/**
 * 自定义异常类：MethodNotFoundException
 */
class MethodNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

// ActiveRecord 现在总是继承自 ActiveRecordStrategy 返回的类


// 初始化 ActiveRecord 的使用
//(new ActiveRecord)->getActiveRecordClass();

//if (!defined('YII_RPC')){
//    class ActiveRecord extends \yii\db\ActiveRecord
//    {
//    }
//}else{
//    abstract class ActiveRecord extends SwoolePDOActiveRecord
//    {
//    }
//}

