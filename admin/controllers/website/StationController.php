<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 20:12:53
 */


namespace admin\controllers\website;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\models\searchs\WebsiteStationGroup as WebsiteStationGroupSearch;
use common\models\WebsiteStationGroup;
use Throwable;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * StationController implements the CRUD actions for WebsiteStationGroup model.
 */
class StationController extends AController
{
    public string $modelSearchName = "WebsiteStationGroupSearch";

    public $modelClass = '';


    /**
     * Lists all WebsiteStationGroup models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new WebsiteStationGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single WebsiteStationGroup model.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $view = $this->findModel($id);

        return ResultHelper::json(200, '获取成功', $view->toArray());
    }

    public function actionDetail()
    {
        $bloc_id = Yii::$app->request->input('bloc_id');
        $data = WebsiteStationGroup::find()->where(['bloc_id' => $bloc_id])->asArray()->one();
        return ResultHelper::json(200, '获取成功', $data ?? []);
    }

    /**
     * Creates a new WebsiteStationGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new WebsiteStationGroup();
        $data = Yii::$app->request->post();
        $bloc_id = Yii::$app->request->input('bloc_id');
        if (!$model->load($data, '')) {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
        $have = $model::find()->where(['bloc_id' => $bloc_id])->one();
        if ($have) {
            $have->setAttributes($data);
            if (!$have->save(false)){
                $msg = ErrorsHelper::getModelError($model);
                return ResultHelper::json(400, $msg);
            }
            return ResultHelper::json(200, '配置成功',$have->toArray());
        }else{
            $model->bloc_id = $bloc_id;
            if (!$model->save(false)){
                $msg = ErrorsHelper::getModelError($model);
                return ResultHelper::json(400, $msg);
            }
            return ResultHelper::json(200, '配置成功', $model->toArray());

        }
    }

    /**
     * Updates an existing WebsiteStationGroup model.
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
     * Finds the WebsiteStationGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = WebsiteStationGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
