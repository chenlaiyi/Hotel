<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 08:12:31
 */


namespace common\plugins\diandi_auth\admin\auth;

use admin\controllers\auth\GroupController;
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
        $dataProvider = $searchModel->searchYw(Yii::$app->request->queryParams);

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
//        var_dump($list);die;
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
        $all['permission'] =  AuthService::authGroupPermissions(0); // ArrayHelper::itemsMerge($all['permission'], 0, 'id', 'parent_id', 'children');
        /**
         *
         * 查找auth_item表中permission_type 为0的权限
         *
         */
        // $assignedKey = \diandi\admin\acmodels\AuthItem::find()->where(['permission_type' => 0, 'id' => $assigneds['route'] ?? []])->select('id')->column();
        /**
         * 合并menu 的item_id 和route的item_id
         */
        $assignedKey = array_merge(array_column($assignedP['route']??[], 'item_id'), array_column($assignedP['menu']??[], 'item_id'));
        return ResultHelper::json(200, '获取成功', [
            'all' => $all,
            'assigneds' => $assignedP,
            'assignedKey' => $assignedKey,
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
            Yii::debug("Items to load: " . var_export($items, true), __METHOD__);
            if ($AcmodelsAuthItem->load($items, '') && $AcmodelsAuthItem->save()) {
                $model->updateAll([
                    'item_id' => $AcmodelsAuthItem->id,
                ], [
                    'id' => $model->id,
                ]);
            }
            // 调试信息
            Yii::debug("AuthItem saved with ID: " . $AcmodelsAuthItem->id, __METHOD__);
            /**
             * 如果当前数据存在业务中心，就把业务中心的应用权限集合全部给他
             */
//            $bloc_id = $model->bloc_id;
//            AuthService::authGroupBase($model->id);
//
//            if ($bloc_id) {
//                $module_name = BlocAddons::find()->where(['bloc_id' => $bloc_id])->select('module_name')->column();
//                // 调试信息
//                Yii::debug("Module Names: " . var_export($module_name, true), __METHOD__);
//
//                AuthService::authApp($model->id, $module_name);
//            }
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            // 调试信息
            Yii::debug("Model save failed: " . var_export($msg, true), __METHOD__);

            return ResultHelper::json(400, $msg);
        }
    }
}
