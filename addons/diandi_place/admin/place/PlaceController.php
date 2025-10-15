<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-21 14:08:04
 */
namespace addons\diandi_place\admin\place;
use addons\diandi_place\models\enums\HotelRoomTypeEnums;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\searchs\hotel\PlaceListSearch;
use addons\diandi_place\services\PlaceService;
use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use diandi\addons\models\StoreLabelLink;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * PlaceController implements the CRUD actions for PlaceList model.
 */
class PlaceController extends AController
{
    public string $modelSearchName = "PlaceListSearch";
    public $modelClass = '';
//    // 根据公司检索字段,不参与检索设置为false
//    public string $blocField = '';
//
//    // 根据商户检索字段,不参与检索设置为false
//    public string $storeField = '';
    /**
     * Lists all PlaceList models.
     * @return array|object[]|string[]
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }
    /**
     * Displays a single PlaceList model.
     * @param integer $id
     * @return array
     */
    public function actionView($id): array
    {
        $view = PlaceList::find()->where(['id' => $id])->joinWith(['store'])->one()->toArray();
        if (empty($view)) {
            return ResultHelper::json(400, '数据不存在');
        }
        if ($view['store']) {
            $view['provinceCityDistrict'] = [
                (int)$view['store']['province'],
                (int)$view['store']['city'],
                (int)$view['store']['county'],
            ];
            $view['address'] = [
                'address' => $view['store']['address'],
                'lng' => $view['store']['longitude'],
                'lat' => $view['store']['latitude'],
            ];
            $store_id = $view['store_id'];
            $view['label_link'] = StoreLabelLink::find()->where(['store_id' => $store_id])->select(['label_id'])->column();
            unset($view['bloc']);
        }
        $view['thumbs'] = $view['thumbs'] ? unserialize($view['thumbs']) : [];
        return ResultHelper::json(200, '获取成功',$view);
    }
    /**
     * Creates a new PlaceList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new PlaceList();
        $model->scenario = 'create';
        $provinceCityDistrict = Yii::$app->request->input('provinceCityDistrict', []);
        $data = Yii::$app->request->post();
        $thumbs = Yii::$app->request->input('thumbs',[]);
        $data['thumbs'] = serialize($thumbs);
        $data['province'] = $provinceCityDistrict[0]??0;
        $data['city'] = $provinceCityDistrict[1]??0;
        $data['county'] = $provinceCityDistrict[2]??0;
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    /**
     * Updates an existing PlaceList model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $data = Yii::$app->request->post();
        $provinceCityDistrict = Yii::$app->request->input('provinceCityDistrict', []);
        $data['province'] = $provinceCityDistrict[0]??0;
        $data['city'] = $provinceCityDistrict[1]??0;
        $data['county'] = $provinceCityDistrict[2]??0;
        $thumbs = Yii::$app->request->input('thumbs',[]);
        $data['thumbs'] = serialize($thumbs);
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }
    public function actionTypeList(): array
    {
        $list = PlaceService::mobileListType();
        // 全局的房型
        $RoomType = HotelRoomTypeEnums::listData();
        return ResultHelper::json(200, '获取成功', [
            'list' => $list,
            'RoomType' => $RoomType
        ]);
    }
    public function actionList(): array
    {
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $store_id = Yii::$app->request->input('store_id', 0);
        $list = PlaceList::find()->where([
            'bloc_id' => $bloc_id,
            'store_id' => $store_id,
        ])->select(['id as value', 'name as text'])->asArray()->all();
        return ResultHelper::json(200, '编辑成功', $list);
    }
    /**
     * Deletes an existing PlaceList model.
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
     * Finds the PlaceList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlaceList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): PlaceList
    {
        if (($model = PlaceList::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
