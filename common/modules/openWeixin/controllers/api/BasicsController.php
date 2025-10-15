<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-09 01:32:28
 * @Last Modified by:   Radish <minradish@163.com>
 * @Last Modified time: 2022-10-14 15:19:53
 */

namespace common\modules\openWeixin\controllers\api;

use common\models\User;
use api\controllers\AController;
use common\helpers\FileHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\UrlHelper;
use Yii;

/**
 * Default controller for the `WeChat` module.
 */
class BasicsController extends AController
{
    protected array $authOptional = ['signup', 'auth', 'userinfo', 'auth-url'];

    public $modelClass = 'api\modules\officialaccount\models\DdWechatFans';

    public function actionAuth(): array
    {
        $redirect_uri = Yii::$app->request->input('redirect_uri');
        $authCode = Yii::$app->request->input('authCode');
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
        $user = $wechat->oauth->user()->toArray();
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
            $user['gender'] = !empty($user['gender']) ? $user['gender'] : 0;
            $user['openid'] = $user['original']['openid'];
            $user['country'] = $user['original']['country'];
            $user['city'] = $user['original']['city'];
            $user['province'] = $user['original']['province'];

            $Res = Yii::$app->fans->signup($user);

            return ResultHelper::json(200, '用户信息获取成功', $Res);
        }
    }

    public function actionAuthUrl(): array
    {
        $openPlatform = Yii::$app->wechat->openPlatform;
        $callback = UrlHelper::adminUrl('wechat', 'signup', [
            'bloc_id' => Yii::$app->request->input('bloc_id', 0),
            'store_id' => Yii::$app->request->input('store_id', 0),
        ]);
        $url = $openPlatform->getPreAuthorizationUrl($callback); // 传入回调URI即可
        return ResultHelper::json(200, '获取成功', ['url' => $url]);
    }

    public function actionSignup(): array
    {
        $code = Yii::$app->request->post('code');
        if ($code) {
            $configPath = Yii::getAlias('@common/config/wechat.php');
            $config = [];
            if (file_exists($configPath)) {
                $config = require_once $configPath;
            }
            try {
                Yii::$app->params['wechatConfig'] = $config;
                $app = Yii::$app->wechat->app;
                $oauth = $app->oauth;
                $user = $oauth->user();
            } catch (\Exception $e) {
                return ResultHelper::json(400, $e->getMessage());
            }
            if ($user->id) {
                $adminUser = User::find()->where(['open_id' => $user->id])->one();
                if ($adminUser) {
                    $service = Yii::$app->service;
                    $service->namespace = 'admin';
                    $userinfo = $service->AccessTokenService->getAccessToken($adminUser, 1);

                    return ResultHelper::json(200, '登录成功！', $userinfo);
                } else {
                    $adminUser = new User();
                    $maxId = User::find()->max('id');
                    $adminUser->open_id = $user->id;
                    $adminUser->union_id = $user->getOriginal()['unionid'] ?? null;
                    $res = $adminUser->signup($maxId + 1, $maxId + 1, ($maxId + 1) . '@cn.com', '123465', 1);

                    return ResultHelper::json(200, '注册成功', (array)$res);
                }
            } else {
                ResultHelper::json(400, '获取微信用户失败！');
            }
        } else {
            ResultHelper::json(400, 'CODE 是必须的！');
        }
        return ResultHelper::json(200, '注册成功');

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
            $config = [];
            if (file_exists($configPath)) {
                $config = require_once $configPath;
            }
            try {
                Yii::$app->params['wechatConfig'] = $config;
                $app = Yii::$app->wechat->app;
                $oauth = $app->oauth;
                $user = $oauth->user();
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
                        $adminUser->open_id = $user->id;
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
        $adminUser = User::find()->where(['id' => Yii::$app->user->identity->user_id])->one();
        if ($adminUser) {
            $adminUser->open_id = null;
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
