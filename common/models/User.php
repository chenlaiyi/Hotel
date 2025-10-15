<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-04-13 16:29:07
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:12:08
 */

namespace common\models;

use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use common\models\enums\UserStatus;
use diandi\addons\models\BlocStore;
use diandi\admin\models\User as ModelsUser;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @public integer $id
 * @public string $username
 * @public string $password_hash
 * @public string $password_reset_token
 * @public string $verification_token
 * @public string $email
 * @public string $auth_key
 * @public integer $status
 * @public integer $created_at
 * @public integer $updated_at
 * @public string $password write-only password
 */
class User extends ModelsUser
{
    const STATUS_DELETED  = UserStatus::DELETE;
    const STATUS_INACTIVE = UserStatus::AUDIT;
    const STATUS_ACTIVE   = UserStatus::APPROVE;

    public function rules()
    {
        return [
            ['status', 'in', 'range' => [UserStatus::AUDIT, UserStatus::APPROVE, UserStatus::DENY, UserStatus::BLOCK, UserStatus::EXPIRE, UserStatus::DELETE]],
            [
                [
                    'username',
                    'email',
                    'verification_token',
                    'avatar',
                    'mobile',
                    'company',
                    'delete_time',
                ],
                'string',
            ],
            [
                [
                    'store_id',
                    'bloc_id',
                    'is_super_admin',
                    'department_id',
                    'is_business_admin',
                    'parent_bloc_id',
                    'created_at',
                    'updated_at',
                ], 'number',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function findByMobile($mobile): array | \yii\db\ActiveRecord  | null
    {
        return static::find()->where(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'       => '用户ID',
            'bloc_id'  => '所属公司',
            'store_id' => '所属商户',
            'username' => '用户名',
            'email'    => '邮箱',
        ];
    }

    public function delete()
    {
        $this->delete_time = date('Y-m-d H:i:s');
        return $this->save(false);
    }

    public function getStore()
    {
        return $this->hasOne(BlocStore::className(), ['store_id' => 'store_id']);
    }
}
