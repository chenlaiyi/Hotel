<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:30:16
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-11 10:57:35
 */
namespace addons\diandi_place\models\place;
use addons\diandi_place\Traits\PlaceTrait;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_server}}".
 *
 * @property mixed|null $bloc_id
 * @property mixed|null $id
 * @property mixed|null $store_id
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $hotel_id 酒店ID
 * @public string|null $title 服务名称
 * @public string|null $thumb 图标
 * @public string|null $desc 服务说明
 * @public int|null $displayorder 排序
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 */
class PlaceServer extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_server}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'room_id', 'hotel_id', 'displayorder'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['thumb', 'desc'], 'string', 'max' => 250],
            [['bloc_id','title'], 'unique', 'targetAttribute' => ['store_id','title'], 'message' => '服务名称重复']
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
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bloc_id' => '公司ID',
            'store_id' => '商户ID',
            'hotel_id' => '酒店ID',
            'title' => '服务名称',
            'thumb' => '图标',
            'desc' => '服务说明',
            'displayorder' => '排序',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
