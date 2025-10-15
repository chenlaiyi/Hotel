<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-16 11:38:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-17 10:12:49
 */


namespace common\plugins\diandi_auth\admin\app;

use admin\controllers\AController;
use admin\services\AuthService;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\plugins\diandi_auth\models\BlocConfAppNav;
use Yii;

class AppController extends AController
{

    protected array $authOptional = ['nav'];

    /**
     * app导航
     */
    public function actionNav()
    {
        $user_id = Yii::$app->user->id;
        $lists = AuthService::getAppNavigationPermission($user_id);
        return ResultHelper::json(200, '请求成功', $lists);
    }
}
