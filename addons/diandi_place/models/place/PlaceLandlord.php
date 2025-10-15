<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-05 08:51:03
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-26 15:18:10
 */
namespace addons\diandi_place\models\place;
use addons\diandi_place\models\member\PlaceMember;
use api\models\DdMember;
use common\behaviors\SaveBehavior;
use common\components\ActiveRecord\YiiActiveRecord;
use common\models\User;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "{{%diandi_place_landlord}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $member_id 会员ID
 * @public string|null $realname 真实姓名
 * @public int|null $language 房东默认语言
 * @public string|null $desc 房东简介
 * @public string|null $content 房东描述
 * @public string|null $mobile 手机号
 * @public int|null $status 用户状态
 * @public string|null $icard_code 身份证号码
 * @public string|null $icard_front 身份证正面
 * @public string|null $icard_back 身份证反面
 *
 * @property-read mixed $member
 * @property-read mixed $user
 */
class PlaceLandlord extends YiiActiveRecord
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
            [['bloc_id', 'store_id', 'member_id', 'language', 'status','user_id','is_admin','customer_id'], 'integer'],
            [['content', 'contract'], 'string'],
            [['realname', 'desc', 'mobile', 'icard_code'], 'string', 'max' => 255],
            [['icard_front', 'icard_back'], 'string', 'max' => 200],
        ];
    }
    public function getMember(): ActiveQuery
    {
        return $this->hasOne(PlaceMember::class, ['member_id' => 'member_id']);
    }
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bloc_id' => '公司ID',
            'store_id' => '商户ID',
            'member_id' => '会员ID',
            'realname' => '真实姓名',
            'language' => '房东默认语言',
            'desc' => '房东简介',
            'content' => '房东描述',
            'mobile' => '手机号',
            'status' => '用户状态',
            'icard_code' => '身份证号码',
            'icard_front' => '身份证正面',
            'icard_back' => '身份证反面',
        ];
    }
}
