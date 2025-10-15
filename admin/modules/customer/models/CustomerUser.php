<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-21 00:19:09
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 00:52:37
 */

namespace admin\modules\customer\models;

use admin\models\addons\models\Bloc;
use admin\modules\customer\services\AccessTokenService;
use admin\services\UserService;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use common\helpers\ErrorsHelper;
use common\helpers\FileHelper;
use common\helpers\ResultHelper;
use common\models\DdUser;
use common\models\enums\UserStatus;
use common\plugins\diandi_auth\models\AuthMemberRole;
use common\traits\ActiveQuery\StoreTrait;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class CustomerUser extends ActiveRecord implements IdentityInterface
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
        return '{{%customer_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'auth_role_id', 'is_super_admin', 'is_business_admin', 'is_sys'], 'integer'],
            ['status', 'default', 'value' => UserStatus::AUDIT],
            ['status', 'in', 'range' => UserStatus::getConstantsByName()],
            [['username', 'email', 'avatar', 'company', 'union_id', 'open_id', 'customer_id', 'delete_time'], 'safe'],
            ['parent_bloc_id', 'default', 'value' => 0],
            //自定义方法手机号验证
            ['mobile', 'validateMobile'],
            ['username', 'validateUsername'],
        ];
    }

    /**
     * @param $attribute
     * @return void
     */
    public function validateUsername($attribute): void
    {
        if (!$this->hasErrors()) {
            $username = $this->$attribute;
            $bloc_id  = $this->bloc_id;
            if (!empty($username)) {
                if ($this->isNewRecord) {
                    $info = $this->find()->where(['username' => $username, 'bloc_id' => $bloc_id])->exists();
                    if (!empty($info)) {
                        $this->addError($attribute, '名称已存在');
                    }
                } else {
                    // 编辑时的验证逻辑，排除当前记录本身
                    $info = $this->find()->where(['username' => $username, 'bloc_id' => $bloc_id])->andWhere(['<>', 'id', $this->id])->exists();
                    if (!empty($info)) {
                        $this->addError($attribute, '名称已存在');
                    }
                }
            }
        }
    }


    /**
     * 验证手机号，确保客商和员工手机号不重复
     *
     * @param $attribute
     * @param $params
     * @return void
     */
    /**
     * 验证手机号，确保客商和员工手机号不重复
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
                    // 检查客户用户表中是否已存在该手机号
                    $userinfo = $this->find()->where(['mobile' => $mobile])
                        ->andWhere(['<>', 'mobile', 0])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被占用');
                    }

                    // 检查员工用户表中是否已存在该手机号
                    $userinfo = DdUser::find()->where(['mobile' => $mobile])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被员工账号占用');
                    }
                } else {
                    // 编辑时的验证逻辑，排除当前记录本身
                    $userinfo = $this->find()->where(['mobile' => $mobile])
                        ->andWhere(['<>', 'id', $this->id])
                        ->andWhere(['<>', 'mobile', 0])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被占用');
                    }

                    // 检查员工用户表中是否已存在该手机号
                    $userinfo = DdUser::find()->where(['mobile' => $mobile])->exists();
                    if (!empty($userinfo)) {
                        $this->addError($attribute, '手机号已被员工账号占用');
                    }
                }
            }
        }
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
     * @param int $customer_id
     * @param int $disabled
     * @return array|bool|object[]|string[]
     * @throws ErrorException
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     * @throws \yii\db\Exception
     * @throws UnprocessableEntityHttpException
     */
    public function signup($username, $mobile, $email, $password, int $status = 0, bool $is_create_bloc = false, string $invitation_code = '', int $source_type = 0, string $company = '', $customer_id = 0, $disabled = 0): array|bool
    {
        $logPath = Yii::getAlias('@runtime/wechat/login/' . date('ymd') . '.log');

        if (!$this->validate()) {
            FileHelper::writeLog($logPath, '登录日志:会员注册校验失败' . json_encode($this->validate()));

            return $this->validate();
        }

        /* 查看手机号是否重复 */
        if ($mobile) {
            $userinfo = DdUser::find()->where(['mobile' => $mobile])
                ->andWhere(['<>', 'mobile', 0])->select('id')->one();
            if (!empty($userinfo)) {
                return ResultHelper::json(401, '手机号已被占用');
            }

            $userinfo = $this->find()->where(['mobile' => $mobile])
                ->andWhere(['<>', 'mobile', 0])->exists();
            if (!empty($userinfo)) {
                return ResultHelper::json(401, '手机号已被员工账号占用');
            }
        }
        /* 查看邮箱是否重复 */
        if ($email) {
            $userinfo = $this->find()->where(['email' => $email])->exists();
            if ($userinfo) {
                return ResultHelper::json(401, '邮箱已被占用');
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
                'source_type'     => $source_type,
                'company'         => $company,
                'parent_bloc_id'  => $parent_bloc_id,
                'avatar'          => $avatar,
                'bloc_id'         => Yii::$app->request->input('bloc_id'),
            ]));
        //

        $this->auth_role_id   = Yii::$app->request->input('auth_role_id', 0);
        $this->avatar         = $avatar;
        $this->username       = $username;
        $this->email          = $email;
        $this->parent_bloc_id = (int) $parent_bloc_id ?: Yii::$app->request->input('bloc_id', 0);
        $this->company        = $company;
        $this->mobile         = $mobile;
        $this->customer_id    = $customer_id;
        $this->created_at     = time();
        $this->updated_at     = time();
        $this->disabled       = $disabled == 1 ? 1 : 0;

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
            UserService::initUserAuth($user_id, $is_create_bloc);
            /* 写入用户apitoken */
            $AccessTokenService = new AccessTokenService();
            return $AccessTokenService->getAccessToken($this, 1);
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
        ]);
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
        ]);
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

    public function delete()
    {
        if (empty($this->delete_time)) {
            $this->delete_time = date('Y-m-d H:i:s');
            $r                 = $this->save(false);

            if ($r) {
                return true;
            }
        }

        return false;
    }

    public function getRole()
    {
        return $this->hasOne(AuthMemberRole::class, ['id' => 'auth_role_id']);
    }
}
