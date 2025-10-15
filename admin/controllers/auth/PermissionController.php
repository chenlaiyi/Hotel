<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-03 16:36:46
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-18 17:26:04
 */

namespace admin\controllers\auth;

use admin\controllers\AController;
use admin\models\auth\AuthRoute;
use admin\services\AuthService;
use common\helpers\ArrayHelper as HelpersArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use diandi\addons\models\DdAddons;
use diandi\admin\acmodels\AuthItem as AcmodelsAuthItem;
use diandi\admin\acmodels\AuthItemChild;
use diandi\admin\components\Configs;
use diandi\admin\components\Helper;
use diandi\admin\components\Item;
use diandi\admin\models\AuthItem;
use diandi\admin\models\AuthItemModel;
use diandi\admin\models\Menu;
use diandi\admin\models\searchs\AuthItemSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


/**
 * PermissionController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *
 * @since 1.0
 */
class PermissionController extends AController
{
    public $modelClass = 'diandi\admin\models\AuthItem';

    public $enableCsrfValidation = false;

    public int $is_sys;

    public string $module_name;

    public int $parent_type = 0; //0:系统,1模块

    public int $searchLevel = 0;

    public function actions(): array
    {
        $this->module_name = Yii::$app->request->get('module_name', 'system');
        $this->is_sys = $this->module_name == 'system' ? 1 : 0;
        return parent::actions();
    }

    /**
     * {}
     */
    public function labels(): array
    {
        return [
            'Item' => 'Permission',
            'Items' => 'Permissions',
        ];
    }

    /**
     * {}
     */
    public function getType(): int
    {
        return Item::TYPE_PERMISSION;
    }

    /**
     * Lists all AuthItem models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $authManager = Configs::authManager();
//        $where = ['is_sys' => $this->is_sys, 'module_name' => $this->module_name]; //简化权限管理
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $DdAddons = new DdAddons();

        $addons = $DdAddons->find()->indexBy('identifie')->select(['title'])->asArray()->column();
        $addons['system'] = '系统';
        $addons['diandi_website'] = '内容';
        $addons['diandi_auth'] = '授权';
        $dataProvider = HelpersArrayHelper::objectToarray($dataProvider);

        $parentMent = $dataProvider['allModels'];

        foreach ($parentMent as &$value) {
            $module_name = !empty($addons[$value['module_name']]) ? $addons[$value['module_name']] : '';
            $value['addons'] = $module_name;
        }

        $list = HelpersArrayHelper::itemsMerge($parentMent, 0, 'id', 'parent_id', 'children');

        if (!empty($parentMent) && empty($list)) {
            $list = $parentMent;
        }
        return ResultHelper::json(200, '获取成功', [
            'list' => $list,
            '$authManager' => $authManager,
            'dataProvider' => $dataProvider,
            'addons' => $addons,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAddons(): array
    {
        $DdAddons = new DdAddons();

        $addon['system'] = '系统';

        $addons = $DdAddons->find()->indexBy('identifie')->select(['title'])->asArray()->column();

        return ResultHelper::json(200, '获取成功', array_merge($addon, $addons));
    }

    public function actionLevels(): array
    {
        $DdAddons = new DdAddons();

        $addons = $DdAddons->find()->indexBy('identifie')->select(['title'])->asArray()->column();
        $addons['system'] = '系统';

//        $where = ['permission_type' => 1, 'parent_id' => 0];
        $where = ['permission_type' => 1];
        $is_sys = Yii::$app->request->input('is_sys', 0);

        $where['is_sys'] = (int)$is_sys;

        // 权限只能是2级，不能是三级
        $parentMent = AuthItemModel::find()->where($where)->select(['id', 'id as value', 'parent_id', 'name as label', 'module_name', 'is_sys'])->asArray()->all();

        foreach ($parentMent as &$value) {
            if ($value['is_sys'] == 1) {
                $module_name = $addons[$value['module_name']];
                $value['label'] = $module_name . '-' . $value['label'];
            }
        }

        $levels = HelpersArrayHelper::itemsMerge($parentMent, 0, 'id', 'parent_id', 'children');

        return ResultHelper::json(200, '获取成功', $levels);
    }

    /**
     * 获取规则
     * @return array
     */
    public function actionRule(): array
    {
        // 获取所有的权限规则
        $Rules = Configs::authManager()->getRules();

        $Rule = HelpersArrayHelper::objectToarray($Rules);
        $rulesSelect = [];
        foreach ($Rule as $key => $value) {
            $item = [
                'value' => $key,
                'text' => $value['name'],
            ];
            $rulesSelect[] = $item;
        }

        return ResultHelper::json(200, '获取成功', $rulesSelect);
    }

