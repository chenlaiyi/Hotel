<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-04-13 16:25:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-04-02 09:32:30
 */

namespace common\models;

use admin\models\addons\models\Bloc;
use common\models\enums\UserStatus;

/**
 * This is the model class for table "dd_user".
 *
 * @public int    $user_id
 * @public string $open_id
 * @public string $nickName
 * @public string $avatarUrl
 * @public int    $gender
 * @public string $country
 * @public string $province
 * @public string $city
 * @public int    $address_id
 * @public int    $wxapp_id
 * @public int    $create_time
 * @public int    $update_time
 */
class DdUser extends \common\components\ActiveRecord\YiiActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bloc_id', 'store_id','department_id','is_super_admin','is_business_admin','is_sys'], 'integer'],
            ['status', 'default', 'value' => UserStatus::AUDIT],
            ['status', 'in', 'range' => UserStatus::getConstantsByName()],
            [['username', 'email', 'avatar', 'company', 'union_id', 'open_id'], 'safe'],
            ['parent_bloc_id', 'default', 'value' => 0],
        ];
    }

    function getBloc(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Bloc::class,['bloc_id'=>'bloc_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'is_login' => '是否登录',
            'last_login_ip' => '最后登录ip',
            'user_id' => 'User ID',
            'open_id' => 'Open ID',
            'nickName' => 'Nick Name',
            'avatarUrl' => 'Avatar Url',
            'country' => 'Country',
            'province' => 'Province',
            'city' => 'City',
            'address_id' => 'Address ID',
            'wxapp_id' => 'Wxapp ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
