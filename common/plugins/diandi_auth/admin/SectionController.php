<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 18:41:46
 */

namespace common\plugins\diandi_auth\admin;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\plugins\diandi_auth\models\searchs\SearchSections;
use common\plugins\diandi_auth\models\Sections;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * SectionController implements the CRUD actions for Sections model.
 */
class SectionController extends AController
{
    public string $modelSearchName = "SearchSections";

    public $modelClass = '';

    /**
     * Lists all Sections models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel  = new SearchSections();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel'     => $searchModel,
            'dataProvider'    => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field'           => $searchModel->attributeLabels(),
        ]);
    }

    /**
     * Displays a single Sections model.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $view = Sections::find()->where(['id' => $id])->asArray()->one();
        if ($view) {
            $view['setting_key_list'] = json_decode($view['setting_key_list'], true);
        }
        return ResultHelper::json(200, '获取成功', $view);
    }

    /**
     * Creates a new Sections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new Sections();
        $data  = Yii::$app->request->post();
        $section = Yii::$app->request->input('section');

        /**
         * 查询是否存在
         */
        $section = Sections::find()->where(['section' => $section])->one();

        if ($section) {
            $section->setAttributes($data);
            if (!$section->save()) {
                $msg = ErrorsHelper::getModelError($section);
                return ResultHelper::json(400, $msg);
            }
            return ResultHelper::json(200, '编辑成功', $section->toArray());
        } else {
            if ($model->load($data, '') && $model->save()) {
                return ResultHelper::json(200, '创建成功', $model->toArray());
            } else {
                $msg = ErrorsHelper::getModelError($model);
                return ResultHelper::json(400, $msg);
            }
        }
    }

    function actionDetail(): array
    {
        $section = Yii::$app->request->input('section');
        if (empty($section)) {
            return ResultHelper::json(400, 'section 参数不能为空');
        }
        $view = Sections::find()->where(['section' => $section])->asArray()->one();
        if ($view) {
            $view['setting_key_list'] = json_decode($view['setting_key_list'], true);
        }
        return ResultHelper::json(200, '获取成功', $view ?? []);
    }

    /**
     * Updates an existing Sections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data  = Yii::$app->request->post();
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
     * Finds the Sections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array | ActiveRecord
    {
        if (($model = Sections::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
