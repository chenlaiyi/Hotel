<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-04 17:44:12
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-18 14:04:10
 */

namespace admin\controllers\auth;

use admin\controllers\AController;
use admin\models\BlocAddons;
use admin\services\AuthService;
use admin\services\UserService;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use diandi\admin\acmodels\AuthItem as AcmodelsAuthItem;
use diandi\admin\acmodels\AuthItemChild;
use diandi\admin\acmodels\AuthRoute;
use diandi\admin\acmodels\AuthUserGroup;
use diandi\admin\components\Configs;
use diandi\admin\components\Item;
use diandi\admin\components\Route;
use diandi\admin\models\AuthItem;
use diandi\admin\models\Menu;
use diandi\admin\models\Route as ModelsRoute;
use diandi\admin\models\searchs\UserGroupSearch;
use diandi\admin\models\UserGroup;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * GroupController implements the CRUD actions for UserGroup model.
 */
class GroupController extends AController
{
    public string $modelSearchName = 'UserGroupSearch';

    public int $searchLevel = 0;

    /**
     * Lists all UserGroup models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new UserGroupSearch();
//        $searchModel->is_sys = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    function actionInit(): array
    {
        $is_sys = Yii::$app->request->get('is_sys', 1);
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        if ($is_sys == 0) {

            $list = AuthUserGroup::find()->where(['bloc_id' => $bloc_id, 'is_sys' => $is_sys])->select(['id', 'id as value', 'name', 'name as text'])->asArray()->all();

        } else {
            $list = AuthUserGroup::find()->where(['is_sys' => $is_sys, 'bloc_id' => $bloc_id])->select(['id', 'id as value', 'name', 'name as text'])->asArray()->all();
        }
        return ResultHelper::json(200, '获取成功', $list);
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
        $is_sys = $model->is_sys;

        $available = $list['available'];
        $module_name = [];
        if ($is_sys === 0) {
            $bloc_id = $model->bloc_id;
            $module_name = BlocAddons::find()->where(['bloc_id' => $bloc_id])->select('module_name')->asArray()->column();
        }
        foreach ($list['all'] as $key => $value) {
            $value = ArrayHelper::toArray($value);

            foreach ($value as $k => &$val) {
                $val['key'] = $val['id'];
                $val['label'] = $val['name'];
                if ($key == 'role' && $val['name'] !== '基础权限组') {
                    if ($val['id'] == $id || $val['is_sys'] !== $is_sys) {
                        unset($value[$k]);
                    }

                    if ($is_sys === 0 && !in_array($val['module_name'], $module_name)) {
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

        $all['permission'] = AuthService::authGroupPermissions(0); // ArrayHelper::itemsMerge($all['permission'], 0, 'id', 'parent_id', 'children');

        /**
         *
         * 查找auth_item表中permission_type 为0的权限
         *
         */
        $assignedKey = \diandi\admin\acmodels\AuthItem::find()->where(['permission_type' => 0, 'id' => $assigneds['route'] ?? []])->select('id')->column();
        return ResultHelper::json(200, '获取成功', [
                'all' => $all,
                'assigneds' => $assignedP,
                'assignedKey' => $assignedKey,
                'availables' => $available,
            ]
        );
    }


    public function actionChange(): array
    {
        $id = \Yii::$app->request->input('id');
        $items = \Yii::$app->request->input('items');
        $type = \Yii::$app->request->input('type');
        if (empty($id)) {
            return ResultHelper::json(400, '参数ID不能为空');
        }
        $module_name = \Yii::$app->request->input('module_name');
        $Res = AuthService::changeGroupAuth($id,$type, $items,$module_name);
        return ResultHelper::json(200, '操作成功',$Res);

    }

    /**
     * Assign items.
     *
     * @param string $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionAssign(string $id): array
    {
        try {

            $manager = Configs::authManager();

            $items = Yii::$app->getRequest()->input('items', []);

            $model = $this->getGroups($id);

            $success = 0;

            // 用户组
            if (isset($items['role'])) {
                $success += $model->addChildren($items['role']);
            }

            // 权限
            if (isset($items['permission'])) {
                $item = new Item([
                        'id' => $id,
                        'item_id' => $model['item_id'],
                        'name' => $model['name'],
                        'is_sys' => $model['is_sys'],
                        'parent_id' => null,
                        'child_type' => 1,
                        'ruleName' => '',
                        'description' => $model['description'],
                        'data' => '',
                    ]
                );
                $permission = new AuthItem($item);
                $ok = $permission->addChildren($items, 2);
                if (is_int($ok)) {
                    $success += $ok;
                } else {
                    throw new Exception($ok);
                }
            }
            // 路由
            if (isset($items['route'])) {
                $item = new Route([
                        'id' => $id,
                        'name' => $model['name'],
                        'item_id' => $model['item_id'],
                        'title' => '',
                        'is_sys' => $model['is_sys'],
                        'child_type' => 0,
                        'description' => $model['description'],
                        'data' => '',
                        'pid' => 0,
                    ]
                );
                $route = new ModelsRoute($item);
                $success += $route->addChildren($items['route'], 2);
            }

            Yii::$app->getResponse()->format = 'json';

            $items = $manager->getAuths($model['name']);

            return array_merge($items, ['success' => $success]);
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    /**
     * Assign or remove items.
     *
     * @param string $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionRemove(string $id): array
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->getGroups($id);
        $success = 0;

        // 规则
        if ($items['group']) {
            $success += $model->removeChildren($items);
        }

        // 权限
        if ($items['permission']) {
            $item = new Item([
                    'name' => $model['name'],
                    'is_sys' => $model['is_sys'],
                    'parent_id' => null,
                    'child_type' => 1,
                    'ruleName' => '',
                    'description' => $model['description'],
                    'data' => '',
                ]
            );
            $permission = new AuthItem($item);
            $success += $permission->removeChildren($items);
        }

        // 路由
        if ($items['route']) {
            $item = new Route([
                    'name' => $model['name'],
                    'title' => '',
                    'is_sys' => $model['is_sys'],
                    'child_type' => 0,
                    'description' => $model['description'],
                    'data' => '',
                    'pid' => 0,
                ]
            );
            $route = new ModelsRoute($item);
            $success += $route->removeChildren($items);
        }

        Yii::$app->getResponse()->format = 'json';
        $manager = Configs::authManager();

        $items = $manager->getAuths($model['name']);

        return array_merge($items, ['success' => $success]);
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
        $data['is_sys'] = 1;
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
                    ]
                );
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

    /**
     * Updates an existing UserGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return array
     *
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionUpdate($id): array
    {
        $model = UserGroup::findOne($id);

        $old_parent = $model->name;


        $data = Yii::$app->request->input();

        if ($model->load($data, '') && $model->save()) {
            if ($old_parent != \Yii::$app->request->input('name')) {
                AuthItemChild::updateAll([
                    'parent' => \Yii::$app->request->input('name'),
                ], [
                        'parent_type' => 2,
                        'parent_id' => $model->item_id,
                    ]
                );
            }

            // 给item同步添加数据
            $AcmodelsAuthItem = new AcmodelsAuthItem();
            $items = [
                'permission_type' => 2,
                'name' => $model->name,
                'is_sys' => $model->is_sys,
                'parent_id' => 0,
                'permission_level' => 0,
            ];
            // 首先查询是否存在
            $isHave = $AcmodelsAuthItem->find()->where(['id' => $model->item_id])->one();
            if ($isHave) {
                $AcmodelsAuthItem->updateAll($items, [
                        'name' => $model->name,
                        'id' => $model->item_id,
                    ]
                );
            } else {
                $AcmodelsAuthItem->load($items, '') && $AcmodelsAuthItem->save();
                // 修复item_id被删除的情况
                $model->item_id = $AcmodelsAuthItem->id;
                $model->update();
            }
            /**
             * 作为父级权限是否存在
             */
            $parent = AuthItemChild::find()->where(['parent_id' => $id, 'parent_type' => 2])->exists();
            if ($parent) {
                // 修复子权限不对应的情况
                AuthItemChild::updateAll([
                    'parent_item_id' => $model->item_id,
                    'parent' => \Yii::$app->request->input('name'),
                ], [
                        'parent_type' => 2,
                        'parent_id' => $id,
                    ]
                );
            }

            /**
             * 作为子集权限是否存在
             */
            $child = AuthItemChild::find()->where(['item_id' => $model->item_id, 'child_type' => 2])->exists();
            if ($child) {
                // 修复子权限不对应的情况
                AuthItemChild::updateAll([
                    'child' => \Yii::$app->request->input('name'),
                ], [
                        'child_type' => 2,
                        'item_id' => $model->item_id
                    ]
                );
            }

            AuthService::authGroupBase($model->id);

            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Deletes an existing UserGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return array
     *
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete($id): array
    {
        $userGroup = UserGroup::findOne($id);
        $userGroup->delete();
        \diandi\admin\acmodels\AuthItemChild::deleteAll([
                'or',
                ['parent_item_id' => $userGroup->item_id],
                ['item_id' => $userGroup->item_id],
            ]
        );
        \diandi\admin\acmodels\AuthItem::deleteAll([
                'id' => $userGroup->item_id,
            ]
        );
        return ResultHelper::json(200, '删除成功');
    }


    /**
     * Finds the UserGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return UserGroup the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function getGroups(int $id): UserGroup
    {
        if (($model = UserGroup::findOne($id)) !== null) {
            return new UserGroup($model);
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
