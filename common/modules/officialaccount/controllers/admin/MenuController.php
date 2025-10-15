<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\modules\officialaccount\models\OfficialaccountWechatMenu;
use common\modules\officialaccount\models\searchs\OfficialaccountWechatMenu as OfficialaccountWechatMenuSearch;
use common\modules\officialaccount\services\WechatMenuService;
use Throwable;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for OfficialaccountWechatMenu model.
 */
class MenuController extends AController
{
    public string $modelSearchName = "OfficialaccountWechatMenuSearch";

    public $modelClass = '';

    public function actionList(): array
    {
        $wechat = Yii::$app->wechat->app;
        $list = $wechat->menu->list();

        return ResultHelper::json(200, '获取成功', [
            'menuList' => $list
        ]);
    }

    /**
     * @throws \Exception
     */
    public function actionListTreeMenu(): array
    {
        $lists = WechatMenuService::getWechatMenu();
        return ResultHelper::json(200, '获取成功', [
            'menuTree' => $lists,
//            'menuTree'=>$list
        ]);
    }

    public function actionEdit(): array
    {
        $menu = Yii::$app->request->input('menu');
        $lists = WechatMenuService::createMenu($menu);

        return ResultHelper::json(200, '获取成功', $menu);
    }

    /**
     * Lists all OfficialaccountWechatMenu models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new OfficialaccountWechatMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single OfficialaccountWechatMenu model.
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
     * Creates a new OfficialaccountWechatMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new OfficialaccountWechatMenu();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing OfficialaccountWechatMenu model.
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
     * Finds the OfficialaccountWechatMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = OfficialaccountWechatMenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}