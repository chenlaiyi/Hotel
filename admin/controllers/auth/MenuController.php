<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-28 16:42:33
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 07:28:10
 */

namespace admin\controllers\auth;

use admin\controllers\AController;
use admin\models\auth\AuthRoute;
use admin\models\enums\MenuTypeEnum;
use admin\services\AuthService;
use common\components\ActiveRecord\YiiActiveRecord;
use common\helpers\ArrayHelper as HelpersArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use diandi\addons\models\searchs\DdAddons;
use diandi\admin\acmodels\AuthItem as AcmodelsAuthItem;
use diandi\admin\acmodels\AuthItemChild;
use diandi\admin\components\Configs;
use diandi\admin\components\Helper;
use diandi\admin\models\AuthItem;
use diandi\admin\models\Menu;
use diandi\admin\models\searchs\Menu as MenuSearch;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *
 * @since 1.0
 */
class MenuController extends AController
{
    public $modelClass = '';

    public string $modelSearchName = 'Menu';

    public int $searchLevel = 0;

    /**
     * Lists all Menu models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $DdAddons = new DdAddons();
        $addons = $DdAddons->find()->indexBy('identifie')->select(['title'])->asArray()->column();
        $addons['system'] = '系统';
        $dataProvider = HelpersArrayHelper::objectToarray($dataProvider);

        $parentMent = $dataProvider['allModels'];

        foreach ($parentMent as &$value) {
            $module_name = !empty($addons[$value['module_name']]) ? $addons[$value['module_name']] : '';
            $value['addons'] = $module_name;
            $value['label'] = $value['name'];
        }

        $list = HelpersArrayHelper::itemsMerge($parentMent, 0, 'id', 'parent', 'children');
        if (!empty($parentMent) && empty($list)) {
            $list = $parentMent;
        }

        return ResultHelper::json(200, '获取成功', [
            'list' => $list,
            'addons' => $addons,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    function actionTypes()
    {
        $list = MenuTypeEnum::listOptions();
        return ResultHelper::json(200, '获取成功', $list);
    }


    /**
     * Displays a single AuthItem model.
     *
     * @param string $id
     *
     * @return array
     */
    public function actionPermission($id): array
    {
        //        $this->menuRepair();
        $all = [];
        $permission_type = Yii::$app->request->input('permission_type', 0);
        $module_name = Yii::$app->request->input('module_name', '');
        $permissionId = \diandi\admin\acmodels\AuthItem::find()->where(['menu_id' => $id])->select('id')->scalar();
        if (empty($permissionId)) {
            return ResultHelper::json(400, '当前菜单没有权限数据');
        }
        $model = $this->findSelfModel($permissionId);
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

        $assignedAll['permission'] = $assignedAll['permission'] ?? [];

        if (!empty($assigned['route'])) {
            $assignedRoute = $assigned['route'];
            unset($assigned['route']);
            $assigned['route'] = $assigned['menu'] = $assigned['api'] = [];
            foreach ($assignedRoute as $item) {
                switch ($route_type[$item['item_id']] ?? 1) {
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
                switch ($route_type[$item['item_id']] ?? 1) {
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
        $all['permission'] = \common\helpers\ArrayHelper::itemsMerge($all['permission'], 0, 'id', 'parent_id', 'children');
        return ResultHelper::json(200, '获取成功', [
            'all' => $all,
            'permissionId' => $permissionId,
            'assigneds' => $assigned,
            'assignedKey' => $assignedAll,
            'availables' => $available,
        ]);
    }

    /**
     * 获取权限列表
     */
    public function actionPermissionList($id): array
    {
        $page = Yii::$app->request->input('page', 1);
        $pageSize  = Yii::$app->request->input('pageSize', 10);
        $route_type = Yii::$app->request->input('route_type', 1);
        //route_type	路由级别:0: 目录1: 页面 2: 按钮 3: 接口
        $keywords = Yii::$app->request->input('keywords', '');
        $selected_ids = Yii::$app->request->input('selected_ids', []);
        $module_name = Yii::$app->request->input('module_name', 'system');
        $query = \diandi\admin\acmodels\AuthRoute::find()->where(['route_type' => $route_type])->andWhere(['not in', 'item_id', $selected_ids]);
        if ($keywords) {
            $query->andWhere(['or', ['like', 'title', $keywords], ['like', 'name', $keywords]]);
        }
        if ($module_name) {
            $query->andWhere(['module_name' => $module_name]);
        }
        $count = $query->count();
        $query->offset(($page - 1) * $pageSize)->limit($pageSize);
        $list = $query->asArray()->all();


        return ResultHelper::json(200, '获取成功', [
            'total' => $count,
            'list' => $list
        ]);
    }

    /**
     * Displays a single Menu model.
     *
     * @param int $id
     *
     * @return array
     */
    public function actionView($id): array
    {
        try {
            $view = Menu::find()->where(['id' => $id])->asArray()->one();
            if (!$view) {
                return ResultHelper::json(400, '数据不存在');
            }
            $assigned = AuthService::getMenuPermission($id);
            $view['routes'] = $assigned['routes'] ?? [];
            $view['buttons'] = $assigned['buttons'] ?? [];
            $view['apis'] = $assigned['apis'] ?? [];

            return ResultHelper::json(200, '获取成功', $view);
        } catch (NotFoundHttpException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $model = new Menu();
        $data = Yii::$app->request->post();
        $data['parent'] = Yii::$app->request->post('parent', 0);
        $data['order'] = Yii::$app->request->post('order', 0);

        $module_name = Yii::$app->request->post('module_name', 'system');

        $data['is_sys'] = $module_name === 'system' ? 1 : 0;
        $route_id = Yii::$app->request->post('route_id', 0);
        $data['route'] = AuthRoute::find()->where(['id' => $route_id])->select('name')->scalar();

        if ($model->load($data, '') && $model->save()) {
            Helper::invalidate();
            $menu_id = $model->id;

            $menu_item_id = $model->item_id;
            $apis = Yii::$app->request->post('apis', []);
            $buttons = Yii::$app->request->post('buttons', []);
            $routes = Yii::$app->request->post('routes', []);
            AuthService::writeMenuPermission($menu_id, $apis, $buttons, $routes);
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    public function actionLevels(): array
    {
        $addons = DdAddons::find()->asArray()->all();
        $parentMent = Menu::find()->where(['is_sys' => 1])->asArray()->all();
        $parentMenu = HelpersArrayHelper::itemsMergeDropDown(HelpersArrayHelper::itemsMerge($parentMent, 0, 'id', 'parent'), 'id', 'name');

        return ResultHelper::json(200, '获取成功', [
            'addons' => $addons,
            'parentMenu' => $parentMenu,
        ]);
    }

    public function actionRoute(): array
    {
        $name = \Yii::$app->request->input('name') ?? '';
        $module_name = \Yii::$app->request->input('module_name') ?? '';
        $route_type = \Yii::$app->request->input('route_type') ?? 0;
        $limit = \Yii::$app->request->input('limit') ?? 0;

        $where = [];
        if (!empty($name)) {
            $li = explode('/', $name);
            $likeName = str_replace(end($li), '', $name);
            $where = ['like', 'name', $likeName];
        }

        if (!empty($module_name)) {
            $where['module_name'] = $module_name;
        }

        if (!empty($route_type)) {
            $where['route_type'] = $route_type;
        }

        $query = AuthRoute::find()->where($where)->orderBy('name')->select(['name as label', 'id', 'name', 'pid']);

        if (!empty($limit)) {
            $query->limit($limit);
        }

        $lists = $query->asArray()->all();

        // $lists = HelpersArrayHelper::itemsMerge($list, 0, "id", 'pid', 'childen');

        return ResultHelper::json(200, '获取成功', $lists);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id): array
    {

        $model = $this->findModel($id);

        $data = Yii::$app->request->post();
        $data['route'] = AuthRoute::find()->where(['id' => \Yii::$app->request->input('route_id')])->select('name')->scalar();

        if ($model->menuParent) {
            $model->parent_name = $model->menuParent->name;
        }

        if ($model->load($data, '') && $model->save()) {
            Helper::invalidate();
            $menu_id = $model->id;
            $apis = Yii::$app->request->post('apis', []);
            $buttons = Yii::$app->request->post('buttons', []);
            $routes = Yii::$app->request->post('routes', []);
            AuthService::writeMenuPermission($menu_id, $apis, $buttons, $routes);
            return ResultHelper::json(200, '更新成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    public function actionUpdateFiles(): array
    {

        $pk = Yii::$app->request->post('pk');
        $id = unserialize(base64_decode($pk));

        $model = $this->findModel($id);

        $files = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $Res = $model->updateAll([$files => $value], ['id' => $id]);

        return ResultHelper::json(200, '上传成功', [
            'Res' => $Res
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return array
     */
    public function actionDelete($id): array
    {
        try {
            $Res = $this->findModel($id)->delete();

            Helper::invalidate();

            return ResultHelper::json(200, '删除成功');
        } catch (StaleObjectException | Exception $e) {
            return ResultHelper::json(500, $e->getMessage());
        } catch (Throwable $e) {
            return ResultHelper::json(500, $e->getMessage());
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return array|YiiActiveRecord|ActiveRecord
     */
    protected function findModel($id): array|YiiActiveRecord|ActiveRecord
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            return ResultHelper::json(500, '请检查数据是否存在');
        }
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
