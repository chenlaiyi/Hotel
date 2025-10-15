<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 14:22:22
 */



namespace common\plugins\diandi_auth\admin;


use admin\services\AuthService;
use common\plugins\diandi_auth\models\MemberRolePermission;
use admin\models\enums\AddonsType;
use common\helpers\ArrayHelper;
use common\helpers\ImageHelper;
use diandi\addons\models\AddonsUser;
use Yii;
use common\plugins\diandi_auth\models\MemberPermission;
use common\plugins\diandi_auth\models\searchs\MemberPermission as ZyjMemberPermissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;


/**
 * PermissionController implements the CRUD actions for ZyjMemberPermission model.
 */
class PermissionController extends AController
{

    public string $modelSearchName = "MemberPermission";


    public $modelClass = '';


    /**
     * Lists all ZyjMemberPermission models.
     * @return array
     */

    public function actionIndex(): array
    {

        $searchModel = new ZyjMemberPermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $list = ZyjMemberPermissionSearch::find()->asArray()->all();
        $lists = ArrayHelper::itemsMerge($list, 0, 'id', 'pid', 'children');

        return ResultHelper::json(200, '获取成功', [
//            'list' => $lists,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }


    /**
     * Displays a single ZyjMemberPermission model.
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
     * Creates a new ZyjMemberPermission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     * @throws NotFoundHttpException
     */

    public function actionCreate(): array
    {
        $model = new MemberPermission();
        $data = Yii::$app->request->post();

        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }


    /**
     * Updates an existing ZyjMemberPermission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $nav_id = Yii::$app->request->post('nav_id', 0);
        if ($model->load($data, '') && $model->save()) {
            if ($nav_id){
                /**
                 * 更新子集都是这个导航的
                 */
                MemberPermission::updateAll(['nav_id' => $nav_id], ['pid' => $id]);
            }
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * 获取父级权限
     * @return array|object[]|string[]
     */
    function actionList()
    {
        $addons = Yii::$app->request->get('addons', '');
        $where = [];
        if (!empty($addons)) {
            $where = ['addons' => $addons];
        }
        $model = MemberPermission::find()->where(['pid' => 0])->andFilterWhere($where)->select(['id', 'id as value', 'name', 'name as label', 'name as text', 'page_name'])->asArray()->all();
        array_walk($model, function (&$item) {
            $item['name'] = $item['name'] . '(' . $item['page_name'] . ')';
            $item['label'] =   $item['name'] . '(' . $item['page_name'] . ')';
        });
        return ResultHelper::json(200, '获取成功', $model);
    }


    function actionAddons()
    {
        $AddonsUser = new AddonsUser();
        $roleId = Yii::$app->request->get('roleId', 0);
        $user_id = Yii::$app->user->id ?? 0;
        $list =  AuthService::getUserPluginPermission($user_id);

        $is_sys = Yii::$app->request->get('is_sys', 0);
        $authList = [];
        $addonsList = [];
        $defaultCheckedKeys = [];
        foreach ($list as $item) {
            $addons = $item['identifie'];
            $addonsList[$addons] = [
                'id' => $item['mid'],
                'value' => $item['identifie'],
                'name' => $item['title'],
                'label' => $item['title'],
                'text' => $item['title']
            ];

            $authList[$addons] = MemberPermission::find()
                ->select(['page_name', 'id', 'id as value', 'name', 'name as label', 'name as text', 'addons', 'pid'])
                ->where([
                    'addons' => $addons
                ])->asArray()->all();

            $permissionIds = array_column($authList[$addons], 'id');
            $defaultCheckedKeys[$addons] = MemberRolePermission::find()->where([
                'permissionId' => $permissionIds,
                'roleId' => $roleId,
                'is_sys' => $is_sys
            ])->select('permissionId')->column();
        }
        $lists = [];

        foreach ($authList as $addons => $item) {
            $lists[$addons] = [
                'id' => $addonsList[$addons]['id'],
                'value' => $addonsList[$addons]['value'],
                'name' => $addonsList[$addons]['name'],
                'label' => $addonsList[$addons]['label'],
                'text' => $addonsList[$addons]['text'],
                'defaultCheckedKeys' => $defaultCheckedKeys[$addons],
                'permissions' => ArrayHelper::itemsMerge($item, 0, 'id', 'pid', 'children')
            ];
        }

        return ResultHelper::json(200, '获取成功', $lists);
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
     * Finds the ZyjMemberPermission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id): array|ActiveRecord

    {

        if (($model = MemberPermission::findOne($id)) !== null) {

            return $model;
        }


        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
