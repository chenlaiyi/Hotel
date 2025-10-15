<?php

namespace common\plugins\diandi_auth\admin\app;

use admin\controllers\AController;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\plugins\diandi_auth\models\AuthMemberRolePermission;
use common\plugins\diandi_auth\models\BlocConfAppNav;
use common\plugins\diandi_auth\models\searchs\BlocConfAppNav as BlocConfAppNavSearch;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * NavController implements the CRUD actions for BlocConfAppNav model.
 */
class NavController extends AController
{
    public string $modelSearchName = "BlocConfAppNavSearch";

    public $modelClass = '';


    /**
     * Lists all BlocConfAppNav models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new BlocConfAppNavSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single BlocConfAppNav model.
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
     * Creates a new BlocConfAppNav model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new BlocConfAppNav();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing BlocConfAppNav model.
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

    function actionList(): array
    {
        $role_id = Yii::$app->request->input('role_id');
        $is_sys = Yii::$app->request->input('is_sys');
        $defaultCheckedKeys = AuthMemberRolePermission::find()->where(['roleId' =>(int) $role_id,'is_sys' =>(int) $is_sys])->select('permissionId')->column();
        $nav = BlocConfAppNav::find()->findBloc()->with(['auth'])->select(['nav_remark','id','id as value','tab_name as text','tab_name as label','tab_name as title'])->asArray()->all();
        foreach ($nav as &$item) {
            if ($item['auth']){
                $item['children'] = ArrayHelper::itemsMerge($item['auth'],0,'id','pid','children');
            }
            $item['defaultCheckedKeys'] = $defaultCheckedKeys;
            unset($item['auth']);
        }
        return ResultHelper::json(200, '获取成功', $nav);
    }

    /**
     * Finds the BlocConfAppNav model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = BlocConfAppNav::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}