<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 10:25:38
 */
namespace addons\diandi_place\admin\place;
use addons\diandi_place\services\EnumService;
use Yii;
use addons\diandi_place\models\place\PlaceBrand;
use addons\diandi_place\models\searchs\hotel\PlaceBrandSearch;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
/**
 * BrandController implements the CRUD actions for PlaceBrand model.
 */
class BrandController extends AController
{
    public string $modelSearchName = "HotelBrandSearch";
    public $modelClass = '';
    // 根据公司检索字段,不参与检索设置为false
    public string $blocField = '';
    // 根据商户检索字段,不参与检索设置为false
    public string $storeField = '';
    public function actionEnums(): array
   {
        $list = EnumService::getEnums();
        return ResultHelper::json(200, '请求成功', $list);
    }
    /**
     * Lists all PlaceBrand models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceBrandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceBrand model.
     * @param integer $id
     * @return array|object[]|string[]
     */
    public function actionView($id): array
    {
        $view = PlaceBrand::find()->where(['id' => $id])->with(['bloc'])->asArray()->one();
        if ($view['bloc']) {
            $view = array_merge($view['bloc'], $view);
            // $view['category'] = [$view['category_pid'], $view['category_id']];
            $view['provinceCityDistrict'] = [
                (int)$view['province'],
                (int)$view['city'],
                (int)$view['district'],
            ];
            $view['address'] = [
                'address' => $view['address'],
                'lng' => $view['longitude'],
                'lat' => $view['latitude'],
            ];
            unset($view['bloc']);
        }
        return ResultHelper::json(200, '获取成功', $view);
    }
    /**
     * Creates a new PlaceBrand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceBrand();
        $data = Yii::$app->request->post();
        $data['title'] = Yii::$app->request->input('business_name','');
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceBrand model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $data['title'] = Yii::$app->request->input('business_name','');
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Deletes an existing PlaceBrand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
     * Finds the PlaceBrand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        if (($model = PlaceBrand::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
