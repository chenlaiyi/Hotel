<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-09 11:26:55
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:08:00
 */
namespace addons\diandi_place\models\room;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_room_persions}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $room_id 房间ID
 * @public int|null $type 人员类型0成人1儿童3客人
 * @public int|null $num 容纳人数
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 */
class PlaceRoomPersions extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_room_persions}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'room_id', 'type', 'num'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['id'], 'unique'],
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
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'store_id' => Yii::t('place', '商户ID'),
            'room_id' => Yii::t('place', '房间ID'),
            'type' => Yii::t('place', '人员类型0成人1儿童3客人'),
            'num' => Yii::t('place', '容纳人数'),
            'create_time' => Yii::t('place', '创建时间'),
            'update_time' => Yii::t('place', '更新时间'),
        ];
    }
}
