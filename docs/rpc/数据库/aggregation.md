# �ۺ�
    
    ��ģ����Ҳ���Ե������ݿ�ľۺϷ������в�ѯ�����磺

## ����	˵��
    count	ͳ��������������Ҫͳ�Ƶ��ֶ�������ѡ��
    max	��ȡ���ֵ��������Ҫͳ�Ƶ��ֶ��������룩
    min	��ȡ��Сֵ��������Ҫͳ�Ƶ��ֶ��������룩
    avg	��ȡƽ��ֵ��������Ҫͳ�Ƶ��ֶ��������룩
    sum	��ȡ�ܷ֣�������Ҫͳ�Ƶ��ֶ��������룩
## count
```php    
    <?php
    $user = new User();
    $user->count();
    // SELECT  COUNT(*) as count FROM `easyswoole_user` LIMIT 1
    
    $user->count('id', 'name');
    // SELECT  COUNT(id) as count FROM `easyswoole_user` GROUP BY name  LIMIT 1
    
    $user->queryLimit()->fields(['id', 'name']);
    $user->count(null, 'name');
    // SELECT  COUNT(`id`) as id, COUNT(`name`) as name FROM `easyswoole_user` GROUP BY name  LIMIT 1
```

## max

```php

    $user = new User();
    $user->max('id');
    // SELECT  MAX(`id`) as id FROM `easyswoole_user` LIMIT 1
    
    $user->max('id', 'name');
    // SELECT  MAX(`id`) as id FROM `easyswoole_user` GROUP BY name  LIMIT 1
    
    $user->max(['id', 'name'], 'name');
    // SELECT  MAX(`id`) as id , MAX(`name`) as name FROM `easyswoole_user` GROUP BY name  LIMIT 1

```
### min

```php
    <?php
    $user = new User();
    $user->min('id');
    // SELECT  MIN(`id`) as id FROM `easyswoole_user` LIMIT 1
    
    $user->min('id', 'name');
    // SELECT  MIN(`id`) as id FROM `easyswoole_user` GROUP BY name  LIMIT 1
    
    $user->min(['id', 'name'], 'name');
    // SELECT  MIN(`id`) as id , MIN(`name`) as name FROM `easyswoole_user` GROUP BY name  LIMIT 1
```
## avg
```php
    <?php
    $user = new User();
    $user->avg('id');
    // SELECT  AVG(`id`) as id FROM `easyswoole_user` LIMIT 1
    
    $user->avg('id', 'name');
    // SELECT  AVG(`id`) as id FROM `easyswoole_user` GROUP BY name  LIMIT 1
    
    $user->avg(['id', 'name'], 'name');
// SELECT  AVG(`id`) as id , AVG(`name`) as name FROM `easyswoole_user` GROUP BY name  LIMIT 1
```


## sum

```php
    <?php
    $user = new User();
    $user->sum('id');
    // SELECT  SUM(`id`) as id FROM `easyswoole_user` LIMIT 1
    
    $user->sum('id', 'name');
    // SELECT  SUM(`id`) as id FROM `easyswoole_user` GROUP BY name  LIMIT 1
    
    $user->sum(['id', 'name'], 'name');
    // SELECT  SUM(`id`) as id , SUM(`name`) as name FROM `easyswoole_user` GROUP BY name  LIMIT 1
```