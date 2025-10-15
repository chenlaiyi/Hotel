<?php
namespace addons\diandi_place\admin\place;
use Yii;
use addons\diandi_place\models\place\PlaceTypeList;
    use addons\diandi_place\models\searchs\place\PlaceTypeList as PlaceTypeListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;
/**
* HouseController implements the CRUD actions for PlaceTypeList model.
*/
class HouseController extends AController
{
public string $modelSearchName = "PlaceTypeListSearch";
public $modelClass = '';
/**
* Lists all PlaceTypeList models.
* @return array
*/
public function actionIndex(): array
{
    $searchModel = new PlaceTypeListSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    return ResultHelper::json(200, '获取成功',[
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'modelSearchName'=>$this->modelSearchName,
    'field' => $searchModel->attributeLabels()
    ]);
}
/**
* Displays a single PlaceTypeList model.
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
* Creates a new PlaceTypeList model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return array
*/
public function actionCreate(): array
{
    $model = new PlaceTypeList();
    $data = Yii::$app->request->post();
    if ($model->load($data, '') && $model->save()) {
        return ResultHelper::json(200, '创建成功', $model->toArray());
    } else {
        $msg = ErrorsHelper::getModelError($model);
        return ResultHelper::json(400, $msg);
    }
}
/**
* Updates an existing PlaceTypeList model.
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
* Finds the PlaceTypeList model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return array|ActiveRecord the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel($id): array|ActiveRecord
{
if (($model = PlaceTypeList::findOne($id)) !== null) {
return $model;
}
throw new NotFoundHttpException('没有该数据');
}
}