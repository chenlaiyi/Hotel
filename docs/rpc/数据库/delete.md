## ɾ��

    delete() ����������ֵΪ bool ���͵�ֵ��ֵΪ trueʱ��ʾӰ����������0��ɾ���ɹ���
    
    fastDelete() ��������ֵΪ int ���͵�ֵ

    ɾ���ɹ�ʱ����ֵΪ int ���͵�ֵ����ʾɾ������Ӱ�������
    ɾ��ʧ��ʱ����ֵΪ null

### ���Ҳ�ɾ��
    
    ��ȡ�����ݺ�Ȼ��ɾ�����ݡ�
```php

    <?php
    $user = User::findRecord(1);
    $user->delete();
```
## ��������ɾ��
    
    ֱ�ӵ��þ�̬����
```php
    User::fastDelete(1);
```

## ֧������ɾ���������
    
    �� fastDelete ���������ֵ���������ַ����Ϳ����飩��ʱ�򲻻����κε�����ɾ��������������0������Ч�ġ�
```php
    User::fastDelete('1,2,3');
```

## ����ɾ��
    ʹ�������������ɾ�������磺
```php
    <?php
    // ɾ��״̬Ϊ0������
    User::fastDelete(['status' => 0]);
```
## ��֧��ʹ�ñհ�ɾ�������磺

```php
    <?php
    User::fastDelete(function (\EasySwoole\Mysqli\QueryBuilder $query) {
        $query->where('id', 10, '>');
    });
```