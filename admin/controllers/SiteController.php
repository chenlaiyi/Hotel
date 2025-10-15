<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-02 21:40:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:08:13
 */

namespace admin\controllers;

use admin\models\forms\LoginForm;
use admin\models\forms\PasswordResetRequestForm as FormsPasswordResetRequestForm;
use admin\models\forms\ResendVerificationEmailForm;
use admin\models\forms\ResetPasswordForm;
use admin\models\forms\SignupForm;
use admin\models\forms\VerifyEmailForm;
use admin\services\UserService;
use common\helpers\ErrorsHelper;
use common\helpers\MapHelper;
use common\helpers\ResultHelper;
use common\models\User;
use diandi\admin\acmodels\AuthItem;
use diandi\admin\acmodels\AuthRoute;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controllers.
 */
class SiteController extends AController
{
    public $modelClass = '';

    protected array $authOptional = ['login', 'logout', 'error', 'signup', 'request-password-reset', 'setpassword', 'relations'];
    public int $searchLevel = 0;

    /**
     * Displays homepage.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        return ResultHelper::json(200, '获取成功');
    }

    /**
     * Render admin portal login page.
     *
     * @throws NotFoundHttpException
     */
    public function actionLogin(): string
    {
        $file = Yii::getAlias('@public/dd-admin/dist/backend/pro-admin/index.html');
        if (!is_file($file)) {
            throw new NotFoundHttpException('后台登录页面不存在');
        }
        Yii::$app->response->detachBehavior('beforeSend');
        Yii::$app->response->format = Response::FORMAT_HTML;
        Yii::$app->response->headers->set('Content-Type', 'text/html; charset=UTF-8');

        return file_get_contents($file);
    }


    public function actionLogout(): array
    {
        $user_id = Yii::$app->user->identity->id??0;
        if (empty($user_id)){
            return ResultHelper::json(200, '退出成功');
        }
        User::updateAll([
            'is_login' => 0,
        ], ['id' => $user_id]);
        UserService::deleteUserCache($user_id);

        Yii::$app->user->logout();
        return ResultHelper::json(200, '退出成功', [
            'url' => Url::to(['site/login']),
        ]);
    }

    public function actionSignup(): array
   {
        $model = new SignupForm();
        $data = [
            'username' =>\Yii::$app->request->input('username'),
            'email' =>\Yii::$app->request->input('email'),
            'password' =>\Yii::$app->request->input('password'),
        ];
        // p($model->load(Yii::$App->request->post()),$model->signup());
        if ($model->load($data, '') && $model->signup()) {
            return ResultHelper::json(200, '感谢您的注册，请验证您的邮箱');
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, '注册失败', (array)$msg);
        }
    }




    public function actionXiufu(): void
   {
        if (Yii::$app->request->input('type') == 1) {
            $AuthRoute = new AuthRoute();
            $list = AuthRoute::find()->alias('a')->leftJoin(AuthItem::tableName().' as c',
                'a.route_name=c.name'
            )->select(['a.id', 'c.id as item_id'])->asArray()->all();

            foreach ($list as $key => $value) {
                $_AuthRoute = clone $AuthRoute;
                $_AuthRoute->updateAll([
                    'item_id' => $value['item_id'],
                ], [
                    'id' => $value['id'],
                ]);
            }
        } elseif (Yii::$app->request->input('type') == 2) {
            $authItem = new AuthItem();

            $AuthRoute = AuthRoute::find()->asArray()->all();

            foreach ($AuthRoute as $key => $value) {
                $_authItem = clone $authItem;
                $_authItem->setAttributes([
                    'name' => $value['route_name'],
                    'is_sys' => $value['is_sys'],
                    'permission_type' => 0,
                    'description' => $value['description'],
                    'parent_id' => 0,
                    'permission_level' => $value['route_type'],
                    'data' => $value['data'],
                    'module_name' => $value['module_name'],
                ]);
                $_authItem->save();
                $msg = ErrorsHelper::getModelError($_authItem);
                if (!empty($msg)) {
                    echo '<pre>';
                    print_r($msg);
                    echo '</pre>';
                }
            }
        } elseif (Yii::$app->request->input('type') == 3) {
            $AuthRoute = new AuthRoute();
            $list = AuthRoute::find()->where(['=', 'item_id', null])->asArray()->all();

            foreach ($list as $key => $value) {
                $_AuthRoute = clone $AuthRoute;
                $_AuthRoute->updateAll([
                    'route_name' => $value['name'],
                ], [
                    'id' => $value['id'],
                ]);
            }
        }
    }
}
