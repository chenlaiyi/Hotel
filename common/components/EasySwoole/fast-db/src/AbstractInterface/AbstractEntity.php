<?php

namespace EasySwoole\FastDb\AbstractInterface;

use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\FastDb\Attributes\Relate;
use EasySwoole\FastDb\Beans\ListResult;
use EasySwoole\FastDb\Beans\Query;
use EasySwoole\FastDb\Exception\RuntimeError;
use EasySwoole\FastDb\FastDb;
use EasySwoole\FastDb\Utility\ReflectionCache;
use EasySwoole\Mysqli\QueryBuilder;

abstract class AbstractEntity implements \JsonSerializable
{
    private array $compareData = [];

    private ?Query $queryBuilder = null;

    abstract static function tableName();
    abstract function rules();
    public array $_attributes = [];


    public function quoteTableName(): string
    {
        $name = $this->tableName();
        if (strncmp($name, '(', 1) === 0 && strpos($name, ')') === strlen($name) - 1) {
            echo 'quoteTableName01'.PHP_EOL;
            return $name;
        }
        if (!str_contains($name, '{{')) {
            echo 'quoteTableName02'.PHP_EOL;
            return $name;
        }
        $config = FastDb::getInstance()->getConfig();
        return $config->getPrefix().trim(str_replace(['{{%', '}}'], '', $name));
    }

    function __construct(array $data = null)
    {
        $this->init();
        if(!empty($data)){
            $this->setData($data,true);
        }
    }

