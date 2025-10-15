<?php
namespace addons\diandi_place\models\room;
use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;
class PlaceRoomStatus extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_room_status}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [[
                'bloc_id',
                'store_id',
                'hotel_id',
                'room_id',
                'room_num',
                'room_status'
            ], 'integer'],
            [['create_time', 'update_time', 'roomdate','thisdate'], 'safe']
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
                'class' => SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }
}