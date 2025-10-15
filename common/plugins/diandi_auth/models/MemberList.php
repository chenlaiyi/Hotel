<?php


namespace common\plugins\diandi_auth\models;


use common\plugins\diandi_auth\services\MemberService;
use addons\zyj_wash\models\store\ZyjWashStore;
use common\models\User;
use api\models\DdMember;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\traits\ActiveQuery\StoreTrait;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveQuery;


/**
 * This is the model class for table "{{%diandi_auth_list}}".
 *
 * @property int $id
 * @property int|null $bloc_id 业务中心
 * @property int|null $store_id 门店
 * @property string|null $name 账号名称
 * @property string|null $password 密码
 * @property string|null $mobile 手机号
 * @property int|null $status 账号状态
 * @property int|null $isLogin 分销状态
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 */
class MemberList extends \common\components\ActiveRecord\YiiActiveRecord
{

    use StoreTrait;

    public $role = [];


    /**
     * {@inheritdoc}
     */

    public static function tableName(): string
    {

        return '{{%diandi_auth_list}}';

    }


    /**
     * {@inheritdoc}
     */

    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'status', 'isLogin', 'member_id','user_id','member_boss','member_product','member_cloud','ribbon_type','check_type','is_dz', 'spec'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name','username'], 'string', 'max' => 50],
            [['password','openid','unionId','avatarUrl'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 20],
            [['username'],'unique'],
            [['mobile'],'unique']
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
            ]
        ];
    }

    /**
     * 关联门店
     * @return \yii\db\ActiveQuery
     */
    function getStore(): ActiveQuery
    {
        return $this->hasMany(ZyjMemberLinkStore::class, ['member_id' => 'member_id']);
    }

    /**
     * 获取关联云店
     * @return ActiveQuery
     */
    public function getCloud(): \yii\db\ActiveQuery
    {
        return $this->hasOne(ZyjWashStore::class, ['store_id' => 'member_cloud']);
    }

    public function afterFind(): void
    {
        $spec = json_decode($this->spec, true);
        if (is_array($spec)){
            array_walk($spec, function (&$item) {
                $item = (int) $item;
            });
        }
        $this->spec = $spec;
        $this->role = MemberService::getMemberRole($this->id);
        parent::afterFind();
    }


    function getRole()
    {
        return $this->hasOne(MemberListRole::class, ['accountId' => 'id']);
    }

    public function fields(): array
    {
        $fields = parent::fields();
//        unset($fields['password']);
        $fields[] = 'role';
        return $fields;
    }

    /**
     * {@inheritdoc}
     */

    public function attributeLabels(): array
    {

        return [
            'id' => 'ID',
            'bloc_id' => '业务中心',
            'store_id' => '门店',
            'name' => '账号名称',
            'password' => '密码',
            'mobile' => '手机号',
            'status' => '账号状态',
            'isLogin' => '分销状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];

    }

}