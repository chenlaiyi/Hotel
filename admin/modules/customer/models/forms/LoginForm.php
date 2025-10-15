<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-04-21 22:58:32
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 09:30:26
 */

namespace admin\modules\customer\models\forms;

use admin\modules\customer\models\CustomerUser as User;
use admin\modules\customer\services\AccessTokenService;
use admin\services\UserAccountService;
use common\helpers\loggingHelper;
use common\helpers\MapHelper;
use common\helpers\ResultHelper;
use common\models\enums\UserStatus;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\IdentityInterface;

/**
 * Login form.
 */
class LoginForm extends Model
{
    public $mobile;
    public $company;
    public $sms_code;
    public $password;
    public $type; //登录方式 1 用户名 2手机号

    public $rememberMe = true;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile'], 'eitherOneRequired', 'skipOnEmpty' => false, 'skipOnError' => false],
            // username and password are both required
            [['mobile', 'password', 'type'], 'required'],
            ['type', 'in', 'range' => [1, 2]],
            [['company'], 'string'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function eitherOneRequired($attribute, $params, $validator)
    {

        if (empty($this->mobile)) {
            $this->addError($attribute, '手机号不能为空');

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rememberMe' => '记住',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array|null $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, array|null $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (empty($user)) {
                $this->addError($attribute, '手机号不存在或账号未审核');
            } else {
                if (!$user->validatePassword($this->password)) {
                    $this->addError($attribute, '密码不正确');
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return array|bool|object[]|string[]
     * @throws Exception
     * @throws \Throwable
     */
    public function login(): array|bool
    {
        if ($this->validate()) {
            $mobile = $this->mobile;
            $code = $this->sms_code;
            $sendCode = Yii::$app->cache->get($mobile . '_code');

            $settings = Yii::$app->settings;
            $settings->invalidateCache();
            $info = $settings->getAllBySection('Website');
            $is_send_code = $info['is_send_code'] ?? 0;
            //            if (!empty($info) && (int)$is_send_code === 1 && $this->type === 2) {
            //                if (empty($code)) {
            //                    return ResultHelper::json(401, '验证码不能为空');
            //                }
            //                if ($code != $sendCode) {
            //                    return ResultHelper::json(401, '验证码错误');
            //                }
            //            }

            $userInfo = $this->getUser();
            if (empty($userInfo)) {
                $info = User::findUser($this->mobile);
                if (!empty($info)) {
                    $list = UserStatus::listData();
                    $status_str = $list[$info['status']];

                    return ResultHelper::json(400, '您的账户' . $status_str . '，请联系客服');
                } else {
                    return ResultHelper::json(400, '账户不存在');
                }
            }

            $Res = Yii::$app->user->login($userInfo, $this->rememberMe ? 3600 * 24 * 30 : 0);
            if ($Res) {
                $last_login_ip = MapHelper::get_client_ip();
                //                单点登录校验
                //                if (isset(Yii::$app->user->identity->id)) {
                //                    $user = User::find()->where([
                //                        'id' => Yii::$app->user->identity->id,
                //                        'last_login_ip' => $last_login_ip,
                //                    ])->select(['is_login'])->one();
                // if($user['is_login']==1 && $user['last_time']+60*5<time()){

                //     Yii::$App->user->logout();
                //     Yii::$App->session->setFlash('success', '该账户已在其他浏览器登录');
                //     return $this->goHome();
                // }
                //                }

                // 记录最后登录的时间
                $password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

                User::updateAll([
                    'last_time' => time(),
                    'is_login' => 1,
                    'last_login_ip' => $last_login_ip,
                    'password_reset_token' => $password_reset_token
                ], ['id' => Yii::$app->user->identity->id]);
                /**
                 * 初始化用户资产数据
                 */
                UserAccountService::initAccount(Yii::$app->user->identity->id);
                $openid = Yii::$app->request->input('openid');
                UserAccountService::bingOpenid(Yii::$app->user->identity->id, $openid);
                $userObj = User::findByMobile($this->mobile);
                $AccessTokenService = new AccessTokenService();
                $userinfo = $AccessTokenService->getAccessToken($userObj, 1);
                // 登录日志记录
                loggingHelper::actionLog(Yii::$app->user->identity->id, '账号登录', $last_login_ip);

                return ArrayHelper::toArray($userinfo);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]].
     *
     * @return array|ActiveRecord|null|IdentityInterface
     */
    protected function getUser(): array|\yii\db\ActiveRecord|null|IdentityInterface
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }
}
