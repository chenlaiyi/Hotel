<?php


namespace common\plugins\diandi_auth\admin;


use Yii;
use common\plugins\diandi_auth\models\MemberRolePermission;
use common\plugins\diandi_auth\models\searchs\MemberRolePermission as ZyjMemberRolePermissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;


/**
 * RolepermissionController implements the CRUD actions for ZyjMemberRolePermission model.
 */
class RolepermissionController extends AController
{

    public string $modelSearchName = "ZyjMemberRolePermissionSearch";


    public $modelClass = '';


    /**
     * Lists all ZyjMemberRolePermission models.
     * @return array
     */

    public function actionIndex(): array

    {

        $searchModel = new ZyjMemberRolePermissionSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return ResultHelper::json(200, '获取成功', [

            'searchModel' => $searchModel,

            'dataProvider' => $dataProvider,

            'modelSearchName' => $this->modelSearchName,

            'field' => $searchModel->attributeLabels()

        ]);

    }


    /**
     * Displays a single ZyjMemberRolePermission model.
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
     * Creates a new ZyjMemberRolePermission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */

    public function actionCreate(): array

    {

        $model = new MemberRolePermission();

        $data = Yii::$app->request->post();

        if ($model->load($data, '') && $model->save()) {

            return ResultHelper::json(200, '创建成功', $model->toArray());

        } else {

            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);

        }

    }


    /**
     * Updates an existing ZyjMemberRolePermission model.
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
     * Finds the ZyjMemberRolePermission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id): array|ActiveRecord

    {

        if (($model = MemberRolePermission::findOne($id)) !== null) {

            return $model;

        }


        throw new NotFoundHttpException('The requested page does not exist.');

    }

}