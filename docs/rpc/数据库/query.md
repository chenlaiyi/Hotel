# ���������� Query

    \EasySwoole\FastDb\Beans\Query �����ڹ�����ģ����ʹ�ù����ѯ�����¡�ɾ����������

## ֧�ֵķ����У�
    
    limit(int $num,bool $withTotalCount = false):Query
    page(?int $page,bool $withTotalCount = false,int $pageSize = 10):Query
    fields(?array $fields = null,bool $returnAsArray = false):Query
    hideFields(array|string $hideFields):Query
    getHideFields():?array
    getFields():?array
    orderBy($orderByField, $orderbyDirection = "DESC", $customFieldsOrRegExp = null):Query
    where(string $col, mixed $whereValue, $operator = '=', $cond = 'AND'):Query
    orWhere(string $col, mixed $whereValue, $operator = '='):Query
    join($joinTable, $joinCondition, $joinType = ''):Query
    func(callable $func):Query
    returnEntity():AbstractEntity

����ģ���е� queryLimit() �ķ���ֵ��Ϊ \EasySwoole\FastDb\Beans\Query �࣬���㿪���ߴ����ӵĲ�ѯ������

## ��ѯʱʾ��

```php
    $user = new User();
    $user->queryLimit()->where('name', 'easyswoole');   # ʹ�� Query ��� where ����
    $userModel = $user->find();
    echo $userModel->name;
```
## ����ʱʾ��
```php
$user = new User();
$user->queryLimit()->where('id', 1);   # ʹ�� Query ��� where ����
$user->updateWithLimit(['name' => 'easyswoole']);
```