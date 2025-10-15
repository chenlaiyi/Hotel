<?php
namespace addons\diandi_place\models\device;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
* This is the model class for table "{{%diandi_place_device}}".
*
    * @public int $id
    * @public string|null $title 名称
    * @public string|null $mac mac标识
    * @public int|null $cate_id 子集ID
    * @public int|null $cate_pid 父级分类ID
    * @public int|null $project_id 项目ID
    * @public int|null $displayorder 排序
    * @public int|null $type_id 房源类型
    * @public int|null $hotel_id 楼栋ID
    * @public int|null $tier_id 楼层
    * @public int|null $room_id 房间ID
    * @public string|null $create_time
    * @public string|null $update_time
*/
class PlaceUnit extends \common\components\ActiveRecord\YiiActiveRecord
{
use StoreTrait;
/**
* {@inheritdoc}
*/
public static function tableName()
{
return '{{%diandi_place_device}}';
}
/**
* {@inheritdoc}
*/
public function rules(): array
{
return [
            [['cate_id', 'cate_pid', 'project_id', 'displayorder', 'type_id', 'hotel_id', 'tier_id', 'room_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title', 'mac'], 'string', 'max' => 50],
        ];
}
/**
* 行为.
*/
public function behaviors()
{
/*自动添加创建和修改时间*/
return [
[
'class' => \common\behaviors\SaveBehavior::class,
'updatedAttribute' => 'update_time',
'createdAttribute' => 'create_time',
'time_type' => 'datetime',
],
];
}
/**
* {@inheritdoc}
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'title' => '名称',
    'mac' => 'mac标识',
    'cate_id' => '子集ID',
    'cate_pid' => '父级分类ID',
    'project_id' => '项目ID',
    'displayorder' => '排序',
    'type_id' => '房源类型',
    'hotel_id' => '楼栋ID',
    'tier_id' => '楼层',
    'room_id' => '房间ID',
    'create_time' => 'Create Time',
    'update_time' => 'Update Time',
];
}
}