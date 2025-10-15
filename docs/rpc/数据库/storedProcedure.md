## 存储过程
    如果我们定义了一个数据库存储过程 sp_query，可以使用下面的方式调用：
```php
$result = FastDb::getInstance()->rawQuery('call sp_query(8)');
```