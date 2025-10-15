# 操作单个状态类字段


# 参数
```php
name: status
pk: 19
pkColumn: id
value: 1
```

# modelClass配置

```php
class MemberroleController extends AController
{

    public string $modelSearchName = "MemberRole";


    public $modelClass = 'common\plugins\diandi_auth\models\AuthMemberRole';
}
```

# 接口地址
```
/admin/diandi_auth/memberrole/change-field
```