<?php
namespace addons\diandi_place\admin\room;
use addons\diandi_place\models\room\PlaceRoomType;
use addons\diandi_place\models\searchs\room\PlaceRoomType as PlaceRoomTypeSearch;
use addons\diandi_place\services\RoomTypeService;
use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * TypeController implements the CRUD actions for PlaceRoomType model.
 */
class TypeController extends AController
{
    public string $modelSearchName = "PlaceRoomTypeSearch";
    public $modelClass = '';
    /**
     * Lists all PlaceRoomType models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceRoomTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    public function actionList(): array
    {
        $keywords = Yii::$app->request->get('keywords', '');
        $where = [];
        if ($keywords) {
            $where = ['like', 'title', $keywords];
        }
        /**
         * 品牌
         */
        $f_bloc_id = Yii::$app->request->input('f_bloc_id',0);
        $f_store_id = Yii::$app->request->input('f_store_id',0);
        $whereAnd = [];
        if ($f_bloc_id) {
            $whereAnd['bloc_id'] = $f_bloc_id;
        }
        if ($f_store_id) {
            $whereAnd['store_id'] = $f_store_id;
        }
        $list = PlaceRoomType::find()->where($where)->andWhere($whereAnd)->select(['title as label', 'title as text', 'id', 'id as value'])->limit(5)->asArray()->all();
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * Displays a single PlaceRoomType model.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $view = $this->findModel($id);
        $thumbs = unserialize($view['thumbs']);
        $view['thumbs'] = $thumbs;
        return ResultHelper::json(200, '获取成功', $view->toArray());
    }
    /**
     * Finds the PlaceRoomType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = PlaceRoomType::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
    /**
     * Creates a new PlaceRoomType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceRoomType();
        $data = Yii::$app->request->post();
        $data['thumbs'] = serialize(Yii::$app->request->input('thumbs',[]));
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceRoomType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $data['thumbs'] = serialize(Yii::$app->request->input('thumbs',[]));
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Deletes an existing PlaceRoomType model.
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
}