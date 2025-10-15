<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 14:58:10
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-28 14:25:32
 */

namespace common\rpc\services;

use admin\models\addons\models\Bloc;
use admin\models\DdApiAccessToken;
use common\helpers\StringHelper;
use common\rpc\jwt\Jwt;
use common\rpc\model\DdUser;
use common\rpc\pdo\PdoQuery;
use common\rpc\utility\DebugHelper;
use Exception;
use yii\base\InvalidArgumentException;

// https: //cloud.tencent.com/developer/article/2000236
class JwtService extends BaseRpcService
{
    /**
     * @throws Exception
     */
    public static function createTokenByUsernameAndPassword($mobile, $password): array
    {
        $user = User::findRecord(['mobile'=> $mobile])->asArray();
        if (!$user) {
            return [
                'status' => 400,
                'message' => '用户名不存在',
                'data' => [
                    'sql'=>PdoQuery::getInstance()->getLastSql()
                ],
            ];
        }

        $config = self::getConfig();
        $SecretKey = $config['SecretKey'];
        $jwtObject = Jwt::getInstance()
            ->setSecretKey($SecretKey) // 秘钥
            ->publish();

        $Iss = $config['Iss'];
        $Sub = $config['Sub'];
        $jwtObject->setAlg('HMACSHA256'); // 加密方式
        $jwtObject->setAud($mobile); // 用户
        $jwtObject->setExp(time() + 3600); // 过期时间
        $jwtObject->setIat(time()); // 发布时间
        $jwtObject->setIss($Iss); // 发行人
        $jwtObject->setJti(md5(time())); // jwt id 用于标识该jwt
        $jwtObject->setNbf(time() + 60 * 5); // 在此之前不可用
        $jwtObject->setSub($Sub); // 主题

        $checkPass = self::validatePassword($password,$user['password_hash']);
        if (!$checkPass) {
            return [
                'status' => 400,
                'message' => '密码错误',
                'data' => null
            ];
        }

        $tokens = PdoQuery::getInstance()->findOneBy('dd_user_access_token', ['user_id' => $user['id']]);
        $access_token = '';
        $refresh_token = '';
        $refresh_time = time();
        if($tokens){
            $access_token = $tokens['access_token'];
            $refresh_token = $tokens['refresh_token'];
            $refresh_time = $tokens['allowance_updated_at'];
        }

        // 校验有效期
        if ($refresh_time < time()) {
            $refresh_token = StringHelper::random(30);
            $AccessTokenRpcService = new AccessTokenRpcService();
            $AccessTokenRpcService->EditAuthorization($user['id'], $refresh_token);
        }

        unset($user['getAccessToken'], $user['password_hash']);
        // 自定义数据
        $jwtObject->setData([
            'member' => [
                'company' => $user['company'],
                'user_id' => $user['id'],
                'username' => $user['username'],
                'mobile' => $user['mobile'],
                'avatar' => $user['avatar']
            ]
        ]);

        // 最终生成的token
        $token = $jwtObject->__toString();
        $authorization = self::encrypt_ecb($token);
        $AccessTokenRpcService = new AccessTokenRpcService();
        $AccessTokenRpcService->EditAuthorization($user['id'], $authorization);
        return [
            'status' => 200,
            'type' => 'login_res',
            'message' => '登录成功',
            'data' => [
                'member' => [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'company' => $user['company'],
                    'mobile' => $user['mobile'],
                    'avatar' => $user['avatar'],
                    'store_id' => $user['store_id'],
                    'bloc_id' => $user['bloc_id'],
                ],
                'access_token' => $access_token,
                'authorization' => $authorization,
                'refresh_token' => $refresh_token,
            ],
        ];
    }

    /**
     * yii 模式下换取token
     * @param array|string $Authorization
     * @param array $column
     * @return array
     */
    public static function getTokenByAuthorization(array|string $Authorization,array $column = ['*']): array
    {
        return DdApiAccessToken::find()->where(['authorization' => $Authorization])->select($column)->asArray()->one();
    }


