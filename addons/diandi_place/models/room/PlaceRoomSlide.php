<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-05 11:15:32
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-02 14:39:21
 */
namespace addons\diandi_place\models\room;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * 酒店相册
 * This is the model class for table "{{%diandi_place_slide}}".
 *
 * @public int $id ID
 * @public int $bloc_id 公司ID
 * @public int $store_id
 * @public string|null $create_time
 * @public string|null $update_time
 * @public string|null $slide 轮播图
 * @public int|null $type 幻灯片类型: 1.商店头部幻灯片  2.商店中间幻灯片
 */
class PlaceRoomSlide extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_room_slide}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title','slide'],'required'],
            [['bloc_id', 'store_id', 'room_id','type'], 'integer'],
            [['create_time', 'update_time', 'title'], 'safe'],
            [['slide'], 'safe'],
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
            'store_id' => Yii::t('place', 'Store ID'),
            'create_time' => Yii::t('place', 'Create Time'),
            'update_time' => Yii::t('place', 'Update Time'),
            'slide' => Yii::t('place', '轮播图'),
            'type' => Yii::t('place', '幻灯片类型: 1.商店头部幻灯片  2.商店中间幻灯片'),
        ];
    }
}
