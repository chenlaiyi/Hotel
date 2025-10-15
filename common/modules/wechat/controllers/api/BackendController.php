<?php
namespace common\modules\wechat\controllers\api;

use admin\models\User;
use admin\services\AuthService;
use api\controllers\AController;
use common\helpers\ArrayHelper;
use common\helpers\MapHelper;
use common\helpers\ResultHelper;
use Yii;

class BackendController extends AController
{
    protected array $authOptional = ['fast-login', 'union-login'];

    /**
     * @author Michael Liang
     * @email  Liang15946@163.com
     * @date   2025-07-03
     * @desc   通过手机号及union_id完成登录
     * @return [type]             [description]
     */
    public function actionFastLogin()
    {
        $union_id = Yii::$app->request->input('union_id');
        $mobile   = Yii::$app->request->input('mobile');

        if (empty($union_id)) {
            return ResultHelper::json(400, 'union_id 不能为空');
        }

        if (empty($mobile)) {
            return ResultHelper::json(400, 'mobile 不能为空');
        }

        $user = User::findByMobile($mobile);

        if (empty($user)) {
            return ResultHelper::json(400, $mobile . ' 账号不存在，请联系管理员！');
        }

        $last_login_ip        = MapHelper::get_client_ip();
        $password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

        $user->last_time            = time();
        $user->is_login             = 1;
        $user->last_login_ip        = $last_login_ip;
        $user->password_reset_token = $password_reset_token;
        $user->union_id             = $union_id;
        $user->save();

        $union_users = User::find()->where([
            'union_id' => $union_id,
        ])->all();

        if ($union_users) {
            foreach ($union_users as $item) {
                if ($item->id != $user->id) {
                    $item->union_id = '';
                    $item->save();
                }
            }
        }

        $service            = Yii::$app->service;
        $service->namespace = 'admin';
        $userinfo           = $service->AccessTokenService->getAccessToken($user, 1);

        $result = ArrayHelper::toArray($userinfo);
        if ($result){
            $permission = AuthService::getUserAppPermission($user['id']);
            $result['permission'] = $permission;
        }
        return ResultHelper::json(200, '登录成功', $result);
    }

    /**
     * @author Michael Liang
     * @email  Liang15946@163.com
     * @date   2025-07-03
     * @desc   union_id 登录
     * @return [type]             [description]
     */
    public function actionUnionLogin()
    {
        $union_id = Yii::$app->request->input('union_id');

        if (empty($union_id)) {
            return ResultHelper::json(400, 'union_id 不能为空');
        }

        $user = User::find()->where([
            'union_id' => $union_id,
        ])->one();

        if (empty($user)) {
            return ResultHelper::json(400, '未关联账户');
        }

        $last_login_ip        = MapHelper::get_client_ip();
        $password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

        $user->last_time            = time();
        $user->is_login             = 1;
        $user->last_login_ip        = $last_login_ip;
        $user->password_reset_token = $password_reset_token;
        $user->union_id             = $union_id;
        $user->save();

        $union_users = User::find()->where([
            'union_id' => $union_id,
        ])->all();

        if ($union_users) {
            foreach ($union_users as $item) {
                if ($item->id != $user->id) {
                    $item->union_id = '';
                    $item->save();
                }
            }
        }

        $service            = Yii::$app->service;
        $service->namespace = 'admin';
        $userinfo           = $service->AccessTokenService->getAccessToken($user, 1);

        $result = ArrayHelper::toArray($userinfo);
        if ($result){
            $permission = AuthService::getUserAppPermission($user['id']);
            $result['permission'] = $permission;
        }
        return ResultHelper::json(200, '登录成功', $result);
    }
}
