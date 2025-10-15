<?php

namespace common\plugins\diandi_auth\admin;

use common\plugins\diandi_auth\models\AuthMemberRole;
use addons\zyj_wash\models\enums\CheckGoodsEnum;
use addons\zyj_wash\models\enums\OrderLogisticsEnum;
use addons\zyj_wash\models\enums\washOrderStatusEnum;
use Yii;
use common\plugins\diandi_auth\models\AuthMemberRoleMoney;
use common\plugins\diandi_auth\models\searchs\AuthMemberRoleMoney as AuthMemberRoleMoneySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;

/**
 * MoneyController implements the CRUD actions for AuthMemberRoleMoney model.
 */
class MoneyController extends AController
{
    public string $modelSearchName = "AuthMemberRoleMoneySearch";

    public $modelClass = '';


    /**
     * Lists all AuthMemberRoleMoney models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new AuthMemberRoleMoneySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single AuthMemberRoleMoney model.
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
     * Creates a new AuthMemberRoleMoney model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new AuthMemberRoleMoney();
        $data = Yii::$app->request->post();
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing AuthMemberRoleMoney model.
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

    function actionEnum(): array
    {
        $type = Yii::$app->request->input('type');
        $list = [];
        switch ($type) {
            case 1:
                $list = OrderLogisticsEnum::listOptions();
                break;
            case 2:
                $list = washOrderStatusEnum::listOptions();
                break;
            case 3:
                $list = CheckGoodsEnum::listOptions();

                break;
        }

        array_walk($list, function (&$value, $key) {
            $value['value'] = $value['label_name'];
        });

        return ResultHelper::json(200, '获取成功', $list);

    }

    function actionRole()
    {
        $list = AuthMemberRole::find()->select(['id','name','name as text','id as value'])->asArray()->all();
        return ResultHelper::json(200, '获取成功', $list);
    }

    /**
     * Finds the AuthMemberRoleMoney model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = AuthMemberRoleMoney::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}