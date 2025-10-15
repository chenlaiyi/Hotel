<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:32:04
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:01:47
 */
namespace addons\diandi_place\admin\place;
use addons\diandi_place\models\place\PlaceServer;
use addons\diandi_place\models\searchs\hotel\PlaceServer as PlaceServerSearch;
use addons\diandi_place\services\PlaceService;
use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * ServerController implements the CRUD actions for PlaceServer model.
 */
class ServerController extends AController
{
    public string $modelSearchName = "HotelServer";
    public $modelClass = '';
    // 根据公司检索字段,不参与检索设置为false
    public string $blocField = '';
    // 根据商户检索字段,不参与检索设置为false
    public string $storeField = '';
    /**
     * Lists all PlaceServer models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceServerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceServer model.
     * @param integer $id
     * @return array
     */
    public function actionView($id): array
    {
        try {
            $view = $this->findModel($id)->toArray();
        } catch (NotFoundHttpException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
        return ResultHelper::json(200, '获取成功', $view);
    }
    /**
     * Creates a new PlaceServer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceServer();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceServer model.
     * If the update is successful, the browser will be redirected to the 'view' page.
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
    public function actionList(): array
    {
        $bloc_id = \Yii::$app->request->input('f_bloc_id');
        $list = PlaceService::getServers($bloc_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * Deletes an existing PlaceServer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array|object[]|string[]
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): array
    {
        $this->findModel($id)->delete();
        return ResultHelper::json(200, '删除成功');
    }
    /**
     * Finds the PlaceServer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        if (($model = PlaceServer::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
