<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\modules\officialaccount\models\OfficialaccountQrcode;
use common\modules\officialaccount\models\searchs\OfficialaccountQrcode as OfficialaccountQrcodeSearch;
use Throwable;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * QrcodeController implements the CRUD actions for OfficialaccountQrcode model.
 */
class QrcodeController extends AController
{
    public string $modelSearchName = "OfficialaccountQrcodeSearch";

    public $modelClass = '';


    /**
     * Lists all OfficialaccountQrcode models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new OfficialaccountQrcodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single OfficialaccountQrcode model.
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
     * Creates a new OfficialaccountQrcode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new OfficialaccountQrcode();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing OfficialaccountQrcode model.
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

    public function actionDeletes(): array
    {
        $ids = Yii::$app->request->input('ids');
        if (OfficialaccountQrcode::deleteAll(['id'=>$ids])){
            return ResultHelper::json(200, '删除成功');
        }
        return ResultHelper::json(400, '删除失败');
    }

    /**
     * Finds the OfficialaccountQrcode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = OfficialaccountQrcode::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}