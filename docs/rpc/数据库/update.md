# ����

*     update() ����������ֵΪ bool ���͵�ֵ��ֵΪ trueʱ��ʾӰ����������0�ĸ��³ɹ���
*     updateWithLimit() ����������ֵΪ int ���͵�ֵ��ֵ��ʾ����Ӱ���������
*     fastUpdate ����������ֵΪ int ���͵�ֵ��ֵ��ʾ����Ӱ���������

## ���Ҳ�����
    
    ��ȡ�����ݺ󣬸����ֶ����ݺ�������ݡ�
```php
    <?php
    $user = User::findRecord(1);
    $user->name = 'easyswoole111';
    $user->email = 'easyswoole111@qq.com';
    $user->update();
```

## ֱ�Ӹ�������
    
    Ҳ����ֱ�Ӵ�������������������

```php
    $user = new User();
    // updateWithLimit �����ڶ�������Ϊ��������
    $user->updateWithLimit([
        'name'  => 'easyswoole112',
        'email' => 'easyswoole112@qq.com'
    ], ['id' => 1]);
```


## ���þ�̬����

```php
    User::fastUpdate(['id' => 1], [
        'name'  => 'easyswoole112',
        'email' => 'easyswoole112@qq.com'
    ]);
    User::fastUpdate(function (\EasySwoole\Mysqli\QueryBuilder $queryBuilder) {
      $queryBuilder->where('id', 1);
    }, [
        'name'  => 'easyswoole112',
        'email' => 'easyswoole112@qq.com'
    ]);
    
    User::fastUpdate(1, [
        'name'  => 'easyswoole112',
        'email' => 'easyswoole112@qq.com'
    ]);
    
    User::fastUpdate('1,2', [
        'name'  => 'easyswoole112',
        'email' => 'easyswoole112@qq.com'
    ]);
```



## ʹ�� Query ������ֱ�Ӹ������ݡ�

```php
    <?php
    $user = new User();
    $user->queryLimit()->where('id', 1);
    $user->updateWithLimit(['name' => 'easyswoole']);
```

## �հ�����
    
    ����ͨ���հ�����ʹ�ø����ӵĸ������������磺

```php
<?php
    $user = new User();
    $user->updateWithLimit(['name' => 'easyswoole'], function (\EasySwoole\FastDb\Beans\Query $query) {
        // ����statusֵΪ1 ����id����10������
        $query->where('status', 1)->where('id', 10, '>');
    }); // UPDATE `easyswoole_user` SET `name` = 'easyswoole' WHERE  `status` = 1  AND `id` > 10
```
