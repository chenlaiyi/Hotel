<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:01:33
 */
namespace addons\diandi_place\admin\place;
use addons\diandi_place\models\place\PlaceConfig;
use addons\diandi_place\models\searchs\hotel\PlaceConfigSearch;
use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * ConfigController implements the CRUD actions for PlaceConfig model.
 */
class ConfigController extends AController
{
    public string $modelSearchName = "HotelConfigSearch";
    public $modelClass = '';
    // 根据公司检索字段,不参与检索设置为false
    public string $blocField = '';
    // 根据商户检索字段,不参与检索设置为false
    public string $storeField = '';
    /**
     * Lists all PlaceConfig models.
     * @return array|object[]|string[]
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceConfig model.
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
     * Creates a new PlaceConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceConfig();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceConfig model.
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
    /**
     * Displays a single HotelConfig model.
     * @param $store_id
     * @return array
     */
    public function actionBloc($store_id): array
    {
        $view = PlaceConfig::find()->where(['store_id'=>$store_id])->findStores()->one();
        return ResultHelper::json(200, '获取成功', ['view'=>$view?->toArray()]);
    }
    public function actionBlocSet(): array
    {
        $model = new PlaceConfig();
        $data = Yii::$app->request->post();
        $where = [];
        list($bloc_id,$store_id)  = Yii::$app->request->input('blocs', []);
        if ($bloc_id){
            $where['bloc_id'] = $bloc_id;
        }
        if ($store_id){
            $where['store_id'] = $store_id;
        }
        $oldModel = $model->find()->where($where)->findStores()->one();
        if ($oldModel) {
            unset($data['blocs']);
            $oldModel->setAttributes($data);
            $oldModel->save();
//            TeaGlobalConfig::updateAll($data, ['id' => $id]);
            return ResultHelper::json(200, '编辑成功',$oldModel->toArray());
        } else {
            if ($model->load($data, '') && $model->save()) {
                return ResultHelper::json(200, '创建成功', $model->toArray());
            } else {
                $msg = ErrorsHelper::getModelError($model);
                return ResultHelper::json(400, $msg);
            }
        }
    }
    /**
     * Deletes an existing PlaceConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array
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
     * Finds the PlaceConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        if (($model = PlaceConfig::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
