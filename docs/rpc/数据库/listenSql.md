# ���� sql
�����������ݿ�ִ�е��κ� SQL �������м�����������ע�����ӳ�ʱ���� onQuery �ص�������ʹ�����·�����
```php
<?php
$config = new \EasySwoole\FastDb\Config();
$config->setHost('127.0.0.1');
$config->setUser('easyswoole');
$config->setPassword('');
$config->setDatabase('easyswoole');
$config->setName('default');
FastDb::getInstance()->addDb($config);

// ���� onQuery �ص�����
FastDb::getInstance()->setOnQuery(function (\asySwoole\FastDb\Mysql\QueryResult $queryResult) {
   // ��ӡ sql
    if ($queryResult->getQueryBuilder()) {
        echo $queryResult->getQueryBuilder()->getLastQuery() . "\n";
    } else {
        echo $queryResult->getRawSql() . "\n";
    }
});
```