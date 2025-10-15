<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:10:53
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:04:34
 */
namespace addons\diandi_place\models\advertise;
use addons\diandi_place\models\place\PlaceRim;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_advertise_rim}}".
 *
 * @public int $id
 * @public int|null $store_id 商户id
 * @public int|null $bloc_id 公司id
 * @public int|null $rim_id 商品id
 * @public int|null $location_id 广告位id
 * @public string|null $mark 英文标记
 * @public int|null $is_show 是否显示
 * @public int|null $displayorder 排序
 */
class PlaceAdvertiseRim extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_advertise_rim}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['lng','lat'],'number'],
            [['store_id', 'bloc_id', 'rim_id', 'location_id', 'is_show', 'displayorder'], 'integer'],
            [['mark'], 'string', 'max' => 255],
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
                'time_type' => 'datetime'
            ],
        ];
    }
    public function getRim(): \yii\db\ActiveQuery
    {
        return $this->hasOne(PlaceRim::class, ['id' => 'rim_id']);
    }
    public function getAdvertise(): \yii\db\ActiveQuery
    {
        return $this->hasOne(PlaceAdvertise::class, ['id' => 'location_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'store_id' => '商户id',
            'bloc_id' => '公司id',
            'rim_id' => '商品id',
            'location_id' => '广告位id',
            'mark' => '英文标记',
            'is_show' => '是否显示',
            'displayorder' => '排序',
        ];
    }
}
