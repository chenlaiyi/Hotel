<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-03 15:38:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-07 13:35:18
 */
namespace addons\diandi_place\admin\place;
use addons\diandi_place\models\searchs\staff\PlaceStoreStaff;
use addons\diandi_place\models\staff\PlaceStoreStaffLog;
use admin\controllers\AController;
use api\models\DdMember;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use Throwable;
use Yii;
use yii\base\ErrorException;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * StaffController implements the CRUD actions for BeaStoreStaff model.
 */
class StaffController extends AController
{
    public string $modelSearchName = "BeaStoreStaff";
    public $modelClass = '';
    // 根据公司检索字段,不参与检索设置为false
    public string $blocField = '';
    // 根据商户检索字段,不参与检索设置为false
      public string $storeField = '';
    /**
     * Lists all BeaStoreStaff models.
     * @return mixed
     */
    public function actionIndex(): array
    {
        $searchModel = new PlaceStoreStaff();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single BeaStoreStaff model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id): array
    {
         try {
            $view = $this->findModel($id)->toArray();
        } catch (NotFoundHttpException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
        $view['store_id'] = [$view['bloc_id'], $view['store_id']];
        return ResultHelper::json(200, '获取成功', $view);
    }
    /**
     * Creates a new BeaStoreStaff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     * @throws ErrorException
     */
    public function actionCreate(): array
    {
        $model = new PlaceStoreStaff();
        $DdMember = new DdMember();
        $username = Yii::$app->request->input('staff_code');
        $mobile = Yii::$app->request->input('mobile');
        $password = Yii::$app->request->input('password');
        $is_boss = Yii::$app->request->input('is_boss');
        $res = $DdMember->signup($username, $mobile, $password);
        if ($res['code'] === 400) {
            return ResultHelper::json(400, $res['message'], $res);
        }
        if ($res) {
            $data = Yii::$app->request->post();
            if ((int) $is_boss === 0) {
                $data['bloc_id'] = Yii::$app->request->input('store_id',0)[0];
                $data['store_id'] = Yii::$app->request->input('store_id',0)[1];
            } else {
                $data['bloc_id'] = Yii::$app->request->input('bloc_id',0);
            }
            $data['member_id'] = $res['member']['member_id'];
            if ($model->load($data, '') && $model->save()) {
                return ResultHelper::json(200, '创建成功', (array)$model);
            } else {
                $msg = ErrorsHelper::getModelError($model);
                return ResultHelper::json(401, $msg);
            }
        }else{
            return ResultHelper::json(400,'注册失败');
        }
    }
    /**
     * Updates an existing BeaStoreStaff model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPut) {
            $data = Yii::$app->request->post();
            $is_boss = Yii::$app->request->input('is_boss');
            $data['bloc_id'] = Yii::$app->request->input('store_id',0)[0];
            $data['store_id'] = Yii::$app->request->input('store_id',0)[1];
            if ($model['store_id'] != $data['store_id']) {
                $BeaStoreStaffLog = new PlaceStoreStaffLog();
                $log = [
                    'member_id' => $model['member_id'],
                    'store_id' => $data['store_id'],
                    // 'staff_code'=>$staff_code,
                    'mobile' => Yii::$app->request->input('mobile'),
                    'status' => Yii::$app->request->input('status'),
                    'old_store_id' => $model['store_id'],
                ];
                $BeaStoreStaffLog->load($log, '') && $BeaStoreStaffLog->save();
                $msg = ErrorsHelper::getModelError($BeaStoreStaffLog);
                if ($msg) {
                    return ResultHelper::json(400, $msg);
                }
            }
            if ($model->load($data, '') && $model->save()) {
                return ResultHelper::json(200, '编辑成功', $model);
            } else {
                $msg = ErrorsHelper::getModelError($model);
                return ResultHelper::json(400, $msg);
            }
        }else{
            return ResultHelper::json(400,'注册失败');
        }
    }
    /**
     * Deletes an existing BeaStoreStaff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array|object[]|string[]
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
     * Finds the BeaStoreStaff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = PlaceStoreStaff::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('没有该数据');
    }
}
