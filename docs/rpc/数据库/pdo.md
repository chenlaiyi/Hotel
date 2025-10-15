# 数据库操作

## 查询条件

### or

```php
    ['or',['mobile'=>123],['id'=>1]]

```
### and

```php
    ['and',['mobile'=>123],['id'=>1]]
```

### 复杂查询

```php
    ['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123]],['store_id'=>111]]
```


## 分页查询

```php
    PdoQuery::getInstance()->findPaginated('user',1,11);
```

## 查询单个记录

```php
$result = PdoQuery::getInstance()->findOneBy('user',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123]],['store_id'=>111]]);

```

## 查询所有记录
```php
$result = PdoQuery::getInstance()->findAllBy('user',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123],['store_id'=>111]]]);
```
## 查询分页
```php
$result = PdoQuery::getInstance()->findAllPaginated('user',1,11);
```
## 查询单个字段
```php
$result = PdoQuery::getInstance()->findfield('user','uid',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123]],['store_id'=>111]],'id');
```

## 查询最后执行的sql

```php
 $sql = PdoQuery::getInstance()->getLastSql();
```

# 兼容yii->query语法

### 查询单个记录

```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>2323,'username'=>'admin888'])->one();
```

### 查询最后执行的sql
```php
    $sql = $PdoQuery->getLastSql();
```
### 查询分页
```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>2323,'username'=>'admin888'])->paginate(1,10);
```
### 查询所有记录
```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>232
3,'username'=>'admin888'])->all();
```
### 查询单个字段
```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>2323,'username'=>'admin888'])->select('id,username')->one();
```
