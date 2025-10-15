<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-19 13:52:27
 */
namespace addons\diandi_place\models\place;
use addons\diandi_place\models\member\PlaceMember;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * 酒店评价
 * This is the model class for table "{{%diandi_place_comment}}".
 *
 * @public int         $id          id
 * @public int         $bloc_id     公司ID
 * @public int         $store_id    商户ID
 * @public int|null    $hotel_id    酒店ID
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 * @public int|null    $member_id   会员id
 * @public int|null    $order_id    订单id
 * @public int|null    $room_id     包间id
 * @public string|null $labels      评价标签
 * @public string|null $comment     评论内容
 * @public int|null    $star_num    综合星级
 */
class PlaceComment extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_comment}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'hotel_id', 'member_id', 'order_id', 'room_id', 'star_num','hotel_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['labels'], 'string', 'max' => 100],
            [['comment', 'thumbs'], 'string', 'max' => 255],
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
    public function getMember()
    {
        return $this->hasOne(PlaceMember::class, ['member_id' => 'member_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('place', 'id'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'store_id' => Yii::t('place', '商户ID'),
            'hotel_id' => Yii::t('place', '酒店ID'),
            'create_time' => Yii::t('place', '创建时间'),
            'update_time' => Yii::t('place', '更新时间'),
            'member_id' => Yii::t('place', '会员id'),
            'order_id' => Yii::t('place', '订单id'),
            'room_id' => Yii::t('place', '包间id'),
            'labels' => Yii::t('place', '评价标签'),
            'comment' => Yii::t('place', '评论内容'),
            'star_num' => Yii::t('place', '综合星级'),
        ];
    }
}