    /**
     * @throws Exception
     */
    public static function createTokenByUid($user_id): array
    {
        $user = PdoQuery::getInstance()->findById('dd_user', $user_id);

        if (!$user) {
            return [
                'status' => 400,
                'message' => '用户不存在',
                'data' => null,
            ];
        }
        $username = $user['username'];
        $config = self::getConfig();
        $SecretKey = $config['SecretKey'];
        $jwtObject = Jwt::getInstance()
            ->setSecretKey($SecretKey) // 秘钥
            ->publish();

        $Iss = $config['Iss'];
        $Sub = $config['Sub'];
        $jwtObject->setAlg('HMACSHA256'); // 加密方式
        $jwtObject->setAud($username); // 用户
        $jwtObject->setExp(time() + 3600); // 过期时间
        $jwtObject->setIat(time()); // 发布时间
        $jwtObject->setIss($Iss); // 发行人
        $jwtObject->setJti(md5(time())); // jwt id 用于标识该jwt
        $jwtObject->setNbf(time() + 60 * 5); // 在此之前不可用
        $jwtObject->setSub($Sub); // 主题


        $tokens = PdoQuery::getInstance()->findOneBy('dd_user_access_token', ['user_id' => $user['id']]);
        $access_token = '';
        $refresh_token = '';
        $refresh_time = time();
        if($tokens){
            $access_token = $tokens['access_token'];
            $refresh_token = $tokens['refresh_token'];
            $refresh_time = $tokens['allowance_updated_at'];
        }

        // 校验有效期
        if ($refresh_time < time()) {
            $refresh_token = StringHelper::random(30);
            $AccessTokenRpcService = new AccessTokenRpcService();
            $AccessTokenRpcService->EditAuthorization($user['id'], $refresh_token);
        }

        unset($user['getAccessToken'], $user['password_hash']);
        // 自定义数据
        $jwtObject->setData([
            'member' => [
                'company' => $user['company'],
                'user_id' => $user['id'],
                'username' => $user['username'],
                'mobile' => $user['mobile'],
                'avatar' => $user['avatar']
            ]
        ]);

        // 最终生成的token
        $token = $jwtObject->__toString();
        $access_token = self::encrypt_ecb($token);
        $AccessTokenRpcService = new AccessTokenRpcService();
        $AccessTokenRpcService->EditAuthorization($user['id'], $access_token);
        return [
            'status' => 200,
            'type' => 'login_res',
            'message' => '登录成功',
            'data' => [
                'member' => [
                    'member_id' => $user['member_id'],
                    'username' => $user['username'],
                    'mobile' => $user['mobile'],
                    'address' => $user['address'],
                    'nickName' => $user['nickName'],
                    'avatarUrl' => $user['avatarUrl'],
                    'gender' => $user['gender'],
                    'country' => $user['country'],
                    'province' => $user['province'],
                    'realname' => $user['realname'],
                ],
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ],
        ];
    }

    /**
     * 刷下token
     * @throws Exception
     */
    public static function flushToken($refresh_token): array
    {
        $user_id = PdoQuery::getInstance()->findField('dd_user_access_token','user_id', ['refresh_token' => $refresh_token]);

        if (!$user_id) {
            return [
                'status' => 400,
                'message' => 'refresh_token不正确',
                'data' => null,
            ];
        }

        $user = PdoQuery::getInstance()->findOneBy('dd_user', ['user_id' => $user_id]);

        if (!$user) {
            return [
                'status' => 400,
                'message' => '用户名不存在',
                'data' => null,
            ];
        }

        $username = $user['username'];

        $config = self::getConfig();
        $SecretKey = $config['SecretKey'];
        $jwtObject = Jwt::getInstance()
            ->setSecretKey($SecretKey) // 秘钥
            ->publish();

        $Iss = $config['Iss'];
        $Sub = $config['Sub'];
        $jwtObject->setAlg('HMACSHA256'); // 加密方式
        $jwtObject->setAud($username); // 用户
        $jwtObject->setExp(time() + 3600); // 过期时间
        $jwtObject->setIat(time()); // 发布时间
        $jwtObject->setIss($Iss); // 发行人
        $jwtObject->setJti(md5(time())); // jwt id 用于标识该jwt
        $jwtObject->setNbf(time() + 60 * 5); // 在此之前不可用
        $jwtObject->setSub($Sub); // 主题


        // 自定义数据
        $jwtObject->setData([
            'member' => [
                'member_id' => $user['member_id'],
                'username' => $user['username'],
                'mobile' => $user['mobile'],
                'address' => $user['address'],
                'nickName' => $user['nickName'],
                'avatarUrl' => $user['avatarUrl'],
                'gender' => $user['gender'],
                'country' => $user['country'],
                'province' => $user['province'],
                'realname' => $user['realname'],
            ]
        ]);

        // 最终生成的token
        $token = $jwtObject->__toString();

        $access_token = self::encrypt_ecb($token);

        return [
            'status' => 200,
            'type' => 'login_res',
            'message' => '刷新token成功',
            'data' => [
                'member' => [
                    'member_id' => $user['member_id'],
                    'username' => $user['username'],
                    'mobile' => $user['mobile'],
                    'address' => $user['address'],
                    'nickName' => $user['nickName'],
                    'avatarUrl' => $user['avatarUrl'],
                    'gender' => $user['gender'],
                    'country' => $user['country'],
                    'province' => $user['province'],
                    'realname' => $user['realname'],
                ],
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ],
        ];
    }

    /**
     * @param $token
     * @return array
     * @throws Exception
     */
    public static function getUser($token): array
    {
        $token = self::decrypt_ecb($token);
        $jwtObject = Jwt::getInstance()->decode($token);
        return $jwtObject->getData();
    }


