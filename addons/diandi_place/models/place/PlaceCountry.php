<?php
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_country}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string|null $name 国家名称
 * @property string|null $icon 国旗
 * @property string|null $create_time
 * @property string|null $update_time
 * @property int|null $is_hot 是否热门
 * @property int|null $language 默认语言
 */
class PlaceCountry extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_country}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'is_hot', 'language','prefix_num'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['initial'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'name' => '国家名称',
            'icon' => '国旗',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_hot' => '是否热门',
            'language' => '默认语言',
        ];
    }
}