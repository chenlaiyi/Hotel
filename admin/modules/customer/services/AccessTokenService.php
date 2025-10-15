<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-12 01:50:17
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 09:56:02
 */

namespace admin\modules\customer\services;

use admin\models\addons\models\Bloc;
use admin\modules\customer\models\CustomerUser;
use admin\modules\customer\models\DdApiAccessToken;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\plugins\diandi_auth\models\MemberPermission;
use common\plugins\diandi_auth\models\MemberRolePermission;
use common\plugins\diandi_auth\models\searchs\MemberRole;
use common\services\BaseService;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class AccessTokenService.
 * @author wangchunsheng <2192138785@qq.com>
 */
class AccessTokenService extends BaseService
{
    /**
     * 是否加入缓存.
     *
     * @var bool
     */
    public bool $cache = true;

    /**
     * 缓存过期时间.
     *
     * @var int
     */
    public int $timeout = 7200;

    /**
     * 获取token.
     *
     * @param User|null $member
     * @param [type] $group_id
     * @param int $cycle_index
     *
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     * @date 2022-10-18
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getAccessToken(?CustomerUser $member, $group_id, int $cycle_index = 1): array
    {
        $model = $this->findModel($member->id, $group_id);

        $model->user_id = $member->id;

        $model->group_id = $group_id;
        /* 是否到期，到期就重置 */
        if ($this->isPeriod($model->allowance) || empty($model->access_token)) {
            // 删除缓存
            ! empty($model->access_token) && Yii::$app->cache->delete($this->getCacheKey($model->access_token));
            if ($this->isPeriodRefToken($model->refresh_time) || empty($model->refresh_token)) {
                $model->refresh_token = StringHelper::uuid('random');
                $model->refresh_time  = time() + (Yii::$app->params['user.refreshTokenExpire'] ?? 3600 * 2);
            }
            $model->allowance            = time() + (Yii::$app->params['user.accessTokenExpire'] ?? 3600 * 2);
            $model->allowance_updated_at = time();
            $model->access_token         = 'customer_' . StringHelper::uuid('random');
            $model->status               = 1;
            if (! $model->save()) {
                if ($cycle_index <= 3) {
                    ++$cycle_index;

                    return $this->getAccessToken($member, $group_id, $cycle_index);
                }
                $errorshelper = new ErrorsHelper();
                throw new UnprocessableEntityHttpException($errorshelper->getModelError($model));
            }
        }

        $result                    = [];
        $result['refresh_token']   = $model->refresh_token;
        $result['access_token']    = $model->access_token;
        $result['expiration_time'] = Yii::$app->params['user.accessTokenExpire'] ?? 3600;
        // 关联账号信息
        $user = ArrayHelper::toArray($member);

        if (isset($user['avatar'])) {
            $user['avatar'] = ImageHelper::tomedia($user['avatar'], 'avatar7.jpeg');
        }

        $roleId = $member->auth_role_id;

        $result['rule_names'] = MemberRole::find()->where(['id' => $roleId])->select('name')->scalar();

        /**
         * 获取角色权限
         */
        $permissionId = MemberRolePermission::find()->where(['roleId' => $roleId, 'is_sys' => 0])->select(['permissionId'])->column();

        /**
         * 获取权限标识
         */
        $permission = MemberPermission::find()->where(['id' => $permissionId])->select('page_name')->column();

        $result['permission'] = array_values($permission);

        $result['user']      = $user;
        $result['bloc_type'] = Bloc::find()->select('type')->where(['bloc_id' => $member->bloc_id])->scalar();
        //        $result['customer'] = Customer;
        // 写入缓存
        $this->cache === true && Yii::$app->cache->set($this->getCacheKey($model->access_token), $model, $this->timeout);