    /**
     * @throws Exception
     */
    public static function checkPassword($user, $password): bool
    {
        $hash = $user['password_hash'];
        $AccessTokenRpcService = new AccessTokenRpcService();
        $password_hash = $AccessTokenRpcService->generatePasswordHash($password);
        DebugHelper::consoleWrite('加密密码',[
            'password_hash' => $password_hash,
            'hash' => $hash,
            'password' => $password,
        ]);
        return $password_hash  === $hash;
    }

    public static function validatePassword($password, $hash): bool
    {
        if (!is_string($password) || $password === '') {
            throw new InvalidArgumentException('Password must be a string and cannot be empty.');
        }

        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches)
            || $matches[1] < 4
            || $matches[1] > 30
        ) {
            throw new InvalidArgumentException('Hash is invalid.');
        }

        if (function_exists('password_verify')) {
            return password_verify($password, $hash);
        }

        $test = crypt($password, $hash);
        $n = strlen($test);
        if ($n !== 60) {
            return false;
        }

        return self::compareString($test, $hash);
    }

    /**
     * Performs string comparison using timing attack resistant approach.
     * @see https://codereview.stackexchange.com/q/13512
     * @param string $expected string to compare.
     * @param string $actual user-supplied string.
     * @return bool whether strings are equal.
     */
    public static function compareString(string $expected, string $actual): bool
    {
        if (function_exists('hash_equals')) {
            return hash_equals($expected, $actual);
        }

        $expected .= "\0";
        $actual .= "\0";
        $expectedLength = \yii\helpers\StringHelper::byteLength($expected);
        $actualLength = StringHelper::byteLength($actual);
        $diff = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++) {
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        }

        return $diff === 0;
    }

    /**
     * AES-256-ECB 加密.
     *
     * @param $data
     *
     * @return string
     * @throws Exception
     */
    public static function encrypt_ecb($data): string
    {
        $config = self::getConfig();
        $SecretKey = $config['SecretKey'];
        $text = openssl_encrypt($data, 'AES-256-ECB', $SecretKey, 1);

        return base64_encode($text);
    }

    /**
     * AES-256-ECB 解密.
     *
     * @param $text
     *
     * @return string
     * @throws Exception
     */
    public static function decrypt_ecb($text): string
    {
        $config = self::getConfig();
        $SecretKey = $config['SecretKey'];
        $decodeText = base64_decode($text);
        return openssl_decrypt($decodeText, 'AES-256-ECB', $SecretKey, 1);
    }

    /**
     * @throws Exception
     */
    public static function getConfig(): array
    {
        $config = __DIR__.'/../config/jwt.php';
        if (file_exists($config)) {
            return require $config;
        }
        throw new Exception('jwt配置文件不存在');
    }

    /**
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function signup(mixed $username, string $mobile, mixed $email, mixed $password, int $int, string $invitation_code):array
    {
        /* 查看手机号是否重复 */
        if ($mobile) {
            $userinfo = PdoQuery::getInstance()->findField('dd_user','id' ,['mobile' => $mobile]);
            if (!empty($userinfo)) {
                return $this->writeJson(401, '手机号已被占用');
            }
        }
        /* 查看邮箱是否重复 */
        if ($email) {
            $userinfo = PdoQuery::getInstance()->findField('dd_user','id' ,['email' => $email]);
            if (!empty($userinfo)) {
                return $this->writeJson(401, '邮箱已被占用');
            }
        }

        $parent_bloc_id = 0;
        if ($invitation_code) {
            $parent_bloc_id = PdoQuery::getInstance()->findField('dd_user','id' ,['email' => $email]);
                Bloc::find()->where(['invitation_code' => $invitation_code])->select('bloc_id')->scalar();
        }

        $AccessTokenRpcService = new AccessTokenRpcService();
        $auth_key = StringHelper::generateRandomString();
        $password_reset_token = StringHelper::generateRandomString(32);
        $verification_token = StringHelper::generateRandomString(32);

        $password_hash = $AccessTokenRpcService->generatePasswordHash($password);
        $user_id =  PdoQuery::getInstance()->insert('dd_user',[
            'username' => $username,
            'mobile' => $mobile,
            'email' => $email,
            'parent_bloc_id' => $parent_bloc_id,
            'password_hash' => $password_hash,
            'auth_key' => $auth_key,
            'password_reset_token' => $password_reset_token,
            'verification_token' => $verification_token,
//            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        DebugHelper::consoleWrite('pdo-executeQuery',[
            'uid'=>$user_id
        ]);
        if ($user_id) {
//            UserService::initUserAuth($user_id);
            return self::createTokenByUid($user_id);
        } else {
            return $this->writeJson(401, '注册失败');
        }
    }

    public function repassword(mixed $mobile, mixed $code, mixed $password):array
    {
        return [];
    }
}