    public function actionRoute(): array
    {
        $list = AuthRoute::find()->select(['name as label', 'id'])->limit(10)->asArray()->all();

        return ResultHelper::json(200, '获取成功', $list);
    }

    /**
     * Displays a single AuthItem model.
     *
     * @param string $id
     *
     * @return array
     */
    public function actionView($id): array
    {
        $all = [];
        $permission_type = Yii::$app->request->input('permission_type',0);
        $module_name = Yii::$app->request->input('module_name','');

        $model = $this->findSelfModel($id);
        $list = $model->getAdminItems($permission_type);
        $assignedAll = [];
        $assigned = $list['assigned'];
        $available = $list['available'];
        foreach ($available as $key => $value) {
            $value = ArrayHelper::toArray($value);
            foreach ($value as $k => &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
                if ($module_name && $module_name != $val['module_name']) {
                    unset($value[$k]);
                }
            }
            $available[$key] = array_values($value);
            unset($value);
        }

        foreach ($assigned as $key => &$value) {
            $value = ArrayHelper::toArray($value);

            foreach ($value as &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
                $assignedAll[$key][] = $val['item_id'];
            }
            $assigned[$key] = array_values($value);
            unset($value);
        }

        foreach ($list['all'] as $key => $value) {
            $value = ArrayHelper::toArray($value);
            foreach ($value as $k => &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
                if ($module_name && $module_name != $val['module_name']) {
                    unset($value[$k]);
                }
            }
            $all[$key] = array_values($value);
        }

        /**
         * 对路由进行精细拆分
         */
        $route_type = AuthRoute::find()->indexBy('item_id')->select('route_type')->column();

        if (!empty($all['route'])) {
            $allRoute = $all['route'];
            unset($all['route']);
            $all['route'] = $all['menu'] = $all['api'] = [];
            foreach ($allRoute as $item) {
                switch ($item['route_type']) {
                    case 1:
                        $all['route'][] = $item;
                        break;
                    case 2:
                        $all['menu'][] = $item;
                        break;
                    case 3:
                        $all['api'][] = $item;
                        break;
                }
            }
        }

        /**
         * 将权限全部赋值给三个类型，方便前端渲染
         */
        if (!empty($assignedAll['route'])) {
            $assignedAll['menu'] = $assignedAll['api'] = $assignedAll['route'];
        }

        $assignedAll['permission'] = $assignedAll['permission']??[];

        if (!empty($assigned['route'])) {
            $assignedRoute = $assigned['route'];
            unset($assigned['route']);
            $assigned['route'] = $assigned['menu'] = $assigned['api'] = [];
            foreach ($assignedRoute as $item) {
                switch ($route_type[$item['item_id']]??1) {
                    case 1:
                        $assigned['route'][] = $item;
                        break;
                    case 2:
                        $assigned['menu'][] = $item;
                        break;
                    case 3:
                        $assigned['api'][] = $item;
                        break;
                }
            }
        }

        if (!empty($available['route'])) {
            $availableRoute = $available['route'];
            unset($available['route']);
            $available['route'] = $available['menu'] = $available['api'] = [];

            foreach ($availableRoute as $item) {
                switch ($route_type[$item['item_id']]??1) {
                    case 1:
                        $available['route'][] = $item;
                        break;
                    case 2:
                        $available['menu'][] = $item;
                        break;
                    case 3:
                        $available['api'][] = $item;
                        break;
                }
            }
        }
        $all['permission'] = \common\helpers\ArrayHelper::itemsMerge($all['permission'],0,'id','parent_id','children');
        return ResultHelper::json(200, '获取成功', [
            'all' => $all,
            'assigneds' => $assigned,
            'assignedKey' => $assignedAll,
            'availables' => $available,
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {

        $model = new AcmodelsAuthItem();

        $module_name = $this->module_name;

        $data = Yii::$app->getRequest()->post();

        if ($model->load($data, '') && $model->save()) {
            AuthService::upParentPermissionMenuId($model->id);
            return ResultHelper::json(200, '提交成功', [
                'module_name' => $module_name
            ]);
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }

    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateitem(): array
    {
        $id = Yii::$app->request->input('id');
        $model = $this->findSelfModel($id);
        $data = yii::$app->request->post();
        $data['module_name'] = (int) $data['is_sys'] === 1?'system':$data['module_name'];

        if ($model->load($data, '') && $model->save()) {
            AuthService::upParentPermissionMenuId($model->id);

            return ResultHelper::json(200, '编辑成功',$model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return array
     */
    public function actionDelete($id): array
    {
        $model = $this->findSelfModel($id);
        Configs::authManager()->remove($model->item);
        Helper::invalidate();
        $module_name = $this->module_name;

        return ResultHelper::json(200, '删除成功', ['module_name' => $module_name]);
    }

    public function actionChange(): array
    {
        $id = Yii::$app->request->input('id');
        $items = Yii::$app->request->input('items');

        if (empty($id)) {
            return ResultHelper::json(400, '参数ID不能为空');
        }

        if (empty($items)) {
            return ResultHelper::json(400, '参数items不能为空');
        }

        $model = $this->findSelfModel($id);

        if (key_exists('route', $items)) {
//            路由级别:0: 目录1: 页面 2: 按钮 3: 接口
            $route_type = (int) Yii::$app->request->input('route_type',1);
            $list = $items['route'];
            $have_ids = AuthItemChild::find()->where([
                'parent_id' => $id,
                'child_type' => 0,
                'route_type'=>$route_type
            ])->select('item_id')->asArray()->column();

            $diffArr = \common\helpers\ArrayHelper::array2diff($have_ids,$list);
            $remove_ids = $diffArr['delete']??[];
            if (!empty($remove_ids)) {
                $model->removeChildren(['route' => $remove_ids]);
            }

            $add_ids = $diffArr['add']??[];
            if (!empty($add_ids)) {
                $model->addChildren(['route' => $add_ids]);
            }

            return ResultHelper::json(200, '操作成功');
        } elseif (key_exists('permission', $items)) {
            $list = $items['permission'];
            $remove_ids = AuthItemChild::find()->where([
                'parent_id' => $id,
                'child_type' => 1,
            ])->select('item_id')->asArray()->column();
            if (!empty($remove_ids)) {
                $model->removeChildren(['permission' => $remove_ids]);
            }

            if (!empty($list)) {
                $model->addChildren(['permission' => $list]);
            }

            return ResultHelper::json(200, '操作成功');
        }
        return ResultHelper::json(200, '操作成功');

    }

    /**
     * Assign items.
     *
     * @param string $id
     *
     * @return array
     */
    public function actionAssign(string $id): array
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findSelfModel($id);
        $success = $model->addChildren($items);
        if (!$success) {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(500, $msg);

        } else {
            return ResultHelper::json(200, '操作成功', array_merge($model->getItems(), ['success' => $success]));
        }
    }

    /**
     * Assign or remove items.
     *
     * @param string $id
     *
     * @return array
     */
    public function actionRemove(string $id): array
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findSelfModel($id);
        $success = $model->removeChildren($items);

        return ResultHelper::json(200, '移除成功', array_merge($model->getItems(), ['success' => $success]));
    }

    /**
     * {@inheritdoc}
     */
    public function getViewPath(): string
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'item';
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return array|AuthItem
     */
    protected function findSelfModel(int $id): array|AuthItem
    {
        $auth = Configs::authManager();
        $item = $auth->getPermission($id);

        if ($item) {
            $item->is_sys = 3;
            return new AuthItem($item);
        } else {
            return ResultHelper::json(500, '请检查数据是否存在');
        }
    }
}
