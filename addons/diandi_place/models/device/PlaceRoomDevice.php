<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-30 09:39:13
 */
namespace addons\diandi_place\models\device;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_room_device}}".
 *
 * @property false|mixed|string|null $device_status
 * @property mixed|null $title
 * @property mixed|null $type_id
 * @property mixed|null $hotel_id
 * @property mixed|null $room_id
 * @property mixed|null $tier_id
 * @property mixed|null $project_id
 * @property mixed|null $mac
 * @property mixed|null $device_id
 * @property mixed|null $device_type
 * @property mixed|null $manufactor_id
 * @property mixed|null $displayorder
 * @property mixed|null $status
 * @public int $id
 * @public int|null $bloc_id
 * @public mixed $device_status;
 * @public int|null $store_id
 * @public int|null $type_id 房源类型
 * @public int|null $hotel_id 楼栋ID
 * @public int|null $tier_id 楼层
 * @public int|null $room_id 房间ID
 * @public string|null $title 名称
 * @public int|null $project_id 项目ID
 * @public string|null $mac mac标识
 * @public string|null $device_id 设备编号
 * @public int|null $device_type 设备类型
 * @public int|null $manufactor_id 厂家
 * @public int|null $displayorder 排序
 * @public string|null $create_time
 * @public string|null $update_time
 * @public int|null $status 状态 1已绑定 2未绑定
 */
class PlaceRoomDevice extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_room_device}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id','is_default', 'store_id', 'type_id', 'hotel_id', 'tier_id', 'room_id', 'project_id', 'device_type', 'manufactor_id', 'displayorder', 'status','device_id','hotel_type','unit_id'], 'integer'],
            [['create_time', 'update_time', 'device_status'], 'safe'],
            [['title', 'mac'], 'string', 'max' => 50]
        ];
    }
    /**
     * 行为.
     */
    public function behaviors(): array
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
    public function attributeLabels(): array
    {
        return [
            'id'            => 'ID',
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
            'create_time'   => 'Create Time',
            'update_time'   => 'Update Time',
        ];
    }
}
