
##  ����

### ���һ������
    insert() ����������ֵΪ bool ���͵�ֵ������ֵΪ true ��ʾ��ӳɹ�������ֵΪ false ��ʾ���ʧ�ܡ�

### ��һ����ʵ����ģ�Ͷ����ֵ�����棺

```php
    <?php
    $user = new User();
    $user->name = 'easyswoole';
    $user->email = 'easyswoole@qq.com';
    $user->insert();
```
    // �൱�� sql: INSERT  INTO `easyswoole_user` (`name`, `email`)  VALUES ('easyswoole', 'easyswoole@qq.com')
###  setData ����������ֵ��

```php
    <?php
    $user = new User();
    $user->setData([
        'name'  => 'easyswoole',
        'email' => 'easyswoole@qq.com'
    ]);
    $user->insert();
```

### ����ֱ����ʵ������ʱ��������
```php
    <?php
    $user = new User([
        'name'  => 'easyswoole',
        'email' => 'easyswoole@qq.com'
    ]);
    $user->insert();
```

## ��ȡ����ID

    ���Ҫ��ȡ�������ݵ�����ID������ʹ������ķ�ʽ��
```php
    <?php
    $user = new User();
    $user->name = 'easyswoole';
    $user->email = 'easyswoole@qq.com';
    $user->insert();
    // ��ȡ����ID
    echo $user->id;
```

    ע��������ʵ�ǻ�ȡģ�͵��������������������� id������ user_id �Ļ�����ʵ��ȡ����ID�ͱ��������
```php
    <?php
    $user = new User();
    $user->name = 'easyswoole';
    $user->email = 'easyswoole@qq.com';
    $user->insert();
    // ��ȡ����ID
    echo $user->user_id;
```

## ��Ӷ�������

    insertAll() �����������ݷ��ص��ǰ�������ģ�ͣ�������ID���Ķ������� �� ��ͨ���顣
    
    insertAll() �����ķ���������ģ�͵� queryLimit ���� �� fields ���Ե� returnAsArray ����Ӱ�죨���ܷ�����ͨ���飩��

## ֧����������������ʹ�ã�

```php
    <?php
    $user = new User();
    $list = [
        ['name' => 'easyswoole-1', 'email' => 'easyswoole1@qq.com'],
        ['name' => 'easyswoole-2', 'email' => 'easyswoole2@qq.com']
    ];
    $user->insertAll($list); // ���Ϊ ��������
    
    $user = new User();
    $list = [
        ['name' => 'easyswoole-1', 'email' => 'easyswoole1@qq.com'],
        ['name' => 'easyswoole-2', 'email' => 'easyswoole2@qq.com']
    ];
    $user->queryLimit()->fields(null, true);
    $user->insertAll($list); // ���Ϊ ��ͨ����
```

    insertAll ������������Ĭ�ϻ��Զ�ʶ����������Ҫ�������Ǹ��²������������д���������ʱ�����Ϊ�Ǹ��²������������Ҫ������������������������ʹ������ķ�ʽ��

```php
    <?php
    $user = new User;
    $list = [
        ['id' => 1, 'name' => 'easyswoole-1', 'email' => 'easyswoole1@qq.com'],
        ['id' => 2, 'name' => 'easyswoole-2', 'email' => 'easyswoole2@qq.com']
    ];
    $user->insertAll($list, false);
```

## onInsertע��

    �޸� User ģ�����ļ������ OnInsert ע�� �� onInsert ������onInsert �������ڶ����ǰ��������һЩ����

```php
    
    <?php
    
    declare(strict_types=1);
    
    namespace App\Model;
    
    use EasySwoole\FastDb\AbstractInterface\AbstractEntity;
    use EasySwoole\FastDb\Attributes\Hook\OnInsert;
    // ...
    
    /**
     * @property int    $id
     * @property string $name
     * @property int    $status
     * @property int    $create_time
     * @property string $email
     */
    #[OnInsert('onInsert')]
    class User extends AbstractEntity
    {
        // ...
    
        public function onInsert()
        {
            if (empty($this->create_time)) {
                $this->create_time = time();
            }
            if (empty($this->status)) {
                $this->status = 1;
            }
        }
    }
```

    Ȼ������������

```php

    <?php
    $user = new User();
    $user->name = 'easyswoole';
    $user->email = 'easyswoole@qq.com';
    $user->insert(); // INSERT  INTO `easyswoole_user` (`name`, `status`, `create_time`, `email`)  VALUES ('easyswoole', 1, 1704521166, 'easyswoole@qq.com')
    ON DUPLICATE KEY UPDATE
    <?php
    $user = new User();
    $user->name = 'easyswoole100';
    $updateDuplicateCols = ['name'];
    $user->insert($updateDuplicateCols); // INSERT  INTO `easyswoole_user` (`name`, `status`, `create_time`)  VALUES ('easyswoole100', 1, 1704521621) ON DUPLICATE KEY UPDATE `name` = 'easyswoole100'
    
    $user = new User();
    $user->name = 'easyswoole100';
    $updateDuplicateCols = ['name', 'id' => 1];
    $user->insert($updateDuplicateCols); // INSERT  INTO `easyswoole_user` (`name`, `status`, `create_time

```