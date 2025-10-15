<?php


namespace common\plugins\diandi_auth\models;


use common\traits\ActiveQuery\StoreTrait;


/**
 * This is the model class for table "{{%diandi_auth_role_permission}}".
 *
 * @property int $id
 * @property int|null $bloc_id 业务中心
 * @property int|null $store_id 门店
 * @property int|null $roleId 角色
 * @property string|null $tag 权限标识
 * @property int|null $permissionId 权限Id
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 */
class AuthMemberRolePermission extends \common\components\ActiveRecord\YiiActiveRecord
{

    use StoreTrait;


    /**
     * {@inheritdoc}
     */

    public static function tableName(): string

    {

        return '{{%diandi_auth_role_permission}}';

    }


    /**
     * {@inheritdoc}
     */

    public function rules(): array

    {

        return [
            [['bloc_id', 'store_id', 'roleId', 'permissionId'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['tag'], 'string', 'max' => 50],
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
            'bloc_id' => '业务中心',
            'store_id' => '门店',
            'roleId' => '角色',
            'tag' => '权限标识',
            'permissionId' => '权限Id',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];

    }

}