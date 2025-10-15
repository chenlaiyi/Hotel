<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:30:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:08:00
 */
namespace addons\diandi_place\models\room;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_room_server}}".
 *
 * @property mixed|null $title
 * @property int|mixed|null $room_type_id
 * @property int|mixed|null $room_id
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $hotel_id 酒店ID
 * @public int|null $room_id 房间ID
 * @public int|null $room_type_id 房型id
 * @public string|null $title 服务名
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 */
class PlaceRoomServer extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_room_server}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'hotel_id', 'room_id', 'room_type_id','server_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 11],
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
                'time_type'        => 'datetime'
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'bloc_id'     => '公司ID',
            'store_id'    => '商户ID',
            'hotel_id'    => '酒店ID',
            'room_id'     => '房间ID',
            'title'       => '服务名称',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