    private function init()
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        //初始化所有变量和转化
        /** @var Property $property */
        foreach ($entityRef->allProperties() as $property){
            //判断是否需要转化
            if($property->convertObject){
                //如果不允许为null或者是存在默认值
                if((!$property->allowNull) || ($property->defaultValue !== null)){
                    /** @var ConvertObjectInterface $object */
                    $object = call_user_func([$property->convertObject,'toObject'],$property->defaultValue);
                    $this->{$property->name()} = $object;
                    $this->compareData[$property->name()] = $object->toValue();
                }else{
                    $this->{$property->name()} = null;
                    $this->compareData[$property->name()] = $property->defaultValue;
                }
            }else{
                if(($property->defaultValue !== null) || $property->allowNull){
                    $this->{$property->name()} = $property->defaultValue;
                }
                $this->compareData[$property->name()] = $property->defaultValue;
            }
        }
        if($entityRef->getOnInitialize()){
            $this->callHook($entityRef->getOnInitialize()->callback);
        }
    }

    function setData(array $data,bool $mergeCompare = false)
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        $allProperties = $entityRef->allProperties();
        foreach ($data as $key => $val){
            if(!isset($allProperties[$key])){
                continue;
            }
            /** @var Property $property */
            $property = $allProperties[$key];
            if($property->convertObject && ($val !== null)){
                if($val instanceof $property->convertObject){
                    $object = $val;
                }else{
                    $object = call_user_func([$property->convertObject,'toObject'],$val);
                }
                $this->{$key} = $object;
                if($mergeCompare){
                    $this->compareData[$key] = $this->{$key}->toValue();
                }
            }else{
                $this->{$key} = $val;
                if($mergeCompare){
                    $this->compareData[$key] = $val;
                }
            }
        }
    }

    function all():ListResult
    {
        $query = $this->queryLimit()->__getQueryBuilder();

        $fields = null;
        $returnAsArray = false;
        if(!empty($this->queryLimit()->getFields())){
            $fields = $this->queryLimit()->getFields()['fields'];
            $returnAsArray = $this->queryLimit()->getFields()['returnAsArray'];
        }

        $query->get($this->quoteTableName(),null,$fields);
        $ret = FastDb::getInstance()->query($query);
        $total = null;
        if(in_array('SQL_CALC_FOUND_ROWS',$query->getLastQueryOptions())){
            $info = FastDb::getInstance()->rawQuery('SELECT FOUND_ROWS() as count')->getResult();
            if(isset($info[0]['count'])){
                $total = $info[0]['count'];
            }
        }
        $list = [];

        $hideFields = $this->queryLimit()->getHideFields() ?:[];

        if($returnAsArray){
            foreach ($ret->getResult() as $item){
                foreach ($hideFields as $field){
                    unset($item[$field]);
                }
                $list[] = $item;
            }
        }else{
            foreach ($ret->getResult() as $item){
                foreach ($hideFields as $field){
                    unset($item[$field]);
                }
                $list[] = self::setAttrStatic($item);
            }
        }
        $this->reset();

        return new ListResult($list,$total);
    }

    function chunk(callable $func,int $chunkSize = 10)
    {
        $page = 1;
        while (true){
            $this->queryLimit()->page($page,true,$chunkSize);
            $builder = clone $this->queryBuilder;
            $list = $this->all()->list();
            foreach ($list as $item){
                call_user_func($func,$item);
            }
            if(count($list) < $chunkSize){
                break;
            }else{
                $page++;
                $this->queryBuilder = $builder;
            }
        }
        $this->reset();
    }

    function asArray(bool $filterNull = false):array
    {
        $hideFields = $this->queryLimit()->getHideFields() ?:[];
        $temp = [];
        foreach ($this->_attributes as $key => $val){
            if($val instanceof ConvertObjectInterface){
                $val = $val->toValue();
            }else if($filterNull && $val === null){
                continue;
            }
            if (!in_array($key, $hideFields)){
                $temp[$key] = $val;
            }
        }
        $this->reset();
        return $temp;
    }

    function toArray(bool $filterNull = false):array
    {
        $hideFields = $this->queryLimit()->getHideFields() ?:[];
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        $temp = [];
        /** @var Property $property */
        foreach ($entityRef->allProperties() as $property){
            $val = null;
            if(isset($this->{$property->name()})){
                $val = $this->{$property->name()};
            }
            if($val instanceof ConvertObjectInterface){
                $val = $val->toValue();
            }else if($filterNull && $val === null){
                continue;
            }
            if (!in_array($property->name(), $hideFields)){
                $temp[$property->name()] = $val;
            }
        }
        $this->reset();
        return $temp;
    }

    public function count(string|null $field = '*', string $group = null): int|array
    {
        $fields = null;
        if (!empty($this->queryLimit()->getFields())) {
            $fields = $this->queryLimit()->getFields()['fields'];
        }
        $query = $this->queryLimit()->__getQueryBuilder();
        $hasFiled = false;

        if ($group) {
            $query->groupBy($group);
        }

        if (!empty($fields)) {
            $hasFiled = true;
            $temp = [];
            foreach ($fields as $fieldName){
                $temp[] = "COUNT(`{$fieldName}`) as $fieldName";
            }
            $fields = $temp;
            $query->get($this->quoteTableName(),1, $fields);
        } else {
            $query->get($this->quoteTableName(),1, "count({$field}) as count");
        }

        $ret = FastDb::getInstance()->query($query)->getResult();
        $this->reset();
        if (empty($ret)) {
            if ($hasFiled) {
                return [];
            }
            return 0;
        }
        $ret = $ret[0];
        if ($hasFiled) {
            return $ret;
        }

        return $ret['count'];
    }

    private function aggregate(string $aggregate, string|array $cols, string $group = null, bool $force = false): int|array|float
    {
        $multiFields = false;
        if (is_string($cols)) {
            $cols = [$cols];
        }

        if (count($cols) > 1) {
            $multiFields = true;
        }

        $str = "";
        while ($item = array_shift($cols)) {
            $str .= "{$aggregate}(`{$item}`) as {$item}";
            if (!empty($cols)) {
                $str .= " , ";
            }
        }
        $query = $this->queryLimit()->__getQueryBuilder();
        if ($group) {
            $query->groupBy($group);
        }
        $query->get($this->quoteTableName(), 1, $str);
        $ret = FastDb::getInstance()->query($query)->getResult();
        $this->reset();
        if (empty($ret)) {
            if ($multiFields) {
                return [];
            }
            return 0;
        }
        $ret = $ret[0];
        if ($multiFields) {
            if ($force) {
                foreach ($ret as &$row) {
                    $row = (float) $row;
                }
                unset($row);
            }
            return $ret;
        } else {
            $ret = array_values($ret)[0] ?: 0;
            if ($force) {
                return (float) $ret;
            }
            return $ret;
        }
    }

    public function sum(string|array $cols, string $group = null, bool $force = true): int|array|float
    {
        return $this->aggregate('SUM', $cols, $group, $force);
    }

    public function avg(string|array $cols, string $group = null, bool $force = true): int|array|float
    {
        return $this->aggregate('AVG', $cols, $group, $force);
    }

    public function max(string|array $cols, string $group = null, bool $force = true): int|array|float
    {
        return $this->aggregate('MAX', $cols, $group, $force);
    }

    public function min(string|array $cols, string $group = null, bool $force = true): int|array|float
    {
        return $this->aggregate('MIN', $cols, $group, $force);
    }

    function queryLimit():Query
    {
        if(!$this->queryBuilder){
            $this->queryBuilder = new Query($this);
        }
        return $this->queryBuilder;
    }

    function delete()
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        if($entityRef->getOnDelete()){
            $ret = $this->callHook($entityRef->getOnDelete()->callback);
            if($ret === false){
                return  false;
            }
        }
        $pk = $this->primaryKeyCheck('delete');
        $this->queryLimit()->where($pk,$this->{$pk});
        $query = $this->queryLimit()->__getQueryBuilder();
        $query->delete($this->quoteTableName());
        $this->reset();
        $ret = FastDb::getInstance()->query($query);
        return $ret->getConnection()->getLastAffectRows() >= 1;
    }

    public static function fastDelete(array|callable|string|int $deleteLimit,string $tableName = null):int|null|string
    {
        if (empty($deleteLimit) && 0 !== $deleteLimit) {
            return 0;
        }

        $entity = new static();
        if (empty($tableName)) {
            $tableName = $entity->quoteTableName();
        }
        $query = new QueryBuilder();
        if (is_array($deleteLimit)) {
            foreach ($deleteLimit as $key => $item) {
                if (is_array($item)) {
                    $query->where($key, ...$item);
                } else {
                    $query->where($key, $item);
                }
            }
        } else if (is_callable($deleteLimit)) {
            call_user_func($deleteLimit, $query);
        } else if (is_string($deleteLimit) || is_int($deleteLimit)) {
            $pk = ReflectionCache::getInstance()->parseEntity(static::class)->getPrimaryKey();
            if (empty($pk)) {
                $msg = "entity can not delete record without primary key define";
                throw new RuntimeError($msg);
            }

            if (is_string($deleteLimit)) {
                if (strpos($deleteLimit, ',') !== false) {
                    $pkIds = explode(',', $deleteLimit);
                    foreach ($pkIds as &$pkId) {
                        $pkId = intval($pkId);
                    }
                    unset($pkId);
                    $query->where($pk, $pkIds, 'IN');
                }
            } else {
                $query->where($pk, $deleteLimit);
            }
        }

        $query->delete($tableName);
        $ret = FastDb::getInstance()->query($query);
        return $ret->getConnection()->getLastAffectRows();
    }

    function update()
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        if($entityRef->getOnUpdate()){
            $ret = $this->callHook($entityRef->getOnUpdate()->callback);
            if($ret === false){
                return  false;
            }
        }
        $data = [];
        foreach ($this->compareData as $key => $compareDatum){
            $pVal = null;
            if(isset($this->{$key})){
                $pVal = $this->{$key};
            }
            if($pVal instanceof ConvertObjectInterface){
                $pVal = $pVal->toValue();
            }
            if($pVal !== $compareDatum){
                $data[$key] = $pVal;
            }
        }

        if(!empty($this->queryLimit()->getFields())){
            $fields = $this->queryLimit()->getFields()['fields'];
            if(!empty($fields)){
                foreach ($fields as $field){
                    unset($data[$field]);
                }
            }
        }
        if(empty($data)){
            return true;
        }
        $pk = $this->primaryKeyCheck('update');
        $this->queryLimit()->where($pk,$this->{$pk});
        $query = $this->queryLimit()->__getQueryBuilder();
        $query->update($this->quoteTableName(),$data);
        $ret = FastDb::getInstance()->query($query);
        $this->reset();
        return $ret->getConnection()->getLastAffectRows() > 0;
    }

    public function updateWithLimit(array $data, array|callable $queryLimit = null)
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        if ($entityRef->getOnUpdate()) {
            $ret = $this->callHook($entityRef->getOnUpdate()->callback);
            if($ret === false){
                return  false;
            }
        }

        $pk = $entityRef->getPrimaryKey();

        $updateData = [];
        foreach ($this->compareData as $key => $compareDatum){
            $pVal = null;
            if (isset($this->{$key})) {
                $pVal = $this->{$key};
            }
            if ($pVal instanceof ConvertObjectInterface) {
                $pVal = $pVal->toValue();
            }
            if ($pVal !== $compareDatum) {
                $updateData[$key] = $pVal;
            }
        }

        $updateData = array_merge($updateData, $data);

        if (!empty($this->queryLimit()->getFields())) {
            $fields = $this->queryLimit()->getFields()['fields'];
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    unset($updateData[$field]);
                }
            }
        }

        if (empty($updateData)) {
            return true;
        }

        if ($queryLimit) {
            if (is_array($queryLimit)) {
                foreach ($queryLimit as $key => $item) {
                    if (is_array($item)) {
                        $this->queryLimit()->where($key, ...$item);
                    } else {
                        $this->queryLimit()->where($key, $item);
                    }
                }
            } else if (is_callable($queryLimit)) {
                call_user_func($queryLimit, $this->queryLimit());
            }
        }

        if ($pk && !empty($this->{$pk})) {
            $this->queryLimit()->where($pk, $this->{$pk});
        }

        $query = $this->queryLimit()->__getQueryBuilder();
        $query->update($this->quoteTableName(), $data);
        $ret = FastDb::getInstance()->query($query);
        $this->reset();
        return $ret->getConnection()->getLastAffectRows();
    }

    public static function fastUpdate(array|callable|string|int $updateLimit,array $data,string $tableName = null):bool|int|string
    {
        $entity = new static();
        if(empty($tableName)){
            $tableName = $entity->quoteTableName();
        }
        $query = new QueryBuilder();
        if(is_array($updateLimit)){
            foreach ($updateLimit as $key => $item){
                $query->where($key,$item);
            }
        }else if(is_callable($updateLimit)){
            call_user_func($updateLimit,$query);
        }else{
            $pk = ReflectionCache::getInstance()->parseEntity(static::class)->getPrimaryKey();
            if(empty($pk)){
                $msg = "entity can not update record without primary key define";
                throw new RuntimeError($msg);
            }

            if (is_string($updateLimit)) {
                if (strpos($updateLimit, ',') !== false) {
                    $pkIds = explode(',', $updateLimit);
                    foreach ($pkIds as &$pkId) {
                        $pkId = intval($pkId);
                    }
                    unset($pkId);
                    $query->where($pk, $pkIds, 'IN');
                }
            } else {
                $query->where($pk,$updateLimit);
            }
        }
        $query->update($tableName,$data);
        $ret = FastDb::getInstance()->query($query);
        return $ret->getConnection()->getLastAffectRows();
    }

    /**
     * @param array $dataList
     * @param bool  $replace
     * @param bool  $transaction
     *
     * @return array
     * @throws RuntimeError
     * @throws \EasySwoole\Pool\Exception\Exception
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function insertAll(array $dataList, bool $replace = true, bool $transaction = true,bool $isStrict = false)
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        if (empty($entityRef->getPrimaryKey())){
            throw new RuntimeError('insertAll() needs primaryKey for model ' . static::class);
        }
        $primaryKey = $entityRef->getPrimaryKey();

        $returnAsArray = false;
        if (!empty($this->queryLimit()->getFields())) {
            $returnAsArray = $this->queryLimit()->getFields()['returnAsArray'];
        }

        // 开启事务
        if ($transaction){
            FastDb::getInstance()->begin();
        }

        $result = [];
        try {
            foreach ($dataList as $key => $row) {
                // 如果有设置更新
                if ($replace && isset($row[$primaryKey])) {
                    $model = (new static())->find($row[$primaryKey]);
                    unset($row[$primaryKey]);
                    $model->setData($row);
                    $model->update();
                } else {
                    $model = (self::setAttrStatic($row));
                    $model->insert();
                }
                if ($returnAsArray) {
                    $result[$key] = $isStrict?$model->toArray():$this->asArray();
                } else {
                    $result[$key] = $model;
                }
            }
            if ($transaction) {
                FastDb::getInstance()->commit();
            }
            $this->reset();
            return $result;
        } catch (\Throwable $throwable) {
            if ($transaction) {
                FastDb::getInstance()->rollback();
            }
            $this->reset();
            throw $throwable;
        }
    }

    function insert(array $updateDuplicateCols = null, bool $isStrict = false)
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        if($entityRef->getOnInsert()){
            $ret = $this->callHook($entityRef->getOnInsert()->callback);
            if($ret === false){
                return false;
            }
        }
        //插入的时候，null值一般无意义，default值在数据库层做。
        $data = $isStrict? $this->toArray() :$this->asArray();
        $query = $this->queryLimit()->__getQueryBuilder();
        if($updateDuplicateCols){
            $query->onDuplicate($updateDuplicateCols);
        }
        $query->insert($this->quoteTableName(),$data);
        $ret = FastDb::getInstance()->query($query);
        $isSuccess = false;
        //swoole客户端问题 https://github.com/swoole/swoole-src/issues/5202
        if($ret->getResult()){
            $isSuccess = true;
        }else if($ret->getConnection()->getLastAffectRows() >= 1){
            $isSuccess = true;
        }
        if($ret->getConnection()->getLastInsertId() >= 1){
            $ref = ReflectionCache::getInstance()->parseEntity(static::class);
            if($ref->getPrimaryKey()){
                $this->{$ref->getPrimaryKey()} = $ret->getConnection()->getLastInsertId();
                $data[$ref->getPrimaryKey()] =  $ret->getConnection()->getLastInsertId();
            }
            $isSuccess = true;
        }else if(!empty($updateDuplicateCols)){
            $isSuccess = true;
        }
        if($isSuccess){
            $this->setData($data,true);
        }
        return $isSuccess;
    }


    private function reset():void
    {
        $this->queryBuilder = null;
    }


    private function primaryKeyCheck(string $op,bool $emptyCheck = true):string
    {
        $entityRef = ReflectionCache::getInstance()->parseEntity(static::class);
        $pk = $entityRef->getPrimaryKey();
        if(empty($pk)){
            $msg = "can not {$op} entity without primary key set";
            throw new RuntimeError($msg);
        }
        if(empty($this->{$pk}) && $emptyCheck){
            $msg = "can not {$op} entity without primary key value";
            throw new RuntimeError($msg);
        }
        return $pk;
    }

    public function find(array|string|int $queryLimit = null): ?static
    {
        $fields = null;
        if (!empty($this->queryLimit()->getFields())) {
            $fields = $this->queryLimit()->getFields()['fields'];
        }

        if (is_array($queryLimit)) {
            foreach ($queryLimit as $key => $item) {
                if (is_array($item)) {
                    $this->queryLimit()->where($key, ...$item);
                } else {
                    $this->queryLimit()->where($key, $item);
                }
            }
        } else if ($queryLimit) {
            $pk = ReflectionCache::getInstance()->parseEntity(static::class)->getPrimaryKey();
            if (empty($pk)) {
                $msg = "entity can not find record without primary key define";
                throw new RuntimeError($msg);
            }
            $this->queryLimit()->where($pk, $queryLimit);
        }

        $query = $this->queryLimit()->__getQueryBuilder();
        if ($fields) {
            $query->fields($fields);
        }
        $query->get($this->quoteTableName(), 1);
        $ret = FastDb::getInstance()->query($query)->getResult();
        $this->reset();
        if (!empty($ret[0])) {
            return self::setAttrStatic($ret[0]);
        }

        return null;
    }

    public static function findRecord(callable|array|string|int $queryLimit, string $tableName = null): ?static
    {
        $entity = new static();
        if (empty($tableName)) {
            $tableName = $entity->quoteTableName();
        }
        $query = new QueryBuilder();
        if (is_array($queryLimit)) {
            foreach ($queryLimit as $key => $item) {
                if (is_array($item)) {
                    $query->where($key, ...$item);
                } else {
                    $query->where($key, $item);
                }
            }
        } else if (is_callable($queryLimit)) {
            call_user_func($queryLimit, $query);
        } else {
            $pk = ReflectionCache::getInstance()->parseEntity(static::class)->getPrimaryKey();
            if (empty($pk)) {
                $msg = "entity can not find record without primary key define";
                throw new RuntimeError($msg);
            }
            $query->where($pk, $queryLimit);
        }

        $query->get($tableName, 1);
        $ret = FastDb::getInstance()->query($query)->getResult();
        if (!empty($ret[0])) {
            return self::setAttrStatic($ret[0]);
        }

        return null;
    }

    static function  setAttrStatic($data): static
    {
        $entity = new static();
        $rules = $entity->rules();
        $attr = [];
        foreach ($rules as $rule) {
            foreach ($rule[0] as $item) {
                $attr[$item] = $item;
            }
        }
        $attrArr = array_keys($attr);
        foreach ($attrArr as $item) {
            $entity->_attributes[$item] = $data[$item] ?? null;
        }
        return new static($data);
    }

    public static function findAll(array|callable|string $queryLimit = null, string $tableName = null, bool $returnAsArray = false):mixed
    {
        $entity = new static();
        if (empty($tableName)) {
            $tableName = $entity->quoteTableName();
        }
        $query = new QueryBuilder();
        if (is_array($queryLimit)) {
            foreach ($queryLimit as $key => $item) {
                if (is_array($item)) {
                    $query->where($key, ...$item);
                } else {
                    $query->where($key, $item);
                }
            }
        } else if (is_callable($queryLimit)) {
            call_user_func($queryLimit, $query);
        } else if (is_string($queryLimit)) {
            $pk = ReflectionCache::getInstance()->parseEntity(static::class)->getPrimaryKey();
            if (empty($pk)) {
                $msg = "entity can not find all record without primary key define";
                throw new RuntimeError($msg);
            }

            if (is_string($queryLimit)) {
                if (strpos($queryLimit, ',') !== false) {
                    $pkIds = explode(',', $queryLimit);
                    foreach ($pkIds as &$pkId) {
                        $pkId = intval($pkId);
                    }
                    unset($pkId);
                    $query->where($pk, $pkIds, 'IN');
                }
            }
        }

        $query->get($tableName);
        $result = FastDb::getInstance()->query($query)->getResult();
        if (!$returnAsArray) {
            $list = [];
            foreach ($result as $item) {
                $list[] = self::setAttrStatic($item);
            }
            return $list;
        } else {
            return $result;
        }
    }


    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    protected function callHook(callable|string $callback):mixed
    {
        if(is_callable($callback)){
            return call_user_func($callback,$this);
        }else{
            if(method_exists($this,$callback)){
                return $this->$callback();
            }else{
                throw new RuntimeError("{$callback} no a method of class ".static::class);
            }
        }
    }

    protected function relateOne(?Relate $relate = null,string $tableName = null):null|array|AbstractEntity
    {
        $relate = $this->parseRelate($relate);
        /** @var AbstractEntity $temp */
        $temp = new $relate->targetEntity();

        $query = $this->queryLimit()->__getQueryBuilder();
        $fields = null;
        $returnAsArray = false;
        if(!empty($this->queryLimit()->getFields())){
            $fields = $this->queryLimit()->getFields()['fields'];
            $returnAsArray = $this->queryLimit()->getFields()['returnAsArray'];
        }
        if(isset($this->{$relate->selfProperty})){
            $selfValue = $this->{$relate->selfProperty};
        }else{
            $selfValue = null;
        }

        if(empty($tableName)){
            $tableName = $temp->quoteTableName();
        }

        $query->where($relate->targetProperty,$selfValue)
            ->get($tableName,2,$fields);

        $ret = FastDb::getInstance()->query($query)->getResult();
        $this->reset();
        if(empty($ret)){
            return null;
        }
        if(count($ret) > 1){
            $msg = "more than one record hit is no allow in relateOne method";
            throw new RuntimeError($msg);
        }
        if($returnAsArray){
            return $ret[0];
        }
        $temp->setData($ret[0]);
        return $temp;

    }

    protected function relateMany(?Relate $relate = null,string $tableName = null)
    {
        $relate = $this->parseRelate($relate);
        /** @var AbstractEntity $temp */
        $temp = new $relate->targetEntity();

        $query = $this->queryLimit()->__getQueryBuilder();
        $fields = null;
        $returnAsArray = false;
        if(!empty($this->queryLimit()->getFields())){
            $fields = $this->queryLimit()->getFields()['fields'];
            $returnAsArray = $this->queryLimit()->getFields()['returnAsArray'];
        }
        if(isset($this->{$relate->selfProperty})){
            $selfValue = $this->{$relate->selfProperty};
        }else{
            $selfValue = null;
        }

        if(empty($tableName)){
            $tableName = $temp->quoteTableName();
        }

        $query->where($relate->targetProperty,$selfValue)
            ->get($tableName,null,$fields);

        $ret = FastDb::getInstance()->query($query)->getResult();

        $final = [];
        foreach ($ret as $item){
            if($returnAsArray){
                $final[] = $item;
            }else{
                $final[] = new $relate->targetEntity($item);
            }
        }
        $total = null;
        if(in_array('SQL_CALC_FOUND_ROWS',$query->getLastQueryOptions())){
            $info = FastDb::getInstance()->rawQuery('SELECT FOUND_ROWS() as count')->getResult();
            if(isset($info[0]['count'])){
                $total = $info[0]['count'];
            }
        }
        $this->reset();
        return new ListResult($final,$total);
    }

    private function parseRelate(?Relate $relate = null)
    {
        if($relate == null){
            //解析是否有注释Relate
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT,3);
            $method = $trace[2]['function'];
            $ref = new \ReflectionClass(static::class);
            $ret = $ref->getMethod($method)->getAttributes(Relate::class);
            if(empty($ret)){
                $msg = "{$method} did not define Relate attribute in ".static::class;
                throw new RuntimeError($msg);
            }
            $relate = new Relate(...$ret[0]->getArguments());
        }
        //检查目标对象
        $check = ReflectionCache::getInstance()->parseEntity($relate->targetEntity);
        //在没有指定目标和当前属性的情况下，都以自身主键为准。
        if(empty($relate->selfProperty)){
            $relate->selfProperty = $this->primaryKeyCheck('relate',false);
        }else{
            if(!key_exists($relate->selfProperty,$this->compareData)){
                $msg = "{$relate->selfProperty} is not a define property in ".static::class;
                throw new RuntimeError($msg);
            }
        }
        if(!key_exists($relate->targetProperty,$check->allProperties())){
            $msg = "{$relate->selfProperty} is not a define property in {$relate->targetEntity}";
            throw new RuntimeError($msg);
        }
        return $relate;
    }
}
