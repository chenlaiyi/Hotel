<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-27 11:58:28
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 18:24:32
 */

namespace admin\controllers\addons;

use admin\controllers\AController;
use admin\models\BlocAddons;
use admin\models\enums\AddonsType;
use admin\services\AuthService;
use admin\services\UserService;
use common\components\ActiveRecord\YiiActiveRecord;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\models\User;
use common\plugins\diandi_auth\models\cloud\DiandiAuthAddonsCate;
use common\services\admin\NavService;
use common\services\common\AddonsService as CommonAddonsService;
use diandi\addons\models\AddonsUser;
use diandi\addons\models\DdAddons;
use diandi\addons\models\searchs\DdAddons as DdAddonsSearch;
use diandi\addons\services\addonsService;
use diandi\admin\models\AuthAssignmentGroup;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * AddonsController implements the CRUD actions for DdAddons model.
 */
class AddonsController extends AController
{
    public string  $modelSearchName = 'DdAddons';

    public int $searchLevel = 0;

    public function actionInfo(): array
    {
        $addons = Yii::$app->request->input('addons');
        $info = CommonAddonsService::getAddonsInfo($addons);

        return ResultHelper::json(200, '获取成功', $info);
    }

    /**
     * Lists all DdAddons models.
     *
     * @return array
     */
    public function actionList(): array
    {
        $AddonsUser = new AddonsUser();

        // 根据用户获取应用权限
        $module_names = $AddonsUser->find()->where([
            'user_id' => Yii::$app->user->id,
        ])->select(['module_name'])->column();

        $searchModel = new DdAddonsSearch([
            'module_names' => $module_names,
            // 'parent_mids' => 0,
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userId' => Yii::$app->user->id,
        ]);
    }

    public function actionBlocList(): array
    {
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $list = BlocAddons::find()->where(['bloc_id' => $bloc_id])->with(['addons'])->asArray()->all();
        return ResultHelper::json(200, '获取成功', (array)$list);
    }

    function actionAll()
    {
        $list = DdAddons::find()->where(['is_nav' => 1])->asArray()->all();
        return ResultHelper::json(200, '获取成功', (array)$list);
    }

    function actionAuthBloc()
    {
        $bloc_id = Yii::$app->request->input('bloc_id');
        $addons = Yii::$app->request->input('addons', []);

        $BlocAddons = new BlocAddons();
        $BlocAddons->deleteAll([
            'bloc_id' => $bloc_id
        ]);
        foreach ($addons as $addon) {
            $_BlocAddons = clone $BlocAddons;
            $_BlocAddons->bloc_id = $bloc_id;
            $_BlocAddons->module_name = $addon;
            $_BlocAddons->save();
        }
        return ResultHelper::json(200, '授权成功');
    }

    /**
     * 用户授权
     * @return array
     */
    public function actionAuth(): array
    {
        $user_id = Yii::$app->user->id;
        $list =  AuthService::getUserPluginPermission($user_id);
        return ResultHelper::json(200, '获取成功', $list);
    }


    public function actionChild(): array
    {
        $parent_mid = Yii::$app->request->input('parent_mid');
        if (empty($parent_mid)) {
            return ResultHelper::json(400, '父级应用parent_mid不能为空');
        }
        $AddonsUser = new AddonsUser();

        // 根据用户获取应用权限
        $module_names = $AddonsUser->find()->where([
            'user_id' => Yii::$app->user->id,
        ])->select(['module_name'])->column();
        $list = DdAddons::find()->where(['identifie' => $module_names])->andWhere("FIND_IN_SET($parent_mid,parent_mids)")->asArray()->all();

        return ResultHelper::json(200, '获取成功', $list);
    }

    /**
     * 未安装.
     */
    public function actionUninstalled(): array
    {
        $title = Yii::$app->request->input('title', '');
        $list = addonsService::unAddons($title);

        return ResultHelper::json(200, '获取成功', [
            'list' => $list,
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
        return ResultHelper::json(200, '获取成功',  [
            'model' => $this->findModel($id),
        ]);
    }

    // 显示logo
    public function actionLogo(): array
    {
        $this->layout = false;
        $identifie = Yii::$app->request->get('addon');
        $logo = addonsService::getLogo($identifie);

        return ResultHelper::json(200, '获取成功', [
            'logo' => $logo,
        ]);
    }

    /**
     * Creates a new DdAddons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new DdAddons();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return  ResultHelper::json(200, '获取成功', [
                'model' => $model
            ]);
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return  ResultHelper::json(500, $msg);
        }
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return  ResultHelper::json(200, '获取成功', [
                'model' => $model
            ]);
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return  ResultHelper::json(500, $msg);
        }
    }

    /**
     * Deletes an existing DdAddons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): array
    {
        try {
            $this->findModel($id)->delete();
        } catch (\Throwable $e) {
            return ResultHelper::json(500, $e->getMessage());
        }
        return  ResultHelper::json(200, '删除成功');
    }

    /**
     * Finds the DdAddons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return array|ActiveRecord
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = DdAddons::findOne($id)) !== null) {
            return $model;
        }

        return ResultHelper::json(500, '请检查数据是否存在');
    }
}
