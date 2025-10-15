<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:01:43
 */
namespace addons\diandi_place\admin\place;
use common\helpers\ImageHelper;
use Yii;
use addons\diandi_place\models\place\PlaceRim;
use addons\diandi_place\models\searchs\hotel\PlaceRimSearch;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
/**
 * RimController implements the CRUD actions for PlaceRoomRim model.
 */
class RimController extends AController
{
    public string $modelSearchName = "HotelRimSearch";
    public $modelClass = '';
    // 根据公司检索字段,不参与检索设置为false
    public string $blocField = '';
    // 根据商户检索字段,不参与检索设置为false
      public string $storeField = '';
    /**
     * Lists all PlaceRoomRim models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceRimSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceRoomRim model.
     * @param integer $id
     * @return array
     */
    public function actionView($id): array
    {
         try {
            $view = $this->findModel($id)->toArray();
            $view['thumbs'] = !empty($view['thumbs'])? unserialize($view['thumbs']):[];
             $view['merger_name'] = [
                 (int) $view['location_p'],
                 (int) $view['location_c'],
                 (int) $view['location_a'],
             ];
        } catch (NotFoundHttpException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
        return ResultHelper::json(200, '获取成功', (array)$view);
    }
    /**
     * Creates a new PlaceRoomRim model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceRim();
        $data = Yii::$app->request->post();
        $data['thumbs'] = (!empty($data['thumbs']) && is_array($data['thumbs'])) ? serialize($data['thumbs']):'';
        $data['location_p'] = $data['merger_name']['0'];
        $data['location_c'] = $data['merger_name']['1'];
        $data['location_a'] = $data['merger_name']['2'];
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceRoomRim model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $data['location_p'] = $data['merger_name']['0'];
        $data['location_c'] = $data['merger_name']['1'];
        $data['location_a'] = $data['merger_name']['2'];
        $data['thumbs'] = (!empty($data['thumbs']) && is_array($data['thumbs'])) ? serialize($data['thumbs']):'';
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Deletes an existing PlaceRoomRim model.
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
     * Finds the PlaceRoomRim model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        if (($model = PlaceRim::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
