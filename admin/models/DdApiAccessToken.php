<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-12 16:40:19
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-01-23 18:37:02
 */

namespace admin\models;

use common\helpers\loggingHelper;
use common\helpers\StringHelper;
use common\rpc\jwt\Exception;
use common\rpc\jwt\Jwt;
use common\rpc\services\JwtService;
use common\rpc\utility\DebugHelper;
use EasySwoole\Component\Context\ContextManager;
use Yii;
use common\models\DdMemberGroup;
use common\models\enums\CodeStatus;
use common\models\User;
use diandi\admin\models\UserGroup;
use yii\db\ActiveQuery;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\IdentityInterface;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\filters\RateLimitInterface;

/**
 * This is the model class for table "dd_admin_access_token".
 *
 * @property mixed|string|null $refresh_token
 * @property int $user_id
 * @property int $id
 * @property int $group_id
 * @property string $access_token
 * @property-read ActiveQuery $memberGroup
 * @property-read ActiveQuery $member
 * @property-read string|int $authKey
 * @property int $status
 * @public int         $id
 * @public string|null $refresh_token 刷新令牌
 * @public string|null $access_token  授权令牌
 * @public int|null    $user_id     用户id
 * @public string|null $openid        授权对象openid
 * @public string|null $group         组别
 * @public int|null    $status        状态[-1:删除;0:禁用;1启用]
 * @public int|null    $create_time   创建时间
 * @public int|null    $updated_time  修改时间
 */


class DdApiAccessToken extends ActiveRecord implements IdentityInterface, RateLimitInterface
{
    const STATUS_DELETED = -1; //删除
    const STATUS_INACTIVE = 0; //禁用
    const STATUS_ACTIVE = 1; //正常启用

    // 次数限制
    public int  $rateLimit = 60;

    // 时间范围
    public int  $timeLimit = 60;
 
