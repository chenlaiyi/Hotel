<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-08 13:49:52
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-08 18:04:32
 */

namespace addons\diandi_place\models\place;

use addons\diandi_equipment\models\device\EquipmentDevice;
use addons\diandi_place\models\device\PlaceRoomDevice;
use addons\diandi_place\models\room\PlaceRoom;
use addons\diandi_place\Traits\PlaceTrait;
use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%diandi_place_tier}}".
 *
 * @public int $id
 * @public string|null $title 楼层编号
 * @public string|null $prefix 编号前缀
 * @public int|null $bloc_id
 * @public int|null $store_id
 * @public int|null $hotel_id 楼栋ID
 * @public string|null $create_time
 * @public string|null $update_time
 */
class PlaceTier extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait, PlaceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_set_tier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'hotel_id'], 'integer'],
            [['create_time', 'update_time', 'type_id'], 'safe'],
            [['title', 'prefix'], 'string', 'max' => 50],
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

    /**
     * 楼层
     * @return ActiveQuery
     * @date 2023-05-08
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getRoom(): ActiveQuery
    {
        return $this->hasMany(PlaceRoom::class, ['tier_id' => 'id', 'hotel_id' => 'hotel_id']);
    }

    /**
     * 单位
     * @return ActiveQuery
     */
    public function getUnit(): ActiveQuery
    {
        return $this->hasMany(PlaceUnit::class, ['tier_id' => 'id', 'hotel_id' => 'hotel_id']);
    }

    function getDevices()
    {
        return $this->hasMany(EquipmentDevice::class, ['tier_id' => 'id', 'hotel_id' => 'hotel_id'])->with(['goods']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '楼层编号',
            'prefix' => '编号前缀',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'hotel_id' => '楼栋ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