        return $result;
    }

    /**
     * 忘记密码.
     *
     * @param User $user
     * @param $password
     * @return bool
     *
     * @throws ErrorException
     * @throws Exception
     */
    public function forgetpassword(User $user, $password): bool
    {
        $user->generatePasswordResetToken();
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save(false);
    }

    /**
     * 判断有效期.
     *
     * @param int|null $allowance post
     * @param null $type
     * @return bool
     */
    public static function isPeriod(int | null $allowance, $type = null): bool
    {
        // 判断验证token有效性是否开启
        if (isset(Yii::$app->params['user.accessTokenValidity']) && Yii::$app->params['user.accessTokenValidity'] === true) {
            // 验证有效期
            if (empty($allowance) || $allowance <= time()) {
                // 过期
                return true;
            }
        }
        // 未到期
        return false;
    }

    /**
     * 判断refresh_token有效期.
     *
     * @param int|null $refresh_time
     * @param null $type
     * @return true
     */
    public static function isPeriodRefToken(int | null $refresh_time, $type = null): bool
    {
        // 判断验证token有效性是否开启
        if (isset(Yii::$app->params['user.refreshTokenValidity']) && Yii::$app->params['user.refreshTokenValidity'] === true) {
            // 验证有效期
            if ($refresh_time <= time()) {
                // 过期
                return true;
            }
        }
        // 未到期
        return false;
    }

    /**
     * 修改accesstoken.
     *
     * @param int|null $user_id post
     * @param int $group_id
     * @return string
     *
     * @throws \Exception
     */
    public function RefreshToken(?int $user_id, int $group_id = 1): string
    {
        $model = $this->findModel($user_id, $group_id);

        ! empty($model->access_token) && Yii::$app->cache->delete($this->getCacheKey($model->access_token));
        $model->access_token = 'customer_' . StringHelper::uuid('random');
        $model->allowance    = time() + (Yii::$app->params['user.accessTokenExpire'] ?? 3600 * 2);

        if ($model->save()) {
            return $model->access_token;
        } else {
            return '修改失败';
        }
    }

    /**
     * @param $token
     * @param $type
     *
     * @return array|mixed|ActiveRecord|null
     */
    public function getTokenToCache($token, $type)
    {
        if (! $this->cache) {
            return $this->findByAccessToken($token);
        }

        $key = $this->getCacheKey($token);
        if (! ($model = Yii::$app->cache->get($key))) {
            $model = $this->findByAccessToken($token);
            Yii::$app->cache->set($key, $model, $this->timeout);
        }

        return $model;
    }

    /**
     * 禁用token.
     *
     * @param $access_token
     * @return bool
     */
    public function disableByAccessToken($access_token)
    {
        $this->cache === true && Yii::$app->cache->delete($this->getCacheKey($access_token));

        if ($model = $this->findByAccessToken($access_token)) {
            $model->status = 1;

            return $model->save();
        }

        return false;
    }

    /**
     * 获取token.
     *
     * @param $token
     *
     * @return array|ActiveRecord|DdApiAccessToken|null
     */
    public function findByAccessToken($token): DdApiAccessToken | array | ActiveRecord | null
    {
        return DdApiAccessToken::find()
            ->where(['access_token' => $token, 'status' => 1])
            ->one();
    }

    /**
     * @param $access_token
     *
     * @return string
     */
    protected function getCacheKey($access_token): string
    {
        // 区分传统模式后台登录
        return $access_token . '_customer';
    }

    /**
     * 注册和登录发送验证码
     *
     * @param [type] $mobile
     * @param array $data
     *
     * @return void
     * @date 2022-07-12
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function send($mobile, array $data = [])
    {
        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $config = $settings->getAllBySection('Website');

        Yii::$app->params['conf']['sms'] = [
            'access_key_id'     => $config['access_key_id'],
            'access_key_secret' => $config['access_key_secret'],
            'sign_name'         => $config['sign_name'],
            'template_code'     => $config['template_code'],
        ];

        $res = Yii::$app->service->apiSmsService->sendContent($mobile, $data, $config['template_code']);

        return $res;
    }

    // 修改用户基础信息
    public static function editInfo($user_id, $fields = [], $newpassword = ''): array
    {
        $DdMember = new User();
        if ($newpassword) {
            $fields['password_hash']        = Yii::$app->security->generatePasswordHash($newpassword);
            $fields['password_reset_token'] = Yii::$app->security->generateRandomString() . '_' . time();
        }
        if ($DdMember->updateAll($fields, ['id' => $user_id])) {
            return ResultHelper::json(200, '编辑成功');
        } else {
            return ResultHelper::json(400, '编辑失败');
        }
    }

    /**
     * 返回模型.
     *
     * @param $user_id
     * @param $group_id
     * @return ActiveRecord|array|DdApiAccessToken|null
     */
    protected function findModel($user_id, $group_id): DdApiAccessToken | array | ActiveRecord | null
    {
        if (empty(($model = DdApiAccessToken::find()->where([
            'user_id' => $user_id,
        ])->one()))) {
            $model = new DdApiAccessToken();

            return $model->loadDefaultValues();
        }

        return $model;
    }
}
