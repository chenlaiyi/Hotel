<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-07-10 08:29:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 22:19:07
 */


namespace common\middlewares;

use Yii;
use yii\base\BootstrapInterface;

class UserLoader  implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->on(\yii\web\Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->user->enableSession = false;
        });

        $app_id = $app->id;
        if (in_array($app_id, ['app-admin'])) {
            $access_token = Yii::$app->request->headers->get('access-token', 0);
            if (empty($access_token)) {
                $access_token = Yii::$app->request->input('access-token', '');
            }
            $arr = explode('_', $access_token);
            $userType = $arr[0] ?? 'admin';
            if ($userType == 'admin') {
                Yii::$app->setComponents([
                    'user' => [
                        'class' => 'yii\web\User',
                        'identityClass' => 'admin\models\DdApiAccessToken',
                        'enableAutoLogin' => true,
                        'enableSession' => true,
                        'loginUrl' => null,
                        'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true]
                    ],
                ]);
                Yii::$container->set('diandi\admin\components\Configs', [
                    'userTable' => '{{%user}}',
                ]);
            } elseif ($userType == 'customer') {
                Yii::$app->setComponents([
                    'user' => [
                        'class' => 'yii\web\User',
                        'identityClass' => 'admin\modules\customer\models\DdApiAccessToken',
                        'enableAutoLogin' => true,
                        'enableSession' => true,
                        'loginUrl' => null,
                        'identityCookie' => ['name' => '_identity-customer-api', 'httpOnly' => true]
                    ]
                ]);

                Yii::$container->set('diandi\admin\components\Configs', [
                    'userTable' => '{{%customer_user}}',
                ]);
            }
        }
    }
}
