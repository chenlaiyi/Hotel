<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 14:40:17
 */


namespace admin\controllers\auth;

use admin\models\searchs\adminUser;
use admin\services\UserService;
use common\helpers\ResultHelper;
use Yii;

/**
 * AdminUserController implements the CRUD actions for User model.
 */
class AdminUserYwController extends AdminUserController
{
    public string $modelSearchName = 'adminUser';

    public int $searchLevel = 2;

    function actionAuthLink()
    {
        $user_id = Yii::$app->request->input('user_id', 0);
        $link_ids =  Yii::$app->request->input('link_ids', []);
        if (empty($link_ids)) {
            return ResultHelper::json(500, '请选择要关联的用户：link_ids');
        }

        if (!is_array($link_ids)) {
            return ResultHelper::json(500, '关联的用户数据必须是字符串');
        }
        $Res = UserService::authUserLink($user_id, $link_ids);
        return ResultHelper::json(200, '授权成功', $Res);
    }

    function actionLink()
    {
        $user_id = Yii::$app->request->input('user_id', 0);
        $Res = UserService::getAuthUserLinkInfo($user_id);
        return ResultHelper::json(200, '获取成功', $Res);
    }

    /**
     * Lists all User models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new adminUser();
        $dataProvider = $searchModel->searchyw(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 个人授权查询
     * @return array
     */
    public function actionUser(): array
    {
        $searchModel = new adminUser();
        $dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
