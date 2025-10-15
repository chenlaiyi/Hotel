<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 07:49:09
 */


namespace admin\controllers\auth;

use admin\models\BlocAddons;
use admin\services\AuthService;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use diandi\admin\acmodels\AuthItem as AcmodelsAuthItem;
use diandi\admin\acmodels\AuthUserGroup;
use diandi\admin\components\Configs;
use diandi\admin\models\searchs\UserGroupSearch;
use diandi\admin\models\UserGroup;
use Yii;
use yii\web\NotFoundHttpException;

class GroupYwController extends GroupController
{
    public $modelClass = 'UserGroupSearch';

    public int $searchLevel = 1;

    /**
     * Lists all UserGroup models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new UserGroupSearch();
        $searchModel->is_sys = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single UserGroup model.
     *
     * @param int $id
     *
     * @return array
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $model = $this->getGroups($id);
        $manager = Configs::authManager();
        $list = $manager->getAuths($model->item_id);
        $all = [];
        $assigneds = [];
        $assignedP = $list['assigned'];
        $type = $model->is_sys;
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $module_name = BlocAddons::find()->where(['bloc_id' => $bloc_id])->select('module_name')->asArray()->column();

        $available = $list['available'];
        $allGroup = AuthService::getGroupByBlocId($bloc_id);
        $item_ids = array_column($allGroup, 'item_id');
        foreach ($list['all'] as $key => $value) {
            $value = ArrayHelper::toArray($value);
            foreach ($value as $k => &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
                if ($key == 'role') {
                    if ($val['id'] == $id || $val['is_sys'] === 1 || !in_array($val['item_id'], $item_ids)) {
                        unset($value[$k]);
                    }
                }

                if ($key == 'permission') {
                    if ($val['id'] == $id || $val['is_sys'] === 1 ||  !in_array($val['module_name'], $module_name)) {
                        unset($value[$k]);
                    }
                }
            }
            $all[$key] = array_values($value);
        }


        foreach ($available as $key => $value) {
            $value = ArrayHelper::toArray($value);
            foreach ($value as &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
            }

            $available[$key] = array_values($value);
            unset($value);
        }

        if (!empty($available['permission'])) {
            $available['permission'] = ArrayHelper::itemsMerge($available['permission'], 0, 'id', 'parent_id', 'children');
        }

        foreach ($assignedP as $key => &$value) {
            $value = ArrayHelper::toArray($value);

            foreach ($value as &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
                $assigneds[$key][] = $val['item_id'];
            }

            $assignedP[$key] = array_values($value);
            unset($value);
        }

        $all['permission'] =  AuthService::authGroupPermission($all['permission']); // ArrayHelper::itemsMerge($all['permission'], 0, 'id', 'parent_id', 'children');


        return ResultHelper::json(200, '获取成功', [
            'all' => $all,
            'assigneds' => $assignedP,
            'assignedKey' => $assigneds,
            'availables' => $available,
        ]);
    }

    /**
     * Creates a new UserGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $model = new UserGroup();
        $data = Yii::$app->request->input();
        $is_sys = Yii::$app->request->input('is_sys', 0);
        /**
         * 校验公司应用权限是否添加
         */
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $isAddons = BlocAddons::find()->where(['bloc_id' => $bloc_id])->one();
        if (empty($isAddons)) {
            return ResultHelper::json(404, '请先给公司添加应用权限');
        }
        $data['is_sys'] = 0;
        if ($model->load($data, '') && $model->save()) {
            // 给item同步添加数据
            $AcmodelsAuthItem = new AcmodelsAuthItem();
            $items = [
                'permission_type' => 2,
                'name' => $model->name,
                'is_sys' => $model->is_sys,
                'parent_id' => 0,
                'permission_level' => 0,
            ];

            if ($AcmodelsAuthItem->load($items, '') && $AcmodelsAuthItem->save()) {
                $model->updateAll([
                    'item_id' => $AcmodelsAuthItem->id,
                ], [
                    'id' => $model->id,
                ]);
            }

            /**
             * 如果当前数据存在业务中心，就把业务中心的应用权限集合全部给他
             */
            $bloc_id = $model->bloc_id;
            if ($bloc_id) {
                $module_name = BlocAddons::find()->where(['bloc_id' => $bloc_id])->select('module_name')->asArray()->column();

                AuthService::authApp($model->id, $module_name);
            }
            AuthService::authGroupBase($model->id);
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }
}
