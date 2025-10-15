<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-05 11:13:43
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:08:00
 */
namespace addons\diandi_place\models\room;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_price}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $hotel_id 酒店ID
 * @public int|null $roomid 房间ID
 * @public int|null $roomdate 房价日期
 * @public string $thisdate 当天日期
 * @public float|null $price 房价
 * @public int|null $status 房间状态
 */
class PlaceRoomPrice extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_price}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'hotel_id', 'roomid', 'roomdate', 'status'], 'integer'],
            [['price'], 'number'],
            [['thisdate'], 'string', 'max' => 255],
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
            'hotel_id' => Yii::t('place', '酒店ID'),
            'roomid' => Yii::t('place', '房间ID'),
            'roomdate' => Yii::t('place', '房价日期'),
            'thisdate' => Yii::t('place', '当天日期'),
            'price' => Yii::t('place', '房价'),
            'status' => Yii::t('place', '房间状态'),
        ];
    }
}
