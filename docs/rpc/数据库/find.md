# ��ѯ

### ��ѯ��������

    findRecord() ����������ֵΪ��ǰģ�͵Ķ���ʵ��������ʹ��ģ�͵ķ�����
    
    find() ����������ֵΪ��ǰģ�͵Ķ���ʵ��������ʹ��ģ�͵ķ�����

## ��ȡ�������ݵķ���������

```php
    <?php
    // ȡ������Ϊ1������
    $user = User::findRecord(1);
    echo $user->name;
    
    // ʹ�������ѯ
    $user = User::findRecord(['name' => 'easyswoole']);
    echo $user->name;
    
    // ʹ�ñհ���ѯ
    $user = User::findRecord(function (\EasySwoole\Mysqli\QueryBuilder $query) {
        $query->where('name', 'easyswoole');
    });
    echo $user->name;

```

## ������ʵ����ģ�ͺ���ò�ѯ����
```php
$user = new User();
// ��ѯ��������
$user->queryLimit()->where('name', 'easyswoole');
$userModel = $user->find();
echo $userModel->name;
```
## ��ȡ�������
    
    findAll() �������ص���һ������ģ�Ͷ���Ķ�ά��ͨ������߶������顣���صĽ�������ܲ��� returnAsArray ��Ӱ�졣
    
    all() �������ص��� \EasySwoole\FastDb\Beans\ListResult ��Ķ���
```php
<?php
// ʹ��������ѯ
$list = User::findAll('1,2');

// ʹ�������ѯ
$list = User::findAll(['status' => 1]);

// ʹ�ñհ���ѯ
$list = User::findAll(function (\EasySwoole\Mysqli\QueryBuilder $query) {
    $query->where('status', 1)->limit(3)->orderBy('id', 'asc');
}, null, false);
foreach ($list as $key => $user) {
    echo $user->name;
}
```
    ���鷽ʽ�ͱհ���ʽ�����ݲ�ѯ���������ڣ����鷽ʽֻ�ܶ����ѯ�������հ���ʽ����֧�ָ����������������������������Ƶȡ�
```php
<?php
// ��ȡ������� ��ʹ��������ѯ
/** @var User[] $users */
$users = (new User())->all(); // ���ؽ����\EasySwoole\FastDb\Beans\ListResult ��Ķ���
foreach ($users as $user) {
    echo $user->name . "\n";
}

// ��ȡ������� ʹ��������ѯ
$userModel = new User();
$userModel->queryLimit()->where('id', [401, 403], 'IN')->where('name', 'easyswoole-1');
$users = $userModel->all(); // ���ؽ����\EasySwoole\FastDb\Beans\ListResult ��Ķ���
foreach ($users as $user) {
    echo $user->name . "\n";
}
```

## ת���ֶ�
    
�������������ݱ� student_info��DDL ���£�
    
    CREATE TABLE `student_info` (
      `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
      `studentId` int unsigned NOT NULL DEFAULT '0' COMMENT 'student id',
      `address` json DEFAULT NULL COMMENT 'address',
      `note` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'note',
      `sex` tinyint unsigned NOT NULL DEFAULT '0' COMMENT 'sex:1=male 2=female 0=unknown',
      PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
���ǿ��Զ� address �� sex �ֶ���ת������������ҵ�񿪷��������������õ��� php8 ��ö�����ԡ�

## ����Ϊģ��Ϊ��
```php
<?php
namespace EasySwoole\FastDb\Tests\Model;

use EasySwoole\FastDb\AbstractInterface\AbstractEntity;
use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\FastDb\Tests\Model\Address;
use EasySwoole\FastDb\Tests\Model\SexEnum;

class StudentInfoModel extends AbstractEntity
{
    #[Property(isPrimaryKey: true)]
    public int $id;

    #[Property()]
    public int $studentId;

    #[Property(
        convertObject: Address::class
    )]
    public Address $address;

    #[Property]
    public ?string $note;

    #[Property(
        convertObject: SexEnum::class
    )]
    public SexEnum $sex;

    function tableName(): string
    {
        return "student_info";
    }
}
```
Address.php
```php
<?php
namespace EasySwoole\FastDb\Tests\Model;

use EasySwoole\FastDb\AbstractInterface\ConvertJson;

class Address extends ConvertJson
{
    public $city;
    public $province;
}
```

SexEnum.php ʹ��ö�����ԡ�
```php
    <?php
    namespace EasySwoole\FastDb\Tests\Model;
    
    use EasySwoole\FastDb\AbstractInterface\ConvertObjectInterface;
    
    enum SexEnum implements ConvertObjectInterface
    {
        case UNKNUWN;
        case MALE;
        case FEMAILE;
    
        public static function toObject(mixed $data): object
        {
            switch ($data){
                case 1:{
                    return self::MALE;
                }
                case 2:{
                    return self::FEMAILE;
                }
                default:{
                    return self::UNKNUWN;
                }
            }
        }
    
        function toValue()
        {
            return match ($this){
                self::MALE=>1,
                self::FEMAILE=>2,
                default=>0
            };
        }
    }
```
## ת���ֶ�ʹ��ʾ����

```php
<?php
// ��Ӽ�¼
$address = new \EasySwoole\FastDb\Tests\Model\Address();
$address->province = 'FuJian';
$address->city = 'XiaMen';
$sex = \EasySwoole\FastDb\Tests\Model\SexEnum::MALE;
$model = new StudentInfoModel([
    'studentId' => 1,
    'address'   => $address->toValue(),
    'sex'       => $sex->toValue(),
    'note'      => 'this is note',
]);
// or
// $model->address = $address;
// $model->sex = $sex;
$model->insert(); // INSERT  INTO `student_info` (`studentId`, `address`, `note`, `sex`)  VALUES (1, '{\"city\":\"XiaMen\",\"province\":\"FuJian\"}', 'this is note', 1)

// ��ѯһ������
$studentInfo = StudentInfoModel::findRecord(1);
var_dump($studentInfo->address->city); // "XiaMen"
var_dump($studentInfo->address->province); // "FuJian"
var_dump($studentInfo->sex); // ö������ enum(EasySwoole\FastDb\Tests\Model\SexEnum::MALE)
var_dump($studentInfo->toArray(false));

// ��ѯ��������
$studentInfo = new StudentInfoModel();
$studentInfos = $studentInfo->all();
foreach ($studentInfos as $studentInfo) {
    var_dump($studentInfo->address->city);
    var_dump($studentInfo->address->province);
    var_dump($studentInfo->sex);
    var_dump($studentInfo->toArray(false));
}
```
## �Զ��巵�ؽ������

findAll() ������ returnAsArray �����������ò�ѯ�ķ��ض�������ƣ�Ĭ����ģ�Ͷ��󣩡�
```php
<?php
$returnAsArray = true;
(new User())->findAll(null, null, $returnAsArray);
all() �������� queryLimit() ������ fields() ������ returnAsArray �����������ò�ѯ�ķ��ض�������ƣ�Ĭ����ģ�Ͷ��󣩡�

<?php
$returnAsArray = true;
(new User())->queryLimit()->fields(null, $returnAsArray);
```
## ���ݷ������� chunk
ģ��Ҳ֧�ֶԷ��ص����ݷ��������ر����������Ҫ�����ǧ�ϰ������ݿ��¼�����Կ���ʹ�� chunk �������÷���һ�λ�ȡ�������һС�飬Ȼ�����ÿһС�����ݵ�Ҫ����ıհ����÷����ڱ�д����������ݿ��¼��ʱ��ǳ����á�

���磬���ǿ���ȫ���û������ݽ��з�������ÿ�δ��� 20 ���û���¼��
```php
<?php
(new User())->chunk(function (User $user) {
    // ���� user ģ�Ͷ���
    $user->updateWithLimit(['status' => 1]);
}, 20);
```

## ��ҳ��ѯ page
����˵����

    \EasySwoole\FastDb\Beans\Query::page ����

    function page(?int $page,bool $withTotalCount = false,int $pageSize = 10): Query

### ʹ��ʾ����
// ʹ�������ķ�ҳ��ѯ �����л��� withTotalCount=false
// ��ѯ ��1ҳ ÿҳ10�� page=1 pageSize=10
```php
    $user = new User();
    $user->queryLimit()->page(1, false, 10);
    $resultObject = $user->all();
    foreach ($resultObject as $oneUser) {
        var_dump($oneUser->name);
    }
    
    // ʹ�������ķ�ҳ��ѯ ���л��� withTotalCount=true
    // ��ѯ ��1ҳ ÿҳ10�� page=1 pageSize=10
    $user = new User();
    $user->queryLimit()->page(1, true, 10)->where('id', 3, '>');
    $resultObject = $user->all();
    $total = $resultObject->totalCount(); // ��������
    foreach ($resultObject as $oneUser) {
        var_dump($oneUser->name);
    }
    var_dump($total);
```
