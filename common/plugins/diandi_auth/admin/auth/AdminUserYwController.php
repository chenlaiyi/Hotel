<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 08:31:51
 */


namespace common\plugins\diandi_auth\admin\auth;

use admin\controllers\auth\AdminUserController;
use admin\models\searchs\adminUser;
use common\models\User;
use admin\services\UserService;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * AdminUserController implements the CRUD actions for User model.
 */
class AdminUserYwController extends AdminUserController
{
    public string $modelSearchName = 'adminUser';
    public int    $searchLevel     = 1;

    function actionAuthLink()
    {
        $user_id  = Yii::$app->request->input('user_id', 0);
        $link_ids = Yii::$app->request->input('link_ids', []);
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
        $Res     = UserService::getAuthUserLinkInfo($user_id);
        return ResultHelper::json(200, '获取成功', $Res);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $model          = new User();
        $data           = Yii::$app->request->post();
        $data['is_sys'] = 0;
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Lists all User models.
     * 普通用户查询
     * 总管理员查询
     * 业务员查询
     * 根据公司查
     * 根据商户查
     * 根据自己的公司权限查询
     * 根据自己的商户权限查询
     * 根据自己的部分权限查询
     * 根据自己授权的用户集合查询
     * @return array
     * @throws \Exception
     */
    public function actionIndex(): array
    {
        $searchModel  = new adminUser();
        $dataProvider = $searchModel->searchyw(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 个人授权查询
     *
     * @return array
     * @throws \Exception
     */
    public function actionUser(): array
    {
        $searchModel  = new adminUser();
        $dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
