<?php

namespace common\plugins\diandi_auth\admin;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\plugins\diandi_auth\models\searchs\SearchSetting;
use common\plugins\diandi_auth\models\Sections;
use common\plugins\diandi_auth\models\Setting;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class StoreSettingController extends AController
{
    public string $modelSearchName = "SearchSetting";

    public $modelClass = '';

    /**
     * Lists all Setting models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel  = new SearchSetting();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
                'searchModel'     => $searchModel,
                'dataProvider'    => $dataProvider,
                'modelSearchName' => $this->modelSearchName,
                'field'           => $searchModel->attributeLabels(),
            ]
        );
    }

    /**
     * Displays a single Setting model.
     *
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
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     */
    public function actionCreate(): array
    {
        $section = Yii::$app->request->post('section');
        $setting = Yii::$app->request->post('items');
        $model   = new Setting();

        if (empty($section)) {
            return ResultHelper::json(400, 'section 不能为空');
        }

        if (empty($setting)) {
            return ResultHelper::json(400, 'items 不能为空');
        }

        foreach ($setting as $key => $value) {
            $type  = $value['type'] ?? '';
            $key   = $value['field'] ?? '';
            $value = $value['value'] ?? '';

            if (empty($key) || empty($value) || empty($type)) {
                continue;
            }

            $_model = clone $model;

            $exist = Setting::find()->where([
                    'bloc_id'  => Yii::$app->request->input('bloc_id'),
                    'store_id' => Yii::$app->request->input('store_id'),
                    'type'     => $type,
                    'section'  => $section,
                    'key'      => $key,
                ]
            )->one();

            if ($exist) {
                $exist->value = $value;
                $exist->save();
            } else {
                $_model->setAttributes([
                        'bloc_id'  => Yii::$app->request->input('bloc_id'),
                        'store_id' => Yii::$app->request->input('store_id'),
                        'type'     => $type,
                        'section'  => $section,
                        'key'      => $key,
                        'value'    => $value,
                    ]
                );

                $_model->save(false);
            }
        }

        return ResultHelper::json(200, '创建成功');
    }

    public function actionInfo()
    {
        $bloc_id  = Yii::$app->request->input('bloc_id');
        $store_id = Yii::$app->request->input('store_id');
        $settings = Setting::find()
            ->where([
                    'bloc_id' => $bloc_id,
                    'store_id' => $store_id,
                ]
            )->all();

        return ResultHelper::json(200, '获取成功', $settings);
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
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
     *
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
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSections()
    {
        $section = Yii::$app->request->input('section');
        if ($section) {
            $item             = Sections::find()
                ->where([
                        'section' => $section,
                    ]
                )->findBloc()
                ->select([
                        'section as id',
                        'section as value',
                        'description as label',
                        'description as text',
                        'setting_key_list',
                    ]
                )->asArray()
                ->one();
            $setting_key_list = json_decode($item['setting_key_list'], true);
            array_walk($setting_key_list, function (&$value) {
                $value['id']    = $value['field'];
                $value['value'] = $value['field'];
                $value['text']  = $value['name'];
                $value['label'] = $value['name'];
            }
            );
            $item['setting_key_list'] = $setting_key_list;
            return ResultHelper::json(200, '获取成功', $item);
        } else {
            $item = Sections::find()
                ->where([
                        'disabled' => 0,
                    ]
                )
                ->findBloc()
                ->select([
                        'section as id',
                        'section as value',
                        'description as label',
                        'description as text',
                        'setting_key_list',
                    ]
                )->asArray()
                ->all();
            array_walk($item, function (&$value) {
                $setting_key_list = json_decode($value['setting_key_list'], true);
                foreach ($setting_key_list as $k => &$item) {
                    if (isset($item['store_config']) && $item['store_config'] == false) {
                        unset($setting_key_list[$k]);
                    }
                    $item['value'] = '';
                }
                $value['setting_key_list'] = $setting_key_list;
            }
            );
            return ResultHelper::json(200, '获取成功', $item);
        }

    }

}
