<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-08 14:55:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-19 19:41:22
 */
namespace addons\diandi_place\models\place;
use addons\diandi_place\models\room\PlaceRoom;
use addons\diandi_place\Traits\PlaceTrait;
use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "{{%diandi_place_set_unit}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public string|null $title 单元编号
 * @public int $type_id 房源类型（酒店，公寓，名宿，茶室）
 * @public int|null $lease_type 承租类型
 * @public int|null $time_type 租期
 * @public int|null $time_length 租约类型
 * @public int|null $hotel_id 楼栋ID
 * @public int|null $tier_id 楼层ID
 * @public string|null $create_time
 * @public string|null $update_time
 */
class PlaceUnit extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait,PlaceTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_set_unit}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'type_id', 'lease_type', 'time_type', 'time_length', 'hotel_id', 'tier_id','room_num','toilet_num','status'], 'integer'],
            [['area'],'number'],
            [['type_id'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
     * 房间.
     *
     * @return ActiveQuery
     * @date 2023-05-08
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getRoom(): ActiveQuery
    {
        return $this->hasMany(PlaceRoom::class, ['hotel_id' => 'hotel_id','unit_id'=>'id'])->with(['server']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bloc_id' => '公司ID',
            'store_id' => '商户ID',
            'title' => '单元编号',
            'type_id' => '房源类型（酒店，公寓，名宿，茶室）',
            'lease_type' => '承租类型',
            'time_type' => '租期',
            'time_length' => '租约类型',
            'hotel_id' => '楼栋ID',
            'tier_id' => '楼层ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
