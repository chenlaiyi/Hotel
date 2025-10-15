<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-26 11:21:15
 */
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
class PlaceDevice extends \common\components\ActiveRecord\YiiActiveRecord
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
            [['bloc_id', 'store_id', 'type_id', 'hotel_id', 'tier_id', 'room_id', 'project_id', 'device_type', 'manufactor_id', 'displayorder', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title', 'mac'], 'string', 'max' => 50],
            [['device_id'], 'string', 'max' => 255],
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
                'class'            => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type'        => 'datetime',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'bloc_id'       => 'Bloc ID',
            'store_id'      => 'Store ID',
            'type_id'       => '房源类型',
            'hotel_id'      => '楼栋ID',
            'tier_id'       => '楼层',
            'room_id'       => '房间ID',
            'title'         => '名称',
            'project_id'    => '项目ID',
            'mac'           => 'mac标识',
            'device_id'     => '设备编号',
            'device_type'   => '设备类型',
            'manufactor_id' => '厂家',
            'displayorder'  => '排序',
            'create_time'   => 'Create Time',
            'update_time'   => 'Update Time',
            'status'        => '状态 1已绑定 2未绑定',
        ];
    }
}
