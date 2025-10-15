<?php
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
/**
 * This is the model class for table "{{%diandi_place_type_list}}".
 *
 * @property int $id
 * @property int|null $place_type_id 业务类型
 * @property string|null $title 房型名称
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string|null $create_time
 * @property string|null $update_time
 */
class PlaceTypeList extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_type_list}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['place_type_id', 'bloc_id', 'store_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 50],
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
                'class' => \common\behaviors\SaveBehavior::className(),
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
            'place_type_id' => '业务类型',
            'title' => '房型名称',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}