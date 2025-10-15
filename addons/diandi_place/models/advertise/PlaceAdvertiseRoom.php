<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 13:00:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 12:35:12
 */
namespace addons\diandi_place\models\advertise;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\room\PlaceRoom;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_advertise_room}}".
 *
 * @public int $id
 * @public int|null $store_id 商户id
 * @public int|null $bloc_id 公司id
 * @public int|null $hotel_id 酒店ID
 * @public int|null $room_id 房间ID
 * @public int|null $location_id 广告位id
 * @public string|null $mark 英文标记
 * @public int|null $is_show 是否显示
 * @public int|null $displayorder 排序
 */
class PlaceAdvertiseRoom extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_advertise_room}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['store_id', 'bloc_id', 'hotel_id', 'room_id', 'location_id', 'is_show', 'displayorder'], 'integer'],
            [['mark'], 'string', 'max' => 255],
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
                'time_type' => 'datetime'
            ],
        ];
    }
    public function getRoom()
    {
        return $this->hasOne(PlaceRoom::class, ['id' => 'room_id']);
    }
    public function getHotel()
    {
        return $this->hasOne(PlaceList::class, ['store_id' => 'store_id']);
    }
    public function getAdvertise()
    {
        return $this->hasOne(PlaceAdvertise::class, ['id' => 'location_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '商户id',
            'bloc_id' => '公司id',
            'hotel_id' => '酒店ID',
            'room_id' => '房间ID',
            'location_id' => '广告位id',
            'mark' => '英文标记',
            'is_show' => '是否显示',
            'displayorder' => '排序',
        ];
    }
}