    public int|string  $auth_key = '';

    
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_access_token}}';
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
                'updatedAttribute' => 'create_time',
                'createdAttribute' => 'update_time',
            ],
        ];
    }

    public function getRateLimit($request, $action): array
    {
        $this->rateLimit = Yii::$app->params['api']['rateLimit'];
        $this->timeLimit = Yii::$app->params['api']['timeLimit'];
      
        return [$this->rateLimit, $this->timeLimit];
    }

    public function loadAllowance($request, $action): array
    {
        $allowance = Yii::$app->cache->get($this->getCacheKey('api_rate_allowance'));
        $timestamp = Yii::$app->cache->get($this->getCacheKey('api_rate_timestamp'));

        if ($allowance === false) {
            return [$this->timeLimit, time()];
        }

        return [$allowance, $timestamp];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp): void
    {
        Yii::$app->cache->set($this->getCacheKey('api_rate_allowance'), $allowance, $this->timeLimit);
        Yii::$app->cache->set($this->getCacheKey('api_rate_timestamp'), $timestamp, $this->timeLimit);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['group_id', 'status', 'allowance', 'allowance_updated_at', 'create_time', 'updated_time'], 'integer'],
            [['refresh_token', 'access_token'], 'string', 'max' => 60],
            [['openid'], 'string', 'max' => 50],
            [['authorization'], 'string', 'max' => 1000],
            [['access_token'], 'unique'],
            [['refresh_token'], 'unique'],
        ];
    }

    /**
     * @param mixed $token
     * @param null $type
     *
     * @return array|mixed|ActiveRecord|IdentityInterface|null
     *
     * @throws UnauthorizedHttpException
     * @throws \Exception
     */
    public static function findIdentityByAccessToken($token, $type = null): mixed
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // 判断验证token有效性是否开启
        $Authorization = Yii::$app->request->headers->get('authorization', '');

        if ($Authorization){ //用户中心授权处理
            $config = JwtService::getConfig();
            // 验证 JWT 是否合法
            $secret = $config['SecretKey'];
            $tokenStr = JwtService::decrypt_ecb($Authorization);
            $jwtObject = Jwt::getInstance()->setSecretKey($secret)->decode($tokenStr);

            $status = $jwtObject->getStatus();

            $Iss = $jwtObject->getIss();

            if ($Iss != $config['Iss']) {
                throw new UnauthorizedHttpException('非法请求');
            }

            // 如果encode设置了秘钥,decode 的时候要指定
            switch ($status) {
                case  1:
                    $userToken = JwtService::getTokenByAuthorization($Authorization,['access_token','allowance']);
                    if (Yii::$app->params['user.accessTokenValidity'] === true) {
                        // 验证有效期
                        if ($userToken['allowance'] <= time()) {
                            throw new UnauthorizedHttpException('用户您好，您的登录验证已经过期，请重新登录',CodeStatus::getValueByName('token失效'));
                        }
                    }
                    $service = Yii::$app->service;
                    $service->namespace = 'admin';
                    // 优化版本到缓存读取用户信息 注意需要开启服务层的cache
                    return $service->AccessTokenService->getTokenToCache($userToken['access_token'], $type);
                case  -1:
                    throw new UnauthorizedHttpException('authorization已失效');
                case  -2:
                    throw new UnauthorizedHttpException('authorization已过期');
                default:
                throw new UnauthorizedHttpException('authorization无效');
            }
        }else{//yii验证token
            if (Yii::$app->params['user.accessTokenValidity'] === true) {
                $allowance = DdApiAccessToken::find()->where(['access_token' => $token])->select('allowance')->scalar();
//                $expire = Yii::$app->params['user.accessTokenExpire'];
                // 验证有效期
                if ($allowance <= time()) {
                    throw new UnauthorizedHttpException('您的登录验证已经过期，请重新登录',CodeStatus::getValueByName('token失效'));
                }
            }
            $service = Yii::$app->service;
            $service->namespace = 'admin';
            // 优化版本到缓存读取用户信息 注意需要开启服务层的cache
            return $service->AccessTokenService->getTokenToCache($token, $type);
        }
    }

    /**
     * @param $token
     * @param null $group
     *
     * @return DdApiAccessToken|null
     */
    public static function findIdentityByRefreshToken($token, $group = null): ?DdApiAccessToken
    {
        return static::findOne(['group_id' => $group, 'refresh_token' => $token, 'status' => 1]);
    }



    /**
     * 关联用户.
     *
     * @return ActiveQuery
     */
    public function getMember(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getUserInfo(): array
    {
        return User::find()->where(['id' => $this->user_id])->asArray()->one();
    }

    public function getDepartmentId()
    {
        return User::find()->where(['id' => $this->user_id])->select('department_id')->scalar();
    }

    /**
     * 关联授权角色.
     *
     * @return ActiveQuery
     */
    public function getMemberGroup(): ActiveQuery
    {
        return $this->hasOne(UserGroup::class, ['id' => 'group_id'])
            ->where(['type' => Yii::$app->id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): DdApiAccessToken|IdentityInterface|null
    {
        $Res =static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
        if ($Res){
            return $Res;
        }else{
            $user = User::find()->where(['id' => $id])->asArray()->one();
            if ($user){
                $Res = new static();
                $Res->user_id = $id;
                $Res->access_token = StringHelper::uuid('random');
                $Res->refresh_token = StringHelper::uuid('random');
                $Res->save();
                return $Res;
            }
        }
        return null;
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId(): int|string
    {
        return $this->user_id;
    }

    /**
     * @return int|string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey(): int|string
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     *
     * @return bool if an auth key is valid for current user
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $key
     *
     * @return array
     */
    public function getCacheKey($key): array
    {
        return [__CLASS__, $this->getId(), $key];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'refresh_token' => 'Refresh Token',
            'access_token' => 'Access Token',
            'user_id' => '用户id',
            'openid' => 'Openid',
            'group_id' => '用户组',
            'status' => '用户状态',
            'create_time' => 'Create Time',
            'updated_time' => 'Updated Time',
        ];
    }
}
