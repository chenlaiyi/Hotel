<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-09 11:27:23
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 14:23:38
 */
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * 酒店周边
 * This is the model class for table "{{%diandi_place_rim}}".
 *
 * @public int         $id
 * @public int|null    $bloc_id     公司ID
 * @public int|null    $store_id    商户ID
 * @public int|null    $hotel_id    酒店ID
 * @public int|null    $rim_type    周边类型
 * @public int|null    $is_hot      是否热门
 * @public string|null $title       周边名称
 * @public string|null $thumb       周边图片
 * @public string|null $thumbs      周边相册
 * @public string|null $desc        周边简介
 * @public string|null $content     周边详情
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 */
class PlaceRim extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_rim}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['level_star','lat','lng'], 'number'],
            [['location_p', 'location_c', 'location_a'], 'integer'],
            [['bloc_id', 'store_id', 'hotel_id', 'rim_type', 'is_hot', 'room_num'], 'integer'],
            [['content'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['thumb', 'thumbs', 'desc'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'store_id' => Yii::t('place', '商户ID'),
            'hotel_id' => Yii::t('place', '酒店ID'),
            'rim_type' => Yii::t('place', '周边类型'),
            'is_hot' => Yii::t('place', '是否热门'),
            'title' => Yii::t('place', '周边名称'),
            'thumb' => Yii::t('place', '周边图片'),
            'thumbs' => Yii::t('place', '周边相册'),
            'desc' => Yii::t('place', '周边简介'),
            'content' => Yii::t('place', '周边详情'),
            'create_time' => Yii::t('place', '创建时间'),
            'update_time' => Yii::t('place', '更新时间'),
        ];
    }
}
