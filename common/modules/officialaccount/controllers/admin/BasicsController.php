<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-09 01:32:28
 * @Last Modified by:   Radish <minradish@163.com>
 * @Last Modified time: 2022-10-14 15:19:53
 */

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\DdUser;
use common\modules\officialaccount\services\OfficialaccountService;
use common\modules\openWeixin\services\OpenWechatAuthService;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;

/**
 * Default controller for the `WeChat` module.
 */
class BasicsController extends AController
{

    public string $modelSearchName = 'DdWechatFans';

    protected array $authOptional = ['qrcode', 'auth-url', 'check-login', 'signup', 'auth', 'userinfo'];

    public $modelClass = 'api\modules\officialaccount\models\DdWechatFans';

    /**
     * 生成临时二维码用于登录
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionQrcode(): array
    {
        $env = env('OPENWXLOGIN');

        if ($env == 1) {
            $timeType      = Yii::$app->request->input('time_type');
            $scene         = Yii::$app->request->input('scene');
            $expireSeconds = Yii::$app->request->input('expireSeconds');
            $Res           = OfficialaccountService::createQrcode((int) $timeType, $scene, (int) $expireSeconds);

            return ResultHelper::json(200, '获取成功', $Res);
        } else {
            return ResultHelper::json(200, '未开启微信登录');
        }
    }

    /**
     * 校验登录
     *
     * @return array
     * @throws \Throwable
     * @throws ErrorException
     * @throws Exception
     */
    public function actionCheckLogin(): array
    {
        $ticket = Yii::$app->request->input('ticket');
        $Res    = OfficialaccountService::checkLogin($ticket);
        return ResultHelper::json(200, '登录成功', $Res);
    }

    public function actionAuth(): array
    {
        $redirect_uri = Yii::$app->request->input('redirect_uri');
        $authCode     = Yii::$app->request->input('authCode');
        $openPlatform = Yii::$app->wechat->openPlatform;

        $Res = $openPlatform->handleAuthorize($authCode)
            ->redirect($redirect_uri);

        loggingHelper::writeLog('openWeixin', 'actionAuth', '授权成功', $Res);

        return ResultHelper::json(200, '授权成功', $Res);
    }

    public function actionUserinfo(): array
    {
        $logPath = Yii::getAlias('@runtime/officialaccount/signup/' . date('ymd') . '.log');
        if (empty(Yii::$app->request->input('code'))) {
            return ResultHelper::json(400, 'code 参数不能为空');
        }
        $wechat = Yii::$app->wechat->app;
        $user   = $wechat->oauth->user()->toArray();
        if (empty($user)) {
            return ResultHelper::json(400, '用户信息获取失败', $user);
        } else {
            FileHelper::writeLog($logPath, '用户信息获取成功');
            // "id": "ogEDnjlsqUJJbT1KNB36QuVVyji8",
            // "name": "王春生",
            // "nickname": "王春生",
            // "avatar": "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLUDricS0ibwcYGtp3LU9uvHREQtobWk95QLf4IHbzTCyrkniacDIrYXHhwiaQe7UXYqeNjNlZcEW7sfQ/132",
            // "email": null,
            // "original": {
            // "openid": "ogEDnjlsqUJJbT1KNB36QuVVyji8",
            // "nickname": "王春生",
            // "sex": 1,
            // "language": "zh_CN",
            // "city": "西安",
            // "province": "陕西",
            // "country": "中国",
            // "headimgurl": "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLUDricS0ibwcYGtp3LU9uvHREQtobWk95QLf4IHbzTCyrkniacDIrYXHhwiaQe7UXYqeNjNlZcEW7sfQ/132",
            // "privilege": []
            // },
            // "token": "37_aw6o8m4VAFCLsKFU2fUN00h9Ao3jVs6ZeOdagfG9n05cbgo0c8PeAZppGt1KIZ66weFy_hSO4RSQFvpSa5RW4A",
            // "provider": "WeChat"
            $user['avatarUrl'] = $user['avatar'];
            $user['gender']    = !empty($user['gender']) ? $user['gender'] : 0;
            $user['openid']    = $user['original']['openid'];
            $user['country']   = $user['original']['country'];
            $user['city']      = $user['original']['city'];
            $user['province']  = $user['original']['province'];

            $Res = Yii::$app->fans->signup($user);

            return ResultHelper::json(200, '用户信息获取成功', $Res);
        }
    }

    public function actionAuthUrl(): array
    {
        $officialaccount = Yii::$app->officialaccount->getApp(1);
        $callback        = Yii::$app->request->input('redirect_url');
        loggingHelper::writeLog('officialaccount', 'actionAuthUrl', '获取授权地址', [
            'callback' => $callback,
        ]);
//        $url = $officialaccount->handleAuthorize($callback); // 传入回调URI即可
        $redirect_uri = Yii::$app->request->input('redirect_url');
        $authCode     = Yii::$app->request->input('auth_code');
        $Res          = $officialaccount->oauth->scopes(['snsapi_userinfo'])->redirect();
        return ResultHelper::json(200, '获取成功', ['url' => $Res]);
    }

    public function actionAuthWechat(): array
    {
        $authCode = Yii::$app->request->input('auth_code');
        $Res      = OpenWechatAuthService::authInfoSave($authCode);
        return ResultHelper::json(200, '获取成功', $Res);
    }


