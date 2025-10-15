<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-12 01:50:17
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-13 09:45:08
 */

namespace common\rpc\services;

use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\StringHelper;
use common\rpc\pdo\PdoQuery;
use common\rpc\redis\RedisClient;
use common\rpc\redis\RedisPoolContainer;
use diandi\addons\models\DdAddons;
use yii\base\Exception;
use yii\base\InvalidArgumentException;

/**
 * 用户中心使用
 * Class AccessTokenService.
 *
 * @author wangchunsheng <2192138785@qq.com>
 */
class AccessTokenRpcService
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
     * @var string strategy, which should be used to generate password hash.
     * Available strategies:
     * - 'password_hash' - use of PHP `password_hash()` function with PASSWORD_DEFAULT algorithm.
     *   This option is recommended, but it requires PHP version >= 5.5.0
     * - 'crypt' - use PHP `crypt()` function.
     * @deprecated since version 2.0.7, [[generatePasswordHash()]] ignores [[passwordHashStrategy]] and
     * uses `password_hash()` when available or `crypt()` when not.
     */
    public $passwordHashStrategy;
    /**
     * @var int Default cost used for password hashing.
     * Allowed value is between 4 and 31.
     * @see generatePasswordHash()
     * @since 2.0.6
     */
    public $passwordHashCost = 13;

    /**
     * 获取token.
     *
     * @param $user_id
     * @param int $group_id
     * @param int $cycle_index 重新获取次数
     *
     * @return array
     *
     * @throws \RedisException
     * @throws \Exception
     */
    public function getAccessToken($user_id,int $group_id=1, int $cycle_index = 1): array
    {
        $keys = $user_id.'getAccessToken';

        $user = $this->findModel($user_id, $group_id);

        /* access-token 是否到期，到期就重置 */
        if ($this->isPeriod($user['authorization']) || empty($user['authorization'])) {
            // 删除缓存
            $refresh_token = '';
            !empty($user->authorization) &&         RedisClient::getInstance()->delete($this->getCacheKey($user->authorization));
            if ($this->isPeriodRefToken($user['refresh_token']) || empty($user['refresh_token'])) {

                $refresh_token = StringHelper::uuid('sha1').'_'.time();
            }
            $authorization = StringHelper::uuid('sha1').'_'.time();
            $status = 1;
            $res = PdoQuery::getInstance()->update('dd_user', ['refresh_token' => $refresh_token, 'authorization' => $authorization, 'status' => $status], ['id' => $user['id']]);
            if (!$res) {
                if ($cycle_index <= 3) {
                    ++$cycle_index;
                    return self::getAccessToken($user_id, $group_id, $cycle_index);
                }
            }
        }


        $result = RedisClient::getInstance()->get($keys);
        if ($result){
            return $result;
        }
        $result = [];
        $result['refresh_token'] = $user['refresh_token'];
        $result['authorization'] = $user['authorization'];
        $result['expiration_time'] =  3600;


        if (isset($user['avatar'])){
            $user['avatar'] = ImageHelper::tomedia($user['avatar']);
        }
        $result['user'] = $user;
        $result['addons'] = false;

        // 关联用户的默认模块和商户
        $module_name = PdoQuery::getInstance()->findField('dd_user_addons','module_name',['user_id' => $user['id'], 'is_default' => 1]);

        $store_id = PdoQuery::getInstance()->findField('dd_user_store','store_id',['user_id' => $user['id'], 'is_default' => 1]);

        $bloc_id = PdoQuery::getInstance()->findField('dd_user_bloc','bloc_id',['user_id' => $user['id'], 'is_default' => 1]);
        $bloc = PdoQuery::getInstance()->findOneBy('dd_bloc',['bloc_id' => $bloc_id]);

        if(!empty($bloc) && $bloc['bloc']){
            $bloc['bloc']['logo'] = ImageHelper::tomedia($bloc['bloc']['logo']);
            $result['bloc'] = $bloc['bloc'];
        }

        // 商户为空授权的是公司
        if (!empty($module_name)) {
            $result['addons'] = [
                'module_name' => $module_name,
                'module_info' => DdAddons::find()->where(['identifie' => $module_name])->asArray()->one(),
                'store_id' => $store_id,
            ];
        }
        RedisClient::getInstance()->set($keys, $result);
        RedisClient::getInstance()->set($this->getCacheKey($user->authorization), $user);

        return $result;
    }

    /**
     * 忘记密码.
     *
     * @param $mobile
     * @param $password
     * @return array|object[]|string|string[]
     * @throws Exception
     * @throws \Exception
     */
    public function forgetpassword($mobile, $password): array|string
    {
        $auth_key = StringHelper::uuid('sha1');

        $password_reset_token = StringHelper::uuid('time', true);

        $password_hash = $this->generatePasswordHash($password);

        return PdoQuery::getInstance()->update('dd_user_access_token',[
            'auth_key' => $auth_key,
            'password_hash' => $password_hash,
            'password_reset_token' => $password_reset_token,
            'updated_at' => time(),
        ] ,['mobile' => $mobile]);
    }

    /**
     * @throws Exception
     */
    public function generatePasswordHash($password, $cost = null): string
    {
        if ($cost === null) {
            $cost = $this->passwordHashCost;
        }

        if (function_exists('password_hash')) {
            return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
        }

        $salt = $this->generateSalt($cost);
        $hash = crypt($password, $salt);
        // strlen() is safe since crypt() returns only ascii
        if (strlen($hash) !== 60) {
            throw new Exception('Unknown error occurred while generating hash.');
        }

        return $hash;
    }

    protected function generateSalt($cost = 13)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            throw new InvalidArgumentException('Cost must be between 4 and 31.');
        }

        // Get a 20-byte random string
        $rand = $this->generateRandomKey(20);
        // Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
        $salt = sprintf('$2y$%02d$', $cost);
        // Append the random salt data in the required base64 format.
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

        return $salt;
    }

    public function generateRandomKey($length = 32)
    {
        if (!is_int($length)) {
            throw new InvalidArgumentException('First parameter ($length) must be an integer');
        }

        if ($length < 1) {
            throw new InvalidArgumentException('First parameter ($length) must be greater than 0');
        }

        return random_bytes($length);
    }

    /**
     * 判断access-token有效期.
     *
     * @param string $token post
     *
     * @return  true
     *
     * @throws \Exception
     */
    public static function isPeriod(string $token, $type = null): bool
    {
        loggingHelper::writeLog('AccessTokenService', 'isPeriod', '重复获取', [
            'token' => $token
        ]);
        $user = PdoQuery::getInstance()->findOneBy('dd_user_access_token', ['authorization' => $token, 'status' => 1]);
        $allowance_updated_at = $user['allowance_updated_at'];
        $expire = 3600*2; // 默认2个小时
        // 判断验证token有效性是否开启
        if ($allowance_updated_at + $expire <= time()) {
            // 过期
            return true;
        }
        // 未到期
        return false;
    }

    /**
     * 判断refresh_token有效期.
     *
     * @param string $token post
     *
     *
     * @throws \Exception
     */
    public static function isPeriodRefToken(string $token, $type = null): bool
    {
        $user = PdoQuery::getInstance()->findOneBy('dd_user_access_token', ['authorization' => $token, 'status' => 1]);
        $refresh_time = $user['refresh_time'];
        $expire = 3600*24*30; // 默认30天
        // 判断验证token有效性是否开启
        if ($refresh_time + $expire <= time()) {
            // 过期
            return true;
        }
        // 未到期
        return false;
    }

    /**
     * 修改RefreshToken
     *
     * @param int|null $user_id post
     * @param $RefreshToken
     * @param int $group_id
     * @return void
     * @throws \Exception
     */
    public function EditRefreshToken(?int $user_id,$RefreshToken, int $group_id = 1): void
    {
        $user = $this->findModel($user_id, $group_id);
        PdoQuery::getInstance()->update('dd_user_access_token',['refresh_token' => $RefreshToken] ,['user_id' => $user['user_id']]);
    }

    /**
     * 修改AccessToken
     *
     * @param int|null $user_id post
     * @param $Authorization
     * @param int $group_id
     * @return void
     * @throws \Exception
     */
    public function EditAuthorization(?int $user_id,$Authorization, int $group_id = 1): void
    {
        $this->EditAccessTokenTime($user_id);
        PdoQuery::getInstance()->update('dd_user_access_token',['authorization' => $Authorization] ,['user_id' => $user_id]);
    }

    /**
     * 更新token有效期，与yii的token一样，只是这里没有使用redis缓存，而是使用数据库缓存
     * @param $user_id
     * @return void
     * @throws \Exception
     */
    function EditAccessTokenTime($user_id): void
    {
        $params = require __DIR__ . '/../../../admin/config/params.php';
        $allowance = time()+$params['user.accessTokenExpire'];
        $access_token = StringHelper::uuid('time', true);
        PdoQuery::getInstance()->update('dd_user_access_token',[
            'allowance' => $allowance,
            'allowance_updated_at' => time(),
            'access_token' => $access_token
        ] ,['user_id' => $user_id]);
    }

    /**
     * 禁用token.
     *
     * @param string $access_token
     * @return bool
     * @throws \Exception
     */
    public function disableByAuthorization(string $access_token): bool
    {
//        $this->cache === true && Yii::$app->cache->delete($this->getCacheKey($access_token));
        return PdoQuery::getInstance()->update('dd_user_access_token',['status' => 1] ,['authorization' => $access_token]);
    }

    /**
     * 获取token.
     *
     * @param $token
     *
     * @return array
     * @throws \Exception
     */
    public function findByAuthorization($token): array
    {
        return PdoQuery::getInstance()->findOneBy('dd_user_access_token', ['authorization' => $token, 'status' => 1]);
    }

    /**
     * 更新登录次数.
     *
     * @param $token
     *
     * @return int
     * @throws \Exception
     */
    public function upLoginNum($token): int
    {
        return PdoQuery::getInstance()->update('dd_user_access_token', ['last_login_time' => time()], ['authorization' => $token]);
    }

    /**
     * @param $access_token
     *
     * @return string
     */
    protected function getCacheKey($access_token): string
    {
        return $access_token;
    }

    /**
     * 返回模型.
     *
     * @param $user_id
     * @param $group_id
     * @return array
     * @throws \Exception
     */
    protected function findModel($user_id, $group_id): array
    {
        return PdoQuery::getInstance()->findOneBy('dd_user_access_token', ['user_id' => $user_id, 'group_id' => $group_id]);
    }
}
