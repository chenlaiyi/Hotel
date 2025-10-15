<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:02:25
 */
namespace addons\diandi_place\admin\room;
use addons\diandi_place\events\RoomInit;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceServer;
use addons\diandi_place\models\room\PlaceRoom;
use addons\diandi_place\models\room\PlaceRoomServer;
use addons\diandi_place\models\searchs\room\PlaceRoomSearch;
use addons\diandi_place\services\RoomDataServer;
use admin\controllers\AController;
use common\components\events\DdDispatcher;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * RoomController implements the CRUD actions for PlaceRoom model.
 */
class RoomController extends AController
{
    public string $modelSearchName = "PlaceRoomSearch";
    public $modelClass = '';
    // 根据公司检索字段,不参与检索设置为false
    public string $blocField = '';
    // 根据商户检索字段,不参与检索设置为false
    public string $storeField = '';
    /**
     * Lists all PlaceRoom models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceRoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceRoom model.
     * @param integer $id
     * @return array
     */
    public function actionView($id): array
    {
        try {
            $view = $this->findModel($id)->toArray();
            $view['checkin'] = [$view['checkin_start'], $view['checkin_end']];
            $view['cancel'] = [$view['cancel_start'], $view['cancel_end']];
            $view['thumbs'] = !empty($view['thumbs']) ? unserialize($view['thumbs']) : '';
            $view['servers'] = PlaceRoomServer::find()->where(['room_id' => $id])->select('server_id')->column();
            return ResultHelper::json(200, '获取成功', (array)$view);
        } catch (NotFoundHttpException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }
    public function actionList(): array
    {
        $f_bloc_id = Yii::$app->request->input('f_bloc_id',0);
        $f_store_id = Yii::$app->request->input('f_store_id',0);
        $list = PlaceRoom::find()->findBlocs()->andWhere([
            'bloc_id'=>$f_bloc_id,
            'store_id'=>$f_store_id
        ])->select(['id as value', 'title as text', 'title as label'])->asArray()->all();
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * Creates a new PlaceRoom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceRoom();
        $data = Yii::$app->request->post();
        $data['thumbs'] = !empty($data['thumbs']) && is_array($data['thumbs']) ? serialize($data['thumbs']) : '';
        if ($model->load($data, '') && $model->save()) {
            $servers = $data['servers'] ?? [];
            if ($servers && is_array($servers)) {
                $serModel = new PlaceRoomServer();
                foreach ($servers as $server_id) {
                    $_model = clone $serModel;
                    $_model->setAttributes([
                        'room_id' => $model->id,
                        'server_id' => $server_id,
                        'hotel_id' => PlaceList::find()->where(['store_id' => $data['store_id']])->select('id')->scalar(),
                        'title' => PlaceServer::find()->where(['id' => $server_id])->select('title')->scalar(),
                    ]);
                    $_model->save();
                    $msg = ErrorsHelper::getModelError($_model);
                    if ($msg) {
                        return ResultHelper::json(400, $msg);
                    }
                }
            }
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceRoom model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $data['thumbs'] = !empty($data['thumbs']) && is_array($data['thumbs']) ? serialize($data['thumbs']) : '';
        if ($model->load($data, '') && $model->save()) {
            $servers = $data['servers']??[];
            if ($servers && is_array($servers)) {
                $have_ids = PlaceRoomServer::find()->where(['room_id' => $id])->select('server_id')->column();
                $delete_ids = array_diff($have_ids, $servers);
                PlaceRoomServer::deleteAll(['id' => $delete_ids]);
                $serModel = new PlaceRoomServer();
                foreach ($servers as $server_id) {
                    if (!in_array($server_id, $have_ids)) {
                        $_model = clone $serModel;
                        $_model->setAttributes([
                            'room_id' => $id,
                            'server_id' => $server_id,
                            'hotel_id' => PlaceList::find()->where(['store_id' => $data['store_id']])->select('id')->scalar(),
                            'title' => PlaceServer::find()->where(['id' => $server_id])->select('title')->scalar(),
                        ]);
                        $_model->save();
                    }
                }
                $msg = ErrorsHelper::getModelError($serModel);
                if ($msg) {
                    return ResultHelper::json(400, $msg);
                }
            }
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Deletes an existing PlaceRoom model.
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
     * Finds the PlaceRoom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        if (($model = PlaceRoom::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