    // app支付参数获取
    public function actionPayappparameters(): array
    {
        try {

            $data = Yii::$app->request->post();
            // 生成订单
            $orderData = [
                'spbill_create_ip' => Yii::$app->request->userIP,
                'fee_type' => 'CNY',
                'body' => StringHelper::msubstr($data['body'], 0, 10), // 内容
                'out_trade_no' => $data['out_trade_no'], // 订单号
                'total_fee' => $data['total_fee'] * 100,
                'trade_type' => $data['trade_type'], //支付类型
                'notify_url' => Yii::$app->params['wechatPaymentConfig']['notify_url'], // 回调地址
                // 'open_id' => 'okFAZ0-',  //JS支付必填
                // 'auth_code' => 'ojPztwJ5bRWRt_Ipg',  刷卡支付必填
            ];
            $logPath = Yii::getAlias('@runtime/wechat/payparameters' . date('ymd') . '.log');
            FileHelper::writeLog($logPath, '订单数据' . json_encode(ArrayHelper::toArray($orderData)));

            // 生成支付配置
            $payment = Yii::$app->wechat->payment;
            // return $payment->order;
            $result = $payment->order->unify($orderData);
            if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                $prepayId = $result['prepay_id'];
                $config = $payment->jssdk->appConfig($prepayId);

                return ResultHelper::json(200, '支付参数获取成功', $config);
            } else {
                FileHelper::writeLog($logPath, 'app支付参数获取错误' . json_encode($result));

                return ResultHelper::json(401, '功能暂未开放，请联系管理员', $result);
            }
        }catch (Exception $e){
            FileHelper::writeLog($logPath, 'app支付参数获取错误' . json_encode($e));
            return ResultHelper::json(401, '功能暂未开放，请联系管理员',[
                'msg'=>$e->getMessage(),
                'file'=>$e->getFile(),
                'line'=>$e->getLine()
            ]);
        }
    }

    public function actionSignup(): array
    {
        $code = Yii::$app->request->input('code');
        loggingHelper::writeLog('officialaccount', 'actionSignup', '授权登录', [
            'code' => $code,
        ]);
        if (empty($code)) {
            return ResultHelper::json(400, 'CODE 是必须的！');
        }

        try {
            $app   = Yii::$app->officialaccount->getApp(1);
            $oauth = $app->oauth;
            $user  = $oauth->userFromCode($code);
            loggingHelper::writeLog('officialaccount', 'actionSignup', '获取用户信息', [
                'userinfo' => $user,
            ]);
            if ($user->getId()) {
                $userInfo = $app->user->get($user->getId());
                loggingHelper::writeLog('officialaccount', 'actionSignup', '获取用户详细信息', [
                    'userInfo' => $userInfo['unionid'],
                ]);
                $adminUser = User::find()->where(['open_id' => $user->getId()])->one();
                if ($adminUser) {
                    $service            = Yii::$app->service;
                    $service->namespace = 'admin';
                    $userinfo           = $service->AccessTokenService->getAccessToken($adminUser, 1);
                    loggingHelper::writeLog('officialaccount', 'actionSignup', '存在就登录成功', [
                        'userinfo' => $userinfo,
                    ]);
                    return ResultHelper::json(200, '登录成功！', $userinfo);
                } else {
                    $adminUser           = new User();
                    $maxId               = User::find()->max('id');
                    $adminUser->open_id  = $user->getId();
                    $adminUser->union_id = $userInfo['unionid'] ?? null;
                    /**
                     * 微信注册手机号码注册为空，后续要求绑定
                     */
                    $res = $adminUser->signup($maxId + 1, '', ($maxId + 1) . '@cn.com', '123465', 1);
                    loggingHelper::writeLog('officialaccount', 'actionSignup', '不存在就注册成功', [
                        'res' => $res,
                    ]);
                    return ResultHelper::json(200, '注册成功', (array) $res);
                }
            } else {
                return ResultHelper::json(400, '获取微信用户失败！', ['user' => $user]);
            }
        } catch (\Exception $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
    }

    public function actionTick(): void
    {
        $data = Yii::$app->request->input();
        loggingHelper::writeLog('WechatController', 'tick', '服务器消息处理', $data);
    }

    public function actionBind(): array
    {
        $code = Yii::$app->request->post('code');
        if ($code) {
            $configPath = Yii::getAlias('@common/config/wechat.php');
            $config     = [];
            if (file_exists($configPath)) {
                $config = require_once $configPath;
            }
            try {
                Yii::$app->params['wechatConfig'] = $config;
                $app                              = Yii::$app->wechat->app;
                $oauth                            = $app->oauth;
                $user                             = $oauth->user();
            } catch (\Exception $e) {
                return ResultHelper::json(400, $e->getMessage());
            }
            if ($user->id) {
                $adminUser = User::find()->where(['open_id' => $user->id])->one();
                if ($adminUser) {
                    return ResultHelper::json(400, '当前微信已绑定用户！');
                } else {
                    $adminUser = User::find()->where(['id' => Yii::$app->user->identity->user_id])->one();
                    if ($adminUser) {
                        $adminUser->open_id  = $user->id;
                        $adminUser->union_id = $user->getOriginal()['unionid'] ?? null;
                        if ($adminUser->save(false)) {
                            return ResultHelper::json(200, '绑定成功！');
                        }
                    }
                    ResultHelper::json(400, '绑定失败！');
                }
            } else {
                ResultHelper::json(400, '获取微信用户失败！');
            }
        } else {
            ResultHelper::json(400, 'CODE 是必须的！');
        }
        return ResultHelper::json(200, '绑定成功！');

    }

    public function actionUnbind(): array
    {
        $user_id = Yii::$app->user->id??0;
        $adminUser = DdUser::find()->where(['id' => $user_id])->one();
        if ($adminUser) {
            $adminUser->open_id  = null;
            $adminUser->union_id = null;
            if ($adminUser->save(false)) {
                return ResultHelper::json(200, '解除绑定成功！');
            } else {
                return ResultHelper::json(400, '解除绑定失败！');
            }
        } else {
            return ResultHelper::json(400, '无效的用户信息！');
        }
    }
}
