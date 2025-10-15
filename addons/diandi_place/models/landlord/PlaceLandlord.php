<?php
namespace addons\diandi_place\models\landlord;
use addons\diandi_place\models\place\PlaceList;
use common\models\DdUser;
use common\traits\ActiveQuery\StoreTrait;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "{{%diandi_place_landlord}}".
 *
 * @property int $id
 * @property int|null $bloc_id 公司ID
 * @property int|null $store_id 商户ID
 * @property int|null $member_id 会员ID
 * @property int|null $user_id
 * @property string|null $realname 真实姓名
 * @property int|null $language 房东默认语言
 * @property string|null $desc 房东简介
 * @property string|null $content 房东描述
 * @property string|null $mobile 手机号
 * @property int|null $status 用户状态
 * @property string|null $icard_code 身份证号码
 * @property string|null $icard_front 身份证正面
 * @property string|null $icard_back 身份证反面
 * @property string|null $contract 长租协议
 */
class PlaceLandlord extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_landlord}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'member_id', 'user_id', 'language', 'status','icard_auth_status','place_list_id'], 'integer'],
            [['content', 'contract','avatar'], 'string'],
            [['realname', 'desc', 'mobile', 'icard_code'], 'string', 'max' => 255],
            [['icard_front', 'icard_back'], 'string', 'max' => 200],
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
    function getUser(): ActiveQuery
    {
        return $this->hasOne(DdUser::class, ['id' => 'user_id']);
    }
    /**
     * 关联楼栋
     */
    function getBuilding(): ActiveQuery
    {
        return $this->hasOne(PlaceList::class, ['id' => 'place_list_id']);
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
            'member_id' => '会员ID',
            'user_id' => 'User ID',
            'realname' => '真实姓名',
            'language' => '房东默认语言',
            'desc' => '房东简介',
            'content' => '房东描述',
            'mobile' => '手机号',
            'status' => '用户状态',
            'icard_code' => '身份证号码',
            'icard_front' => '身份证正面',
            'icard_back' => '身份证反面',
            'contract' => '长租协议',
        ];
    }
}