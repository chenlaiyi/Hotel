## һ��һ���� hasOne
�������
    
    ����һ��һ���������磬ÿ���û�����һ������������Ϣ�����Ƕ��� User ģ�����£�

```php
<?php
declare(strict_types=1);

namespace EasySwoole\FastDb\Tests\Model;

use EasySwoole\FastDb\AbstractInterface\AbstractEntity;
use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\FastDb\Attributes\Relate;
use EasySwoole\FastDb\Tests\Model\UserProfile;

/**
 * @property int    $id
 * @property string $name
 * @property string $email
 */
class User extends AbstractEntity
{
    #[Property(isPrimaryKey: true)]
    public int $id;
    #[Property]
    public ?string $name;
    #[Property]
    public ?string $email;

    public function tableName(): string
    {
        return 'easyswoole_user';
    }

    #[Relate(
        targetEntity: UserProfile::class,
        targetProperty: 'user_id' // ����ģ�͵����ݱ������
    )]
    public function profile()
    {
        return $this->relateOne();
    }
}
```
## ������ѯ
    
����ù���֮�󣬾Ϳ���ʹ������ķ�����ȡ�������ݣ�

```php
<?php
$user = User::findRecord(1);
// ��� UserProfile ����ģ�͵�email����
echo $user->profile()->email;
```

### һ�Զ���� hasMany
�������
```php
<?php
declare(strict_types=1);

namespace EasySwoole\FastDb\Tests\Model;

use EasySwoole\FastDb\AbstractInterface\AbstractEntity;
use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\FastDb\Attributes\Relate;
use EasySwoole\FastDb\Tests\Model\UserCar;

/**
 * @property int    $id
 * @property string $name
 * @property int    $status
 * @property int    $score
 * @property string $email
 */
class User extends AbstractEntity
{
    #[Property(isPrimaryKey: true)]
    public int $id;
    #[Property]
    public ?string $name;
    #[Property]
    public ?int $status;
    #[Property]
    public ?int $score;
    #[Property]
    public ?int $create_time;
    #[Property]
    public ?string $info;
    #[Property]
    public ?string $foo;
    #[Property]
    public ?string $bar;
    #[Property]
    public ?int $login_time;
    #[Property]
    public ?int $login_times;
    #[Property]
    public ?int $read;
    #[Property]
    public ?string $title;
    #[Property]
    public ?string $content;
    #[Property]
    public ?string $email;

    public function tableName(): string
    {
        return 'easyswoole_user';
    }

    #[Relate(
        targetEntity: UserCar::class,
        targetProperty: 'user_id'
    )]
    public function cars()
    {
        return $this->relateMany();
    }
}
```
### ������ѯ

```php
<?php
$article = User::findRecord(1);
// ��ȡ�û�ӵ�е����г���Ʒ��
$listResult = $article->cars();
foreach ($listResult as $userCar) {
    echo $userCar->car_name . "\n";
}
// or
$objectArr = $listResult->toArray(); // ת��Ϊ ��������
foreach ($objectArr as $userCar) {
    echo $userCar->car_name . "\n";
}
```