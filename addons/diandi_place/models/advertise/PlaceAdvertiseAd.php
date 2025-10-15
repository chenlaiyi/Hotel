<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:10:36
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:04:20
 */
namespace addons\diandi_place\models\advertise;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_advertise_ad}}".
 *
 * @public int $id
 * @public int|null $store_id 商户id
 * @public int|null $bloc_id 公司id
 * @public string|null $thumb 图片
 * @public int|null $location_id 广告位id
 * @public string|null $mark 英文标记
 * @public int|null $is_show 是否显示
 * @public string|null $name
 * @public int|null $room_id
 * @public string|null $url
 */
class PlaceAdvertiseAd extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_advertise_ad}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['store_id', 'bloc_id', 'location_id', 'is_show', 'room_id'], 'integer'],
            [['thumb', 'mark', 'name', 'url'], 'string', 'max' => 255],
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
            'thumb' => '图片',
            'location_id' => '广告位id',
            'mark' => '英文标记',
            'is_show' => '是否显示',
            'name' => 'Name',
            'room_id' => 'Goods ID',
            'url' => 'Url',
        ];
    }
}
