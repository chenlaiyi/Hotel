<?php

namespace common\plugins\diandi_auth\admin;

use admin\controllers\AController;
use admin\services\StoreService;
use admin\services\UserService;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\models\UserStore;
use common\plugins\diandi_auth\models\DiandiAuthDepartments;
use common\plugins\diandi_auth\models\searchs\DiandiAuthDepartments as DiandiAuthDepartmentsSearch;
use diandi\addons\models\BlocStore;
use diandi\admin\acmodels\AuthUserGroup;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * DepartmentsController implements the CRUD actions for DiandiAuthDepartments model.
 */
class DepartmentsController extends AController
{
    public string $modelSearchName = "DiandiAuthDepartments";

    public $modelClass = '';

    public int $searchLevel = 1;

    /**
     * Lists all DiandiAuthDepartments models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new DiandiAuthDepartmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $list = $dataProvider['allModels'];

        $lists = ArrayHelper::itemsMerge((array)$list, 0, 'id', 'department_pid', 'children');

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'list' => $lists,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single DiandiAuthDepartments model.
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
     * Creates a new DiandiAuthDepartments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $model = new DiandiAuthDepartments();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing DiandiAuthDepartments model.
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

    function actionLevel(): array
    {
        $store_id = Yii::$app->request->input('store_id', 0);
        $dd_store_id = Yii::$app->request->input('dd_store_id', $store_id);
        $where = [];
        if ($dd_store_id) {
            $where = ['store_id' => $dd_store_id];
        }
        $list = DiandiAuthDepartments::find()->findBloc()->andFilterWhere($where)->select(['*','department_name as name','department_name as label','department_name as text','id as value'])->asArray()->all();
        $lists = ArrayHelper::itemsMerge($list,0, 'id', 'department_pid', 'children');
        return ResultHelper::json(200, '获取成功', $lists??[]);
    }

    function actionGroup()
    {
        $is_sys = Yii::$app->request->input('is_sys', 0);
//        $is_bloc = Yii::$app->request->input('is_bloc', 0);
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $query = AuthUserGroup::find() ->select(['id', 'name', 'id as value', 'name as text', 'name as label'])
        ->where(['is_sys'=>$is_sys]);
        if ($bloc_id){
            $query->andWhere(['bloc_id' => $bloc_id]);
        }
        $defaultRoles = Yii::$app->authManager->defaultRoles;
        $query->orWhere(['name' => $defaultRoles]);

        $list = $query
            ->asArray()
            ->all();
        return ResultHelper::json(200, '获取成功', $list??[]);
    }

    function actionStoreLevel(): array
    {
        $user_id = Yii::$app->user->id;
        $isAll = Yii::$app->request->input('isAll', 0);
        if ($isAll){
            $authStore = StoreService::getAllStores();
        }else{
            $authStore = StoreService::getAuthStores($user_id);
        }

        $departments = DiandiAuthDepartments::find()
            ->select(['store_id','bloc_id','department_pid','department_name as name','department_name as label','department_name as text','id as item_id','id as value','id'])
            ->asArray()->all();
        $departmentsArr = [];
        foreach ($departments as $department) {
            $department['type'] = 'department';
            $departmentsArr[$department['store_id']][] = $department;
        }    
        foreach ($authStore as &$item) {
            $item['type'] = 'store';
            $list = $departmentsArr[$item['id']]??[];
            $lists = [];
            if ($list){
                $lists = ArrayHelper::itemsMerge($list,0, 'id', 'department_pid', 'children');
            }
            $item['children'] = $lists;
        }
        return ResultHelper::json(200, '获取成功', $authStore??[]);
    }

    function actionAuthList(): array
    {
        $id = Yii::$app->request->input('id');
        $UserStore = new UserStore();
        $lists =  UserStore::find()->where(['user_id' => $id])->asArray()->all();
        $arr = [];
        foreach ($lists as $list) {
            $arr[$list['store_id']]['store_id'] =  $list['store_id'];
            $arr[$list['store_id']]['bloc_id'] =  $list['bloc_id'];
            $arr[$list['store_id']]['department'][] = $list['department_id'];
        }
        return ResultHelper::json(200, '获取成功', array_values($arr));
    }

    function actionAuthData()
    {
        $id = Yii::$app->request->input('id');
        $items = Yii::$app->request->input('items',[]);
        $UserStore = new UserStore();
        $UserStore->deleteAll([
            'user_id' => $id
        ]);

        $UserStore = new UserStore();
        foreach ($items as $value) {
            $store_id = $value['store_id'];
            foreach ($value['department'] as $department_id) {
                $_UserStore = clone $UserStore;
                $data = [
                    'user_id' => $id,
                    'bloc_id' => $value['bloc_id'],
                    'department_id'=> $department_id,
                    'store_id' => $store_id,
                    'status' => 0,
                ];
                $_UserStore->setAttributes($data);
                if (!$_UserStore->save()) {
                    $msg = ErrorsHelper::getModelError($_UserStore);
                    throw new \Exception($msg);
                }
            }

        }

        return ResultHelper::json(200, '授权成功');
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
     * Finds the DiandiAuthDepartments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = DiandiAuthDepartments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}