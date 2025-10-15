<?php

namespace common\modules\officialaccount\controllers\admin;

use Yii;
use common\modules\officialaccount\models\OfficialaccountTemplateMsgLog;
    use common\modules\officialaccount\models\searchs\OfficialaccountTemplateMsgLog as OfficialaccountTemplateMsgLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;

/**
* MsgLogController implements the CRUD actions for OfficialaccountTemplateMsgLog model.
*/
class MsgLogController extends AController
{
public string $modelSearchName = "OfficialaccountTemplateMsgLogSearch";

public $modelClass = '';


/**
* Lists all OfficialaccountTemplateMsgLog models.
* @return array
*/
public function actionIndex(): array
{
    $searchModel = new OfficialaccountTemplateMsgLogSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return ResultHelper::json(200, '获取成功',[
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'modelSearchName'=>$this->modelSearchName,
    'field' => $searchModel->attributeLabels()
    ]);
}

/**
* Displays a single OfficialaccountTemplateMsgLog model.
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
* Creates a new OfficialaccountTemplateMsgLog model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return array
*/
public function actionCreate(): array
{
    $model = new OfficialaccountTemplateMsgLog();
    $data = Yii::$app->request->post();
    if ($model->load($data, '') && $model->save()) {
        return ResultHelper::json(200, '创建成功', $model->toArray());
    } else {
        $msg = ErrorsHelper::getModelError($model);
        return ResultHelper::json(400, $msg);
    }
}

/**
* Updates an existing OfficialaccountTemplateMsgLog model.
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
* Finds the OfficialaccountTemplateMsgLog model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return array|ActiveRecord the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel($id): array|ActiveRecord
{
if (($model = OfficialaccountTemplateMsgLog::findOne($id)) !== null) {
return $model;
}

throw new NotFoundHttpException('The requested page does not exist.');
}
}