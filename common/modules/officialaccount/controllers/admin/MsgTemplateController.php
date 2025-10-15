<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\modules\officialaccount\models\OfficialaccountMsgTemplate;
use common\modules\officialaccount\models\searchs\OfficialaccountMsgTemplate as OfficialaccountMsgTemplateSearch;
use Throwable;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * MsgTemplateController implements the CRUD actions for OfficialaccountMsgTemplate model.
 */
class MsgTemplateController extends AController
{
    public string $modelSearchName = "OfficialaccountMsgTemplateSearch";

    public $modelClass = '';


    /**
     * Lists all OfficialaccountMsgTemplate models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new OfficialaccountMsgTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single OfficialaccountMsgTemplate model.
     * @param string $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $view = $this->findModel($id);

        return ResultHelper::json(200, '获取成功', $view->toArray());
    }

    public function actionData(): array
    {
        $template_id = Yii::$app->request->input('template_id');
        $data = OfficialaccountMsgTemplate::find()->where(['template_id'=>$template_id])->select('content')->scalar();
        return ResultHelper::json(200, '获取成功',Json::decode($data));
    }

    /**
     * Creates a new OfficialaccountMsgTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new OfficialaccountMsgTemplate();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing OfficialaccountMsgTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Finds the OfficialaccountMsgTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = OfficialaccountMsgTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}