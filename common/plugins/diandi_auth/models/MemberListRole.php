<?php


namespace common\plugins\diandi_auth\models;


use common\traits\ActiveQuery\StoreTrait;

use Yii;


/**
 * This is the model class for table "{{%diandi_auth_list_role}}".
 *
 * @property int $id
 * @property int|null $bloc_id 业务中心
 * @property int|null $store_id 门店
 * @property int|null $accountId 账号id
 * @property int|null $roleId 角色
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 */
class MemberListRole extends \common\components\ActiveRecord\YiiActiveRecord
{

    use StoreTrait;


    /**
     * {@inheritdoc}
     */

    public static function tableName(): string

    {

        return '{{%diandi_auth_list_role}}';

    }


    /**
     * {@inheritdoc}
     */

    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'accountId', 'roleId','user_id'], 'integer'],
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

    function getRole()
    {
        return $this->hasOne(MemberRole::class, ['id' => 'roleId']);
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
            'accountId' => '账号id',
            'roleId' => '角色',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];

    }

}