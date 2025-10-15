<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 09:09:50
 */



namespace common\plugins\diandi_auth\admin;


use common\plugins\diandi_auth\models\MemberPermission;
use common\plugins\diandi_auth\models\MemberRolePermission;
use common\helpers\ArrayHelper;
use Yii;
use common\plugins\diandi_auth\models\MemberRole;
use common\plugins\diandi_auth\models\searchs\MemberRole as ZyjMemberRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use diandi\admin\acmodels\AuthUserGroup;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;


/**
 * MemberroleController implements the CRUD actions for ZyjMemberRole model.
 */
class MemberroleController extends AController
{

    public string $modelSearchName = "MemberRole";


    public $modelClass = 'common\plugins\diandi_auth\models\AuthMemberRole';


    /**
     * Lists all ZyjMemberRole models.
     * @return array
     */

    public function actionIndex(): array
    {
        $searchModel = new ZyjMemberRoleSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    public function actionGetOptions()
    {
        $search = new ZyjMemberRoleSearch();
        $result = $search->getOptions();
        return ResultHelper::json(200, '获取成功', $result);
    }


    /**
     * Displays a single ZyjMemberRole model.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {

        $view = $this->findModel($id);
        return ResultHelper::json(200, '获取成功', $view->toArray());
    }


    /**
     * Creates a new ZyjMemberRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     * @throws NotFoundHttpException
     */

    public function actionCreate(): array
    {

        $model = new MemberRole();

        $data = Yii::$app->request->post();

        if ($model->load($data, '') && $model->save()) {

            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {

            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * 保存角色权限
     * @return array
     * @throws NotFoundHttpException
     */
    function actionPermission(): array
    {
        //        roleId
        //        tag
        //        permissionId
        $roleId = Yii::$app->request->post('roleId');
        $permissionIds = Yii::$app->request->post('permissions', []);
        $plugin = Yii::$app->request->post('plugin');
        //        if (empty($roleId) || empty($permissionId)) {
        //            return ResultHelper::json(400, '参数错误');
        //        }
        $oldPermissionId = MemberRolePermission::find()->where([
            'roleId' => $roleId,
            'plugin' => $plugin
        ])->select('permissionId')->orderBy('permissionId')->column();

        $actionData = ArrayHelper::array2diff($oldPermissionId, $permissionIds);

        $MemberRolePermission = new MemberRolePermission();
        $MemberRolePermission::deleteAll(['roleId' => $roleId, 'plugin' => $plugin, 'permissionId' => $actionData['delete']]);
        $tags = MemberPermission::find()->indexBy('id')->select(['tag', 'id'])->all();
        foreach ($actionData['add'] as $permissionId) {
            $_MemberRolePermission = clone $MemberRolePermission;
            $_MemberRolePermission->setAttributes([
                'roleId' => $roleId,
                'plugin' => $plugin,
                'tag' => $tags[$permissionId]['tag'],
                'permissionId' => $permissionId
            ]);
            $_MemberRolePermission->save();
            $msg = ErrorsHelper::getModelError($_MemberRolePermission);
            if (!empty($msg)) {
                return ResultHelper::json(400, $msg);
            }
        }
        return ResultHelper::json(200, '授权成功');
    }

    function actionSavePermissions()
    {
        $paras = Yii::$app->request->input();
        $bloc_id = $paras['bloc_id'] ?? 0;
        $store_id = $paras['store_id'] ?? 0;
        $role_id = $paras['role_id'] ?? 0;
        $permissions = $paras['permissions'] ?? [];
        $is_sys = $paras['is_sys'] ?? 0;
        if ((int) $is_sys === 0) {
            $role = MemberRole::findOne($role_id);
        } else {
            $role = AuthUserGroup::findOne($role_id);
        }

        if ($role) {

            $model = new MemberRolePermission();
            $model->deleteAll([
                'is_sys' => $is_sys,
                'roleId' => $role_id
            ]);
            foreach ($permissions as $_id) {
                $permission = MemberPermission::findOne($_id);
                $_model = clone $model;
                if ($permission) {
                    $role_permission = [
                        'bloc_id' => $bloc_id,
                        'store_id' => $store_id,
                        'is_sys' => $is_sys,
                        'roleId' => $role_id,
                        'tag' => $permission->tag,
                        'plugin' => $permission->addons,
                        'permissionId' => $_id
                    ];

                    $_model->setAttributes($role_permission);

                    if (!$_model->save()) {
                        $msg = ErrorsHelper::getModelError($_model);
                        return ResultHelper::json(400, $msg);
                    }
                }
            }
            return ResultHelper::json(200, '授权成功');
        } else {
            return ResultHelper::json(200, '权限组不存在', [
                'is_sys' => $is_sys,
                'roleId' => $role_id
            ]);
        }
    }

    function actionList()
    {
        $spec = Yii::$app->request->input('spec');
        $list = MemberRole::find()->where(['spec' => (int) $spec])->select(['id', 'name', 'id as value', 'name as label', 'name as text'])->asArray()->all();
        return ResultHelper::json(200, '获取成功', $list);
    }


    /**
     * Updates an existing ZyjMemberRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id): array

    {

        $model = $this->findModel($id);

        $data = Yii::$app->request->post();

        if ($model->load($data, '') && $model->save()) {

            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {

            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }


    /**
     * Deletes an existing WeihExhibitionServiceProvider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */

    public function actionDelete($id): array

    {

        $this->findModel($id)->delete();


        return ResultHelper::json(200, '删除成功');
    }


    /**
     * Finds the ZyjMemberRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id): array|ActiveRecord

    {

        if (($model = MemberRole::findOne($id)) !== null) {

            return $model;
        }


        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
