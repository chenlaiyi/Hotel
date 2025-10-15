## �������
ʹ��������Ļ�����Ҫ���ݿ�����֧������������ MySQL �� MyISAM ��֧����������Ҫʹ�� InnoDB ���档

## �ֶ����������߼����磺
```php
<?php
try {
    // ��������
    FastDb::getInstance()->begin();
    $user = User::findRecord(1000);
    $user->delete();
    // �ύ����
    FastDb::getInstance()->commit();
} catch (\Throwable $throwable) {
    // �ع�����
    FastDb::getInstance()->rollback();
}

// ����ʹ�� `invoke` ����
FastDb::getInstance()->invoke(function (\EasySwoole\FastDb\Mysql\Connection $connection) {
    try {
        // ��������
        FastDb::getInstance()->begin($connection);
        $user = User::findRecord(1000);
        $user->delete();
        // �ύ����
        FastDb::getInstance()->commit($connection);
    } catch (\Throwable $throwable) {
        // �ع�����
        FastDb::getInstance()->rollback($connection);
    }

    return true;
});
```