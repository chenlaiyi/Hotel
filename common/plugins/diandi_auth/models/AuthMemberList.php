<?php


namespace common\plugins\diandi_auth\models;


use common\traits\ActiveQuery\StoreTrait;

use Yii;


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
class AuthMemberList extends \common\components\ActiveRecord\YiiActiveRecord
{

    use StoreTrait;


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
            [['bloc_id', 'store_id', 'status', 'isLogin'], 'integer'],
            [['user_money','frozen_money','accumulate_money'],'number'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'password', 'spec'], 'string', 'max' => 50],
            [['mobile'], 'string', 'max' => 20],
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