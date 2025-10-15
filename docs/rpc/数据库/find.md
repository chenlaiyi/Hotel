# 查询

### 查询单个数据

    findRecord() 方法，返回值为当前模型的对象实例，可以使用模型的方法。
    
    find() 方法，返回值为当前模型的对象实例，可以使用模型的方法。

## 获取单个数据的方法包括：

```php
    <?php
    // 取出主键为1的数据
    $user = User::findRecord(1);
    echo $user->name;
    
    // 使用数组查询
    $user = User::findRecord(['name' => 'easyswoole']);
    echo $user->name;
    
    // 使用闭包查询
    $user = User::findRecord(function (\EasySwoole\Mysqli\QueryBuilder $query) {
        $query->where('name', 'easyswoole');
    });
    echo $user->name;

```

## 或者在实例化模型后调用查询方法
```php
$user = new User();
// 查询单个数据
$user->queryLimit()->where('name', 'easyswoole');
$userModel = $user->find();
echo $userModel->name;
```
## 获取多个数据
    
    findAll() 方法返回的是一个包含模型对象的二维普通数组或者对象数组。返回的结果类型受参数 returnAsArray 的影响。
    
    all() 方法返回的是 \EasySwoole\FastDb\Beans\ListResult 类的对象。
```php
<?php
// 使用主键查询
$list = User::findAll('1,2');

// 使用数组查询
$list = User::findAll(['status' => 1]);

// 使用闭包查询
$list = User::findAll(function (\EasySwoole\Mysqli\QueryBuilder $query) {
    $query->where('status', 1)->limit(3)->orderBy('id', 'asc');
}, null, false);
foreach ($list as $key => $user) {
    echo $user->name;
}
```
    数组方式和闭包方式的数据查询的区别在于，数组方式只能定义查询条件，闭包方式可以支持更多的连贯操作，包括排序、数量限制等。
```php
<?php
// 获取多个数据 不使用条件查询
/** @var User[] $users */
$users = (new User())->all(); // 返回结果：\EasySwoole\FastDb\Beans\ListResult 类的对象
foreach ($users as $user) {
    echo $user->name . "\n";
}

// 获取多个数据 使用条件查询
$userModel = new User();
$userModel->queryLimit()->where('id', [401, 403], 'IN')->where('name', 'easyswoole-1');
$users = $userModel->all(); // 返回结果：\EasySwoole\FastDb\Beans\ListResult 类的对象
foreach ($users as $user) {
    echo $user->name . "\n";
}
```

## 转换字段
    
例如我们有数据表 student_info，DDL 如下：
    
    CREATE TABLE `student_info` (
      `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
      `studentId` int unsigned NOT NULL DEFAULT '0' COMMENT 'student id',
      `address` json DEFAULT NULL COMMENT 'address',
      `note` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'note',
      `sex` tinyint unsigned NOT NULL DEFAULT '0' COMMENT 'sex:1=male 2=female 0=unknown',
      PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
我们可以对 address 和 sex 字段做转换处理来满足业务开发需求，这里我们用到了 php8 的枚举特性。

## 定义为模型为：
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

SexEnum.php 使用枚举特性。
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
## 转换字段使用示例：

```php
<?php
// 添加记录
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

// 查询一条数据
$studentInfo = StudentInfoModel::findRecord(1);
var_dump($studentInfo->address->city); // "XiaMen"
var_dump($studentInfo->address->province); // "FuJian"
var_dump($studentInfo->sex); // 枚举类型 enum(EasySwoole\FastDb\Tests\Model\SexEnum::MALE)
var_dump($studentInfo->toArray(false));

// 查询多条数据
$studentInfo = new StudentInfoModel();
$studentInfos = $studentInfo->all();
foreach ($studentInfos as $studentInfo) {
    var_dump($studentInfo->address->city);
    var_dump($studentInfo->address->province);
    var_dump($studentInfo->sex);
    var_dump($studentInfo->toArray(false));
}
```
## 自定义返回结果类型

findAll() 方法的 returnAsArray 参数可以设置查询的返回对象的名称（默认是模型对象）。
```php
<?php
$returnAsArray = true;
(new User())->findAll(null, null, $returnAsArray);
all() 方法调用 queryLimit() 方法的 fields() 方法的 returnAsArray 参数可以设置查询的返回对象的名称（默认是模型对象）。

<?php
$returnAsArray = true;
(new User())->queryLimit()->fields(null, $returnAsArray);
```
## 数据分批处理 chunk
模型也支持对返回的数据分批处理。特别是如果你需要处理成千上百条数据库记录，可以考虑使用 chunk 方法，该方法一次获取结果集的一小块，然后填充每一小块数据到要处理的闭包，该方法在编写处理大量数据库记录的时候非常有用。

比如，我们可以全部用户表数据进行分批处理，每次处理 20 个用户记录：
```php
<?php
(new User())->chunk(function (User $user) {
    // 处理 user 模型对象
    $user->updateWithLimit(['status' => 1]);
}, 20);
```

## 分页查询 page
方法说明：

    \EasySwoole\FastDb\Beans\Query::page 方法

    function page(?int $page,bool $withTotalCount = false,int $pageSize = 10): Query

### 使用示例：
// 使用条件的分页查询 不进行汇总 withTotalCount=false
// 查询 第1页 每页10条 page=1 pageSize=10
```php
    $user = new User();
    $user->queryLimit()->page(1, false, 10);
    $resultObject = $user->all();
    foreach ($resultObject as $oneUser) {
        var_dump($oneUser->name);
    }
    
    // 使用条件的分页查询 进行汇总 withTotalCount=true
    // 查询 第1页 每页10条 page=1 pageSize=10
    $user = new User();
    $user->queryLimit()->page(1, true, 10)->where('id', 3, '>');
    $resultObject = $user->all();
    $total = $resultObject->totalCount(); // 汇总数量
    foreach ($resultObject as $oneUser) {
        var_dump($oneUser->name);
    }
    var_dump($total);
```
