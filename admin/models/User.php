<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-07-29 01:59:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 14:10:58
 */

namespace admin\models;

use admin\models\addons\models\Bloc;
use admin\modules\customer\models\CustomerUser;
use admin\services\UserService;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use common\helpers\ErrorsHelper;
use common\helpers\FileHelper;
use common\helpers\ResultHelper;
use common\models\enums\UserStatus;
use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\models\BlocStore;
use diandi\admin\models\AuthAssignmentGroup;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\StaleObjectException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * User model.
 *
 * @property string $password_hash
 * @property string $auth_key
 * @property $username
 * @property int|mixed $status
 * @property int $id
 * @property string $access_token
 * @property mixed $avatar
 * @public int    $id
 * @public int    $store_id
 * @public string $username
 * @public string $password_hash
 * @public string $password_reset_token
 * @public string $verification_token
 * @public string $email
 * @public string $auth_key
 * @public int    $status
 * @public int    $created_at
 * @public int    $updated_at
 * @public string $password             write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    use StoreTrait;

    const STATUS_DELETED  = UserStatus::DELETE;
    const STATUS_INACTIVE = UserStatus::AUDIT;
    const STATUS_ACTIVE   = UserStatus::APPROVE;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'department_id', 'is_super_admin', 'is_business_admin', 'is_sys'], 'integer'],
            ['status', 'default', 'value' => UserStatus::AUDIT],
            ['status', 'in', 'range' => UserStatus::getConstantsByName()],
            [['username', 'email', 'avatar', 'company', 'union_id', 'open_id', 'mobile'], 'safe'],
            ['parent_bloc_id', 'default', 'value' => 0],
            //自定义方法手机号验证
            ['mobile', 'validateMobile'],
        ];
    }

    /**
     * 验证手机号，确保员工和客商手机号不重复
     *
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateMobile($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $mobile = $this->$attribute;
            if (!empty($mobile)) {
                // 新增时的验证逻辑
                if ($this->isNewRecord) {
                    // 检查员工用户表中是否已存在该手机号
                    $userinfo = $this->find()->where(['mobile' => $mobile])
                        ->andWhere(['<>', 'mobile', 0])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被占用');
                    }

                    /**
                     * customer查询手机号是否被占用
                     */
                    $userinfo = CustomerUser::find()->where(['mobile' => $mobile])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被客商账号占用');
                    }
                } else {
                    // 编辑时的验证逻辑，排除当前记录本身
                    $userinfo = $this->find()->where(['mobile' => $mobile])
                        ->andWhere(['<>', 'id', $this->id])
                        ->andWhere(['<>', 'mobile', 0])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被占用');
                    }

                    /**
                     * customer查询手机号是否被占用
                     */
                    $userinfo = CustomerUser::find()->where(['mobile' => $mobile])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被客商账号占用');
                    }
                }
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status->getValue();
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUserGroup(): \yii\db\ActiveQuery
    {
        return $this->hasmany(AuthAssignmentGroup::class, ['user_id' => 'id']);
    }

    /**
     * Signs user up.
     * Source_type: 0主动注册1后台添加.
     *
     * @param $username
     * @param $mobile
     * @param $email
     * @param $password
     * @param int $status
     * @param bool $is_create_bloc
     * @param string $invitation_code
     * @param int $source_type
     * @param string $company
     * @return array|bool|object[]|string[]
     * @throws ErrorException
     * @throws Exception
     * @throws Throwable
     * @throws \yii\db\Exception
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function signup($username, $mobile, $email, $password, int $status = 0, bool $is_create_bloc = false, string $invitation_code = '', int $source_type = 0, string $company = ''): array|bool
    {
        $logPath = Yii::getAlias('@runtime/wechat/login/' . date('ymd') . '.log');

        if (!$this->validate()) {
            FileHelper::writeLog($logPath, '登录日志:会员注册校验失败' . json_encode($this->validate()));

            return $this->validate();
        }
        $bloc_id = Yii::$app->request->input('bloc_id');
        /* 查看手机号是否重复 */
        if ($mobile) {
            $userinfo = $this->find()->where(['mobile' => $mobile])
                ->andWhere(['<>', 'mobile', 0])->exists();
            if (!empty($userinfo)) {
                return ResultHelper::json(401, '手机号已被占用');
            }
        }
        $parent_bloc_id = 0;
        if ($invitation_code) {
            $parent_bloc_id = Bloc::find()->where(['invitation_code' => $invitation_code])->select('bloc_id')->scalar();
        }
        $avatar = Yii::$app->request->input('avatar');

        FileHelper::writeLog($logPath, '登录日志:会员注册校验手机号' . json_encode([
                    'email'           => $email,
                    'mobile'          => $mobile,
                    'username'        => $username,
                    'password'        => $password,
                    'status'          => $status,
                    'invitation_code' => $invitation_code,
                    'source_type'     => (int) Yii::$app->request->input('source_type'),
                    'company'         => $company,
                    'parent_bloc_id'  => $parent_bloc_id,
                    'avatar'          => $avatar,
                    'bloc_id'         => $bloc_id,
                ]
            )
        );
        //
        if (!empty($bloc_id) && empty($company)) {
            $bloc_info = Bloc::findOne($bloc_id);
            $company   = $bloc_info->business_name ?? '';
        }

        $this->department_id  = Yii::$app->request->input('department_id', 0);
        $this->avatar         = $avatar;
        $this->username       = $username;
        $this->email          = $email;
        $this->parent_bloc_id = (int) $parent_bloc_id ?: Yii::$app->request->input('bloc_id', 0);
        $this->company        = $company;
        $this->mobile         = $mobile;

        if ((int) Yii::$app->request->input('source_type') === 1) {
            $this->store_id = (int) Yii::$app->request->input('store_id', 0);
            $this->bloc_id  = (int) Yii::$app->request->input('bloc_id', 0);
            if ($this->bloc_id === 0) {
                $this->is_super_admin = 1;
            }
        }
        $this->status = $status;

        $this->setPassword($password);
        $this->generateAuthKey();
        $this->generateEmailVerificationToken();
        $this->generatePasswordResetToken();
        if ($this->save()) {
            $user_id = $this->id;
            // 只有没有该参数才是正常的注册，否则是后台直接添加的用户

            if ((int) Yii::$app->request->input('source_type') === 1) {
                $userLink = new UserLink();
                $admin_id = Yii::$app->user->identity->id;
                $data     = [
                    'user_id'      => $admin_id,
                    'is_default'   => 0,
                    'type'         => 1,
                    'link_user_id' => $user_id,
                    'status'       => 0,
                ];
                $userLink->setAttributes($data);
                if (!$userLink->save()) {
                    $msg = ErrorsHelper::getModelError($userLink);
                    return ResultHelper::json(500, $msg);
                }

                #清楚创建人 所能看到的员工缓存
                $cacheKey = "auth_user_link_" . 1 . "_" . $admin_id;
                $cache    = Yii::$app->cache;
                $cache->delete($cacheKey);
                #  UserService::deleteUserCache($admin_id);

            }

            UserService::initUserAuth($user_id, $is_create_bloc);
            /* 写入用户apitoken */
            $service            = Yii::$app->service;
            $service->namespace = 'admin';
            return $service->AccessTokenService->getAccessToken($this, 1);
        } else {
            $msg = ErrorsHelper::getModelError($this);
            FileHelper::writeLog($logPath, '登录日志:会员注册失败错误' . json_encode($msg));

            return ResultHelper::json(401, $msg);
        }
    }

    /**
     * 生成accessToken字符串.
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAccessToken(): string
    {
        $this->access_token = Yii::$app->security->generateRandomString();

        return $this->access_token;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): User|IdentityInterface|null
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findUser($mobile, $username = ''): array|ActiveRecord|null
    {
        $user  = null;
        $query = static::find();
        if (!empty($mobile)) {
            $user = $query->where(['mobile' => $mobile])->one();
        }

        if (!empty($username)) {
            $user = $query->where(['username' => $username])->one();
        }

        return $user;
    }

    /**
     * Finds user by username.
     *
     * @param string $username
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function findByUsername(string $username): array|\yii\db\ActiveRecord|null
    {
        return static::find()->where(['username' => $username, 'status' => self::STATUS_ACTIVE])->one();
    }

    public static function findByMobile($mobile): array|\yii\db\ActiveRecord|null
    {
        return static::find()->where(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE])->one();
    }

    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     *
     * @return User|null
     */
    public static function findByPasswordResetToken(string $token): ?static
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status'               => self::STATUS_ACTIVE,
            ]
        );
    }

    /**
     * Finds user by verification email token.
     *
     * @param string $token verify email token
     *
     * @return User
     */
    public static function findByVerificationToken(string $token): static
    {
        return static::findOne([
                'verification_token' => $token,
                'status'             => self::STATUS_INACTIVE,
            ]
        );
    }

    /**
     * Finds out if password reset token is valid.
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     *
     * @return bool if the password provided is valid for the current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     *
     * @throws ErrorException
     */
    public function generateAuthKey(): void
    {
        try {
            $this->auth_key = Yii::$app->security->generateRandomString();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage(), 400);
        }
    }

    /**
     * Generates new password reset token.
     *
     * @throws ErrorException
     */
    public function generatePasswordResetToken(): void
    {
        try {
            $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage(), 400);
        }
    }

    /**
     * Generates new token for email verification.
     *
     * @throws ErrorException
     */
    public function generateEmailVerificationToken(): void
    {
        try {
            $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage(), 400);
        }
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    public function fields(): array
    {
        $fields = parent::fields();
        // 去掉一些包含敏感信息的字段
        unset($fields['auth_key'], $fields['password_hash'], $fields['verification_token']);

        return $fields;
    }

    public function getStore()
    {
        return $this->hasOne(BlocStore::className(), ['store_id' => 'store_id']);
    }
}
