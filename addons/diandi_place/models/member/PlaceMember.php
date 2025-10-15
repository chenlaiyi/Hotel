<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-05 11:12:11
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-18 15:18:30
 */
namespace addons\diandi_place\models\member;
use api\models\DdMember;
use common\components\ActiveRecord\YiiActiveRecord;
use common\modules\officialaccount\models\DdWechatFans;
use common\modules\wechat\models\DdWxappFans;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_member}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $member_id 会员ID
 * @public string|null $realname 真实姓名
 * @public string|null $mobile 手机号
 * @public int|null $status 用户状态
 * @public string|null $icard_code 身份证号码
 * @public string|null $icard_front 身份证正面
 * @public string|null $icard_back 身份证反面
 * @public int|null $is_vip 是否是会员
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 * @public string|null $icard_auth_status 认证状态 0认证中 1认证成功 2认证失败
 *
 * @property-read \yii\db\ActiveQuery $wxappFans
 * @property-read \yii\db\ActiveQuery $member
 * @property-read \yii\db\ActiveQuery $wechatFans
 */
class PlaceMember extends YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_member}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'member_id', 'status', 'is_vip','icard_auth_status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['realname', 'mobile', 'icard_code', 'avatar'], 'string', 'max' => 255],
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
                'class'            => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type'        => 'datetime'
            ],
        ];
    }
    public function getMember(): \yii\db\ActiveQuery
    {
        return $this->hasOne(DdMember::class, ['member_id' => 'member_id']);
    }
    public function getWxappFans(): \yii\db\ActiveQuery
    {
        return $this->hasOne(DdWxappFans::class, ['user_id' => 'member_id']);
    }
    public function getWechatFans(): \yii\db\ActiveQuery
    {
        return $this->hasOne(DdWechatFans::class, ['member_id' => 'member_id']);
    }
    public static function findByMemberId(int $member_id): PlaceMember
    {
        return static::findOne(['member_id' => $member_id]);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => Yii::t('place', 'ID'),
            'bloc_id'     => Yii::t('place', '公司ID'),
            'store_id'    => Yii::t('place', '商户ID'),
            'member_id'   => Yii::t('place', '会员ID'),
            'realname'    => Yii::t('place', '真实姓名'),
            'mobile'      => Yii::t('place', '手机号'),
            'status'      => Yii::t('place', '用户状态'),
            'icard_code'  => Yii::t('place', '身份证号码'),
            'icard_front' => Yii::t('place', '身份证正面'),
            'icard_back'  => Yii::t('place', '身份证反面'),
            'is_vip'      => Yii::t('place', '是否是会员'),
            'create_time' => Yii::t('place', '创建时间'),
            'update_time' => Yii::t('place', '更新时间'),
        ];
    }
}
