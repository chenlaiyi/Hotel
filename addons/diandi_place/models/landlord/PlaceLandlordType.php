<?php
namespace addons\diandi_place\models\landlord;
use common\traits\ActiveQuery\StoreTrait;
/**
 * This is the model class for table "{{%diandi_place_landlord_type}}".
 *
 * @property int $id
 * @property int|null $bloc_id 公司ID
 * @property int|null $store_id 商户ID
 * @property int|null $member_id 会员ID
 * @property int|null $type_id 业务类型
 * @property int|null $type_status 是否开启
 * @property int|null $user_id 管理员ID
 * @property string|null $create_time
 * @property string|null $update_time
 */
class PlaceLandlordType extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_landlord_type}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'type_id', 'type_status', 'user_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
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
            'bloc_id' => '公司ID',
            'store_id' => '商户ID',
            'type_id' => '业务类型',
            'type_status' => '是否开启',
            'user_id' => '管理员ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}