<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-20 14:40:28
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 18:41:31
 */
namespace addons\diandi_place\admin\place;
use addons\diandi_place\models\place\PlaceList;
use Yii;
use addons\diandi_place\models\place\PlaceTier;
use addons\diandi_place\models\searchs\hotel\PlaceTierSearch;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
/**
 * TierController implements the CRUD actions for PlaceTier model.
 */
class TierController extends AController
{
    public string $modelSearchName = "PlaceTierSearch";
    public $modelClass = '';
    /**
     * Lists all PlaceTier models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceTierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceTier model.
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
     * Creates a new PlaceTier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceTier();
        $data = Yii::$app->request->post();
        $data['type_id'] = PlaceList::find()->where(['store_id'=>$data['blocs'][1]])->select('type')->scalar();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceTier model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $data['type_id'] = PlaceList::find()->where(['store_id'=>$data['blocs'][1]])->select('type')->scalar();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    public function actionList(): array
   {
        $bloc_id =\Yii::$app->request->input('f_bloc_id');
        $store_id =\Yii::$app->request->input('f_store_id');
        $list = PlaceTier::find()->where([
            'bloc_id' => $bloc_id,
            'store_id' => $store_id,
        ])->findBlocs()->select(['title as text', 'id as value'])->asArray()->all();
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * Deletes an existing PlaceTier model.
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
     * Finds the PlaceTier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        if (($model = PlaceTier::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
