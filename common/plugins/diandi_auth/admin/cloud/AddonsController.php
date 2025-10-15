<?php
namespace common\plugins\diandi_auth\admin\cloud;

use admin\controllers\AController;
use admin\models\BlocAddons;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\models\User;
use diandi\addons\models\DdAddons;
use diandi\addons\models\searchs\DdAddons as DdAddonsSearch;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * AddonsController implements the CRUD actions for DdAddons model.
 */
class AddonsController extends AController
{
    public $modelClass             = 'diandi\addons\models\searchs\DdAddons';
    public string $modelSearchName = 'DdAddons';

    public int $searchLevel = 0;

    /**
     * Lists all DdAddons models.
     *
     * @return array
     */
    public function actionList(): array
    {
        $AddonsUser = new BlocAddons();
        $bloc_id    = Yii::$app->request->input('bloc_id');

        // 根据用户获取应用权限
        $module_names = $AddonsUser->find()->where([
            // 'user_id' => Yii::$app->user->id,
            'bloc_id' => $bloc_id,
        ])->select(['module_name'])->column();

        $searchModel = new DdAddonsSearch([
            'module_names' => $module_names,
            // 'parent_mids' => 0,
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'userId'       => Yii::$app->user->id,
        ]);
    }

    /**
     * Displays a single DdAddons model.
     *
     * @param int $id
     *
     * @return array
     *
     */
    public function actionView($id): array
    {
        $m = DdAddons::find()->where(['mid' => $id])->asArray()->one();
        return ResultHelper::json(200, '获取成功', $m);
    }

    /**
     * Updates an existing DdAddons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id): array
    {
        $model        = $this->findModel($id);
        $is_nav       = Yii::$app->request->post('is_nav');
        $displayorder = Yii::$app->request->post('displayorder');
        $title        = Yii::$app->request->post('title');
//        'identifie', 'title', 'version', 'ability', 'description', 'author', 'url', 'settings', 'logo'
        $data = [
            'is_nav'       => $is_nav,
            'title'        => $title,
            'displayorder' => $displayorder,
            'description'  => $model->description,
            'author'       => $model->author,
            'version'      => $model->version,
            'identifie'    => $model->identifie,
            'ability'      => $model->ability,
            'settings'     => $model->settings,
        ];
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '获取成功', [
                'model' => $model,
            ]);
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(500, $msg);
        }
    }

    /**
     * Finds the DdAddons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return array|ActiveRecord
     */
    protected function findModel($id): array | ActiveRecord
    {
        if (($model = DdAddons::findOne($id)) !== null) {
            return $model;
        }

        return ResultHelper::json(500, '请检查数据是否存在');
    }
}
