<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 14:18:08
 */


namespace admin\services;

use admin\models\addons\models\Bloc;
use admin\models\auth\AuthRoute;
use admin\models\BlocAddons;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\DdUser;
use common\models\User;
use common\models\UserBloc;
use common\models\UserStore;
use common\plugins\diandi_auth\models\BlocConfAppNav;
use common\services\BaseService;
use diandi\addons\models\BlocStore;
use diandi\addons\models\DdAddons;
use diandi\admin\acmodels\AuthAssignmentGroupMenu;
use diandi\admin\acmodels\AuthItem as AcmodelsAuthItem;
use diandi\admin\acmodels\AuthItemChild;
use diandi\admin\components\Configs;
use diandi\admin\components\Helper;
use diandi\admin\components\Item;
use diandi\admin\components\Route;
use diandi\admin\models\AuthAssignmentGroup;
use diandi\admin\models\AuthItem;
use diandi\admin\models\Menu;
use diandi\admin\models\Route as ModelsRoute;
use diandi\admin\models\UserGroup;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\caching\DbDependency;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class AuthService extends BaseService
{
    /**
     * 给用户组授权应用权限
     *
     * @throws ErrorException
     */
    public static function authApp($group_id, $module_name = []): int|ErrorException
    {
        try {
            $model = self::getGroup($group_id);
            /**
             * 获取所有权限，然后过滤
             */
            $authPermission = \diandi\admin\acmodels\AuthItem::find()->where([
                    'module_name'     => $module_name,
                    'permission_type' => 0 //权限属性：0route1permission2role
                ]
            )->select('id')->column();
            $item           = new Item([
                    'id'          => $group_id,
                    'item_id'     => $model->item_id,
                    'name'        => $model->name,
                    'is_sys'      => $model->is_sys,
                    'parent_id'   => null,
                    'child_type'  => 0,
                    'ruleName'    => '',
                    'description' => $model->description,
                    'data'        => '',
                ]
            );

            $permission = new AuthItem($item);
            return $permission->addChildren([
                'route' => array_unique($authPermission)
            ], 2
            );
        } catch (\Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * 获取用户组信息
     *
     * @param $id
     * @return UserGroup
     * @throws \Exception
     */
    public static function getGroup($id): UserGroup
    {
        if (($model = UserGroup::findOne($id)) === null) {
            throw new \Exception('用户组不存在');
        }

        return new UserGroup($model);
    }

    public static function getGroupByBlocId($bloc_id): array
    {
        return UserGroup::find()->where(['bloc_id' => $bloc_id])->asArray()->all();
    }

    /**
     * 获取用户组权限集合
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    public static function getGroupAuth($id): array
    {
        $model     = self::getGroup($id);
        $manager   = Configs::authManager();
        $list      = $manager->getAuths($model->item_id);
        $all       = [];
        $assigneds = [];
        $assignedP = $list['assigned'];
        $type      = $model->is_sys;

        $available = $list['available'];

        foreach ($list['all'] as $key => $value) {
            $value = ArrayHelper::toArray($value);
            foreach ($value as $k => &$val) {
                $val['key']   = $val['id'];
                $val['label'] = $val['name'];
                if (($key == 'role' && $val['id'] == $id && $val['name'] !== '基础权限组') || ($key == 'role' && $val['is_sys'] != $type && $val['name'] !== '基础权限组')) {
                    unset($value[$k]);
                }
            }
            $all[$key] = array_values($value);
        }


        foreach ($available as $key => $value) {
            $value = ArrayHelper::toArray($value);
            foreach ($value as &$val) {
                $val['key']   = $val['id'];
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
                $val['key']        = $val['id'];
                $val['label']      = $val['name'];
                $assigneds[$key][] = $val['item_id'];
            }

            $assignedP[$key] = array_values($value);
            unset($value);
        }

        $all['permission'] = ArrayHelper::itemsMerge($all['permission'], 0, 'id', 'parent_id', 'children');


        return $all;
    }

    /**
     * 设置用户为非系统用户
     */
    public static function authUserUnSys($user_id): void
    {
        $model = User::findOne($user_id);
        if ($model->is_sys == 1) {
            $model->is_sys = 0;
            try {
                $model->update();
            } catch (StaleObjectException $e) {
                throw $e;
            } catch (\Throwable $e) {
                throw $e;
            }
        }
    }

    /**
     * 授权用户store_id
     */
    public static function authStore($user_id, $store_id = [], $is_create = false): void
    {
        /**
         * 用用户授权
         */
        $addList = BlocStore::find()->where(['store_id' => $store_id])->asArray()->all();

        $UserStore = new UserStore();
        if (!$is_create) {
            $UserStore::deleteAll([
                    'user_id' => $user_id,
                ]
            );
        }
        foreach ($addList as $value) {
            $_UserStore = clone $UserStore;
            $data       = [
                'user_id'  => $user_id,
                'bloc_id'  => $value['bloc_id'],
                'store_id' => $value['store_id'],
                'status'   => 0,
            ];
            $_UserStore->setAttributes($data);
            if (!$_UserStore->save()) {
                $msg = ErrorsHelper::getModelError($_UserStore);
                throw new \Exception($msg);
            }
        }
    }


    /**
     * 授权用户bloc_id
     */
    public static function authUserBloc($user_id, $bloc_id = []): void
    {
        /**
         * 用用户授权
         */
        $addList = Bloc::find()->where(['bloc_id' => $bloc_id])->asArray()->all();

        $userBloc = new UserBloc();
        foreach ($addList as $value) {
            $_UserBloc = clone $userBloc;
            $data      = [
                'user_id' => $user_id,
                'bloc_id' => $value['bloc_id'],
            ];
            $_UserBloc->setAttributes($data);
            if (!$_UserBloc->save()) {
                $msg = ErrorsHelper::getModelError($_UserBloc);
                throw new \Exception($msg);
            }
        }
    }

    /**
     * 授权用户组基础权限
     */
    public static function authGroupBase($group_id): void
    {
        /**
         * 获取yii基础权限组
         */
        $base_group_id = UserGroup::find()->where(['name' => '基础权限组'])->select('item_id')->scalar();
        $list          = [
            $base_group_id
        ];
        $group         = UserGroup::findOne($group_id);
        $model         = new UserGroup($group);

        $haveBase = AuthItemChild::find()->where([
                'parent_id'  => $group_id,
                'child_type' => 2,
                'item_id'    => $base_group_id
            ]
        )->exists();

        if (!$haveBase) {
            $model->addChildren(['group' => $list]);
        }
    }

    /**
     * 根据模块和按钮重新组装授权权限数据
     */
    public static function authGroupPermission($permission = [], $is_sys = 1): array
    {
        if ($is_sys === 1) {
            $ddaddons    = DdAddons::find()->indexBy('identifie')->asArray()->all();
            $module_name = array_keys($ddaddons);
        } else {
            $bloc_id     = \Yii::$app->request->input('bloc_id', 0);
            $module_name = BlocAddons::find()->where(['bloc_id' => $bloc_id])->select('module_name')->column();
            $ddaddons    = DdAddons::find()->where(['identifie' => $module_name])->indexBy('identifie')->asArray()->all();
        }
        $module_name_str = "'" . implode("','", $module_name) . "'";
        $permissionsSql  = "SELECT
	r.item_id,
	r.route_name,
	r.title,
	m.item_id as menu_item_id
FROM
	{{%auth_menu}} AS m
	LEFT JOIN {{%auth_item_child}} AS c ON m.item_id = c.parent_item_id
	LEFT JOIN {{%auth_route}} AS r ON c.item_id = r.item_id";
        $permissions     = Yii::$app->db->createCommand($permissionsSql)->queryAll();
        foreach ($permissions as $key => $value) {
            if ($value['item_id']) {
                $permissionsLevel[$value['menu_item_id']][] = $value;
            }
        }
        $menuSql = "SELECT
	*
FROM
	{{%auth_menu}} 
WHERE
	module_name IN ({$module_name_str})";
        $menus   = Yii::$app->db->createCommand($menuSql)->queryAll();
        $list    = [];
        foreach ($menus as &$menu) {
            $menu['permissions'] = $permissionsLevel[$menu['item_id']] ?? [];
            if (!empty($menu['permissions'])) {
                $list[$menu['module_name']][] = $menu;
            }
        }
        $lists = [];
        foreach ($list as $module_name => $item) {
            $menuLevel                      = self::authGroupMenu($item);
            $ddaddons[$module_name]['menu'] = $menuLevel;
            $lists[$module_name]            = $ddaddons[$module_name];
        }
        return array_values($lists);
    }

    /**
     * 根据模块和按钮重新组装授权权限数据
     */
    public static function authGroupPermissions($is_sys = 1): array
    {
        if ($is_sys === 1) {
            $ddaddons = DdAddons::find()->indexBy('identifie')->asArray()->all();

            $module_name = array_keys($ddaddons);
        } else {
            $bloc_id     = \Yii::$app->request->input('bloc_id', 0);
            $module_name = BlocAddons::find()->where(['bloc_id' => $bloc_id])->select('module_name')->column();
            $ddaddons    = DdAddons::find()->where(['identifie' => $module_name])->indexBy('identifie')->asArray()->all();
        }
        $module_name_str = "'" . implode("','", $module_name) . "'";
//        $permissionsSql  = "SELECT
//        r.item_id,
//        r.route_name,
//        r.title,
//        m.item_id as menu_item_id
//    FROM
//        {{%auth_menu}} AS m
//        LEFT JOIN {{%auth_item_child}} AS c ON m.item_id = c.parent_item_id
//        LEFT JOIN {{%auth_route}} AS r ON c.item_id = r.item_id";
        $permissionsSql = "SELECT
        r.item_id,
        r.route_name,
        r.title,
        m.item_id as menu_item_id
    FROM
        {{%auth_menu}} AS m
        LEFT JOIN {{%auth_item}} AS i ON m.id = i.menu_id
        LEFT JOIN {{%auth_route}} AS r ON i.id = r.item_id 
    WHERE r.route_type > 1    
        ";

//      echo  $permissions     = Yii::$app->db->createCommand($permissionsSql)->getRawSql();die;

        $permissions = Yii::$app->db->createCommand($permissionsSql)->queryAll();
        foreach ($permissions as $key => $value) {
            $permissionsLevel[$value['menu_item_id']][] = $value;
        }

        $menuSql = "SELECT
        *
    FROM
        {{%auth_menu}} 
    WHERE
        module_name IN ({$module_name_str})";

        $menus = Yii::$app->db->createCommand($menuSql)->queryAll();
        $list  = [];
        foreach ($menus as &$menu) {
            $menu['title']       = $menu['name'];
            $menu['text']        = $menu['name'];
            $menu['value']       = $menu['item_id'] ?? '';
            $permissions         = $permissionsLevel[$menu['item_id']] ?? [];
            $menu['permissions'] = [];
            foreach ($permissions as $key => $value) {
                if ($value['item_id']) {
                    $value['name']  = $value['title'];
                    $value['text']  = $value['title'];
                    $value['value'] = $value['item_id'] ?? '';

                    $menu['permissions'][] = $value;
                    $menu['children'][]    = $value;
                }
            }
            $list[$menu['module_name']][] = $menu;
        }
        $lists = [];
        foreach ($list as $module_name => $item) {
            $menuLevel                      = self::authGroupMenu($item);
            $ddaddons[$module_name]['menu'] = $menuLevel;
            $lists[$module_name]            = $ddaddons[$module_name];
        }
        return array_values($lists);
    }

    /**
     * 简化菜单组装
     */
    public static function authGroupMenu($menu = []): array
    {
        return ArrayHelper::itemsMerge($menu, 0, 'id', 'parent', 'children');
    }

    /**
     * 修复菜单没有写入item的数据
     */
    public static function menuRepair()
    {
        try {
            /**
             * 获取所有菜单
             */
            $menus  = Menu::find()->all();
            $modela = new AcmodelsAuthItem();

            foreach ($menus as $menu) {
                $model = clone $modela;
                //保存后创建权限item数据
                $parent_id = $menu->find()->where(['id' => $menu->parent])->select('item_id')->scalar();
                $data      = [
                    'name'             => $menu->name,
                    'is_sys'           => $menu->module_name === 'system' ? 1 : 0,
                    'permission_type'  => 1,
                    'description'      => '',
                    'rule_name'        => 0,
                    'menu_id'          => $menu->id,
                    'parent_id'        => (int) $parent_id,
                    'permission_level' => 1,    //权限级别:0: 目录1: 页面 2: 按钮 3: 接口
                    'module_name'      => $menu->module_name,
                ];
                $item_id   = $menu->item_id;
                $have      = $model::find()->where(['id' => $item_id])->one();
                if (!$have) {
                    if ($model->load($data, '') && $model->save()) {
                        $menu->item_id = $model->id;
                        $menu->save();
                    } else {
                        $msg = ErrorsHelper::getModelError($model);
                        throw new ErrorException($msg, 400);
                    }
                } else {
                    if ($have->menu_id != $menu->id) {
                        $have->menu_id = $menu->id;
                        $have->save();
                    }
                }


                //权限处理
                $id                = $menu->item_id;
                $route_id          = $menu->route_id;
                $item_id           = \diandi\admin\acmodels\AuthRoute::find()->where(['id' => $route_id])->select('item_id')->scalar();
                $items['route'][0] = $item_id;
                $modelPermission   = $menu->getPermission($id);
                if ($modelPermission) {
                    $list       = $items['route'];
                    $remove_ids = AuthItemChild::find()->where([
                            'parent_id'  => $id,
                            'child_type' => 0,
                        ]
                    )->andWhere(['not in', 'item_id', $list])->select('item_id')->asArray()->column();

                    if (!empty($remove_ids)) {
                        $modelPermission->removeChildren(['route' => $remove_ids]);
                    }

                    $have_ids = AuthItemChild::find()->where([
                            'parent_id'  => $id,
                            'child_type' => 0,
                        ]
                    )->select('item_id')->asArray()->column();

                    $add_ids = array_diff($list, $have_ids);
                    if (!empty($add_ids)) {
                        $modelPermission->addChildren(['route' => $add_ids]);
                    }
                }
            }
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }


    /**
     * 过滤授权的key
     */

    /**
     * 根据当前权限获取父级权限的按钮ID并处理
     *
     * @param $permission_id
     * @return void
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function upParentPermissionMenuId($permission_id): void
    {
        $permission = \diandi\admin\acmodels\AuthItem::findOne($permission_id);
        $parent_id  = $permission->parent_id;
        $menu_id    = \diandi\admin\acmodels\AuthItem::find()->where(['id' => $parent_id])->select('menu_id')->scalar();
        if ($parent_id && $menu_id) {
            \diandi\admin\acmodels\AuthItem::updateAll(['menu_id' => $menu_id], ['parent_id' => $parent_id]);
        }
    }

    public static function xfMenuIitem()
    {
        //        api/diandi_auth/base/xf-menu-item?bloc_id=1&store_id=1
        /**
         * 获取所有菜单
         */
        $menus  = Menu::find()->all();
        $routes = \diandi\admin\acmodels\AuthRoute::find()->indexBy('id')->select(['id', 'name', 'item_id'])->all();

        foreach ($menus as $menu) {
            $route_id = $menu->route_id;
            $item_id  = $routes[$route_id]['item_id'];
            $menu_id  = $menu->id;
            \diandi\admin\acmodels\AuthItem::updateAll(['menu_id' => $menu_id], ['id' => $item_id]);
        }
    }

    /**
     * 按钮导入到菜单里面
     *
     * @return void
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    static function xfItenmToMenu()
    {
        try {
            $menus  = new Menu();
            $routes = \diandi\admin\acmodels\AuthRoute::find()->where(['route_type' => 2])->andWhere(['!=', 'module_name', 'system'])->indexBy('id')->select(['id', 'module_name', 'name', 'item_id', 'title'])->all();

            foreach ($routes as $route) {
                $_menus = clone $menus;
                $have   = $_menus::find()->where(['route_id' => $route['id']])->exists();
                if (!$have) {
                    $parent = $_menus::find()->where(['module_name' => $route['module_name']])->select('id')->scalar();
                    $data   = [
                        'name'        => $route['title'] ?: '未知',
                        'parent'      => $parent,
                        'route_id'    => $route['id'],
                        'route'       => $route['name'],
                        'order'       => 0,
                        'type'        => '',
                        'level_type'  => 3,
                        'is_sys'      => $route['module_name'] === 'system' ? 1 : 0,
                        'module_name' => $route['module_name'],
                        'is_show'     => 0
                    ];
                    $_menus->load($data, '');

                    if (!$_menus->save()) {
                        $msg = ErrorsHelper::getModelError($_menus);
                        throw new ErrorException($msg, 400);
                    }
                }
            }
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }


    /**
     * 一个组权限被改变，清除这个组所有人的缓存
     */
    public static function clearUserGroupCache($group_id): void
    {
        $user_ids = AuthAssignmentGroup::find()->where(['group_id' => $group_id])->select('user_id')->column();
        foreach ($user_ids as $user_id) {
            UserService::deleteUserCache($user_id);
        }
    }

    /**
     * 获取用户插件权限
     */
    public static function getUserPluginPermission($user_id): array
    {
        $is_admin    = UserService::isSuperAdmin($user_id);
        $isBessRoles = UserService::isbusinessRoles($user_id);
        if ($is_admin) {
            /**
             * 总管理员
             */
            $bloc_id = Yii::$app->request->input('bloc_id');
            $addons  = BlocAddons::find()->where(['bloc_id' => $bloc_id])->with([
                    'addons' => function ($query) {
                        return $query->where(['is_nav' => 1])->orderBy('displayorder');
                    }
                ]
            )->asArray()->all();
            $list    = [];
            foreach ($addons as $addon) {
                if ($addon['addons']) {
                    $list[] = $addon['addons'];
                }
            }
            $list = self::arraySortByKey($list, 'displayorder');
            return $list;
        } else if ($isBessRoles) {
            /**
             * 超级管理员
             */
            $bloc_id = Yii::$app->request->input('bloc_id');

            $addons = BlocAddons::find()->where(['bloc_id' => $bloc_id])->with([
                    'addons' => function ($query) {
                        return $query->where(['is_nav' => 1])->orderBy('displayorder');
                    }
                ]
            )->asArray()->all();
            $list   = [];
            foreach ($addons as $addon) {
                if ($addon['addons']) {
                    $list[] = $addon['addons'];
                }
            }
            $list = self::arraySortByKey($list, 'displayorder');

            return $list;
        } else {
            /**
             * 普通用户
             */
            $bloc_id    = User::find()->where(['id' => $user_id])->select('bloc_id')->scalar();
            $addons     = BlocAddons::find()->where(['bloc_id' => $bloc_id])->with([
                    'addons' => function ($query) {
                        return $query->where(['is_nav' => 1])->orderBy('displayorder');
                    }
                ]
            )->asArray()->all();
            $list       = [];
            $authAddons = self::getRouteAddonsByUser($user_id);
            foreach ($addons as $addon) {
                if ($addon['addons'] && in_array($addon['addons']['identifie'], $authAddons)) {
                    $list[] = $addon['addons'];
                }
            }
            $list = self::arraySortByKey($list, 'displayorder');

            return $list;
        }
    }

    /**
     * 获取用户权限插件
     *
     * @param $user_id
     * @return array
     */
    public static function getRouteAddonsByUser($user_id)
    {
        $config = Configs::instance();
        /* @var $manager \yii\rbac\BaseManager */
        $manager   = $config::authManager();
        $routeItem = $manager->getPermissionsByUser($user_id);
        $item_ids  = array_column($routeItem, 'item_id');
        loggingHelper::writeLog('AuthService', 'getRouteAddonsByUser', '获取用户权限范围内的插件', [
            'item_ids' => $item_ids,
            'sql'      => \diandi\admin\acmodels\AuthRoute::find()->where(['item_id' => $item_ids])->select('module_name')->groupBy('module_name')
                ->createCommand()->getRawSql()
        ]);
        $route_module_name = \diandi\admin\acmodels\AuthRoute::find()->where(['item_id' => $item_ids])->select('module_name')->groupBy('module_name')->column();
        /**
         * 部分插件没有route，需要获取
         */
        $item_module_name = \diandi\admin\acmodels\AuthItem::find()->where(['id' => $item_ids])->select('module_name')->groupBy('module_name')->column();
        /**
         * 合并且去重
         */
        $module_name = array_merge($route_module_name, $item_module_name);
        return array_unique($module_name);
    }

    /**
     * 对多维数组按照指定键的值进行排序
     *
     * @param array $array 待排序的多维数组
     * @param string $key 排序依据的键名
     * @param int $sortFlags 排序标志，默认为SORT_ASC（升序）
     * @return array 排序后的数组
     */
    public static function arraySortByKey(array $array, string $key, int $sortFlags = SORT_ASC)
    {
        if (empty($array)) {
            return $array;
        }

        // 使用usort对数组进行排序
        usort($array, function ($a, $b) use ($key, $sortFlags) {
            // 处理键不存在的情况
            if (!array_key_exists($key, $a) || !array_key_exists($key, $b)) {
                return 0;
            }

            return $sortFlags === SORT_ASC
                ? $a[$key] <=> $b[$key]
                : $b[$key] <=> $a[$key];
        }
        );

        return $array;
    }

    public static function writeMenuPermissionOne($menu_id, $apis = [], $buttons = [], $routes = [])
    {
//        $item_id = Menu::find()->where(['id' => $menu_id])->select('item_id')->scalar();

        //        路由级别:0: 目录1: 页面 2: 按钮 3: 接口
        if ($apis) {
            self::updateRouteTitle($apis);
            /**
             * 清楚和当前菜单有关的所有item表里面的menu_id
             */

//            \diandi\admin\acmodels\AuthItem::updateAll([
//                'menu_id' => 0,
//            ], [
//                    'and', [
//                        'menu_id'          => $menu_id,
//                        'permission_level' => 3
//                    ], [
//                        '!=', 'id', $item_id
//                    ]
//                ]
//            );

            $apis_ids = array_column($apis, 'item_id');


            /**
             * 将当前的menu_id 更新给所有的api对应的item表
             */
            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => $menu_id,
            ], [
                    'id' => $apis_ids,
                ]
            );

        }

        if ($buttons) {
            self::updateRouteTitle($buttons);
//            \diandi\admin\acmodels\AuthItem::updateAll([
//                'menu_id' => 0,
//            ], [
//                    'and', ['menu_id' => $menu_id, 'permission_level' => 2], ['!=', 'id', $item_id]
//                ]
//            );

            $buttons_ids = array_column($buttons, 'item_id');

            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => $menu_id,
            ], [
                    'id' => $buttons_ids,
                ]
            );
        }

        if ($routes) {
            self::updateRouteTitle($routes);

//            \diandi\admin\acmodels\AuthItem::updateAll([
//                'menu_id' => 0,
//            ], [
//                    'and', ['menu_id' => $menu_id, 'permission_level' => 1], ['!=', 'id', $item_id]
//                ]
//            );

            $routes_ids = array_column($routes, 'item_id');
            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => $menu_id,
            ], [
                    'id' => $routes_ids,
                ]
            );

        }
    }

    /**
     * 写入菜单权限数据
     */
    public static function writeMenuPermission($menu_id, $apis = [], $buttons = [], $routes = [])
    {
        $item_id = Menu::find()->where(['id' => $menu_id])->select('item_id')->scalar();
//        $auth         = Configs::authManager();
//        $item         = $auth->getPermission($item_id);

//        $item->is_sys = 3;

//        $model = new AuthItem($item);
        //        路由级别:0: 目录1: 页面 2: 按钮 3: 接口
        if ($apis) {
            self::updateRouteTitle($apis);
            /**
             * 清楚和当前菜单有关的所有item表里面的menu_id
             */

            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => 0,
            ], [
                    'and', [
                        'menu_id'          => $menu_id,
                        'permission_level' => 3
                    ], [
                        '!=', 'id', $item_id
                    ]
                ]
            );

            $apis_ids = array_column($apis, 'item_id');


            /**
             * 将当前的menu_id 更新给所有的api对应的item表
             */
            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => $menu_id,
            ], [
                    'id' => $apis_ids,
                ]
            );

//            var_dump($apis_ids,$menu_id);die;

//            $route_type = 3;
//            $list       = array_column($apis, 'item_id');
//            $have_ids   = AuthItemChild::find()->where([
//                    'parent_item_id'  => $item_id,
//                    'child_type' => 0,
//                    'route_type' => $route_type
//                ]
//            )->select('item_id')->asArray()->column();

//            $diffArr    = \common\helpers\ArrayHelper::array2diff($have_ids, $list);
//            $remove_ids = $diffArr[ 'delete' ] ?? [];
//            if (!empty($remove_ids)) {
//                $model->removeChildren(['route' => $remove_ids]);
//            }
//
//            $add_ids = $diffArr[ 'add' ] ?? [];
//            if (!empty($add_ids)) {
//                $model->addChildren(['route' => $add_ids]);
//            }
        }

        if ($buttons) {
            self::updateRouteTitle($buttons);
            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => 0,
            ], [
                    'and', ['menu_id' => $menu_id, 'permission_level' => 2], ['!=', 'id', $item_id]
                ]
            );

            $buttons_ids = array_column($buttons, 'item_id');

            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => $menu_id,
            ], [
                    'id' => $buttons_ids,
                ]
            );
//            $route_type = 2;
//            $list       = array_column($buttons, 'item_id');
//            $have_ids   = AuthItemChild::find()->where([
//                    'parent_item_id'  => $item_id,
//                    'child_type' => 0,
//                    'route_type' => $route_type
//                ]
//            )->select('item_id')->asArray()->column();
//
//            $diffArr    = \common\helpers\ArrayHelper::array2diff($have_ids, $list);
//            $remove_ids = $diffArr[ 'delete' ] ?? [];
//            if (!empty($remove_ids)) {
//                $model->removeChildren(['route' => $remove_ids]);
//            }
//
//            $add_ids = $diffArr[ 'add' ] ?? [];
//            if (!empty($add_ids)) {
//                $model->addChildren(['route' => $add_ids]);
//            }
        }

        if ($routes) {
            self::updateRouteTitle($routes);
//            $route_type = 1;
//            $list       = array_column($routes, 'item_id');
//            $have_ids   = AuthItemChild::find()->where([
//                    'parent_item_id'  => $item_id,
//                    'child_type' => 0,
//                    'route_type' => $route_type
//                ]
//            )->select('item_id')->asArray()->column();

//            $diffArr    = \common\helpers\ArrayHelper::array2diff($have_ids, $list);
//            $remove_ids = $diffArr[ 'delete' ] ?? [];
//            if (!empty($remove_ids)) {
//                $model->removeChildren(['route' => $remove_ids]);
//            }
//
//            $add_ids = $diffArr[ 'add' ] ?? [];
//            if (!empty($add_ids)) {
//                $model->addChildren(['route' => $add_ids]);
//            }
            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => 0,
            ], [
                    'and', ['menu_id' => $menu_id, 'permission_level' => 1], ['!=', 'id', $item_id]
                ]
            );

            $routes_ids = array_column($routes, 'item_id');
            \diandi\admin\acmodels\AuthItem::updateAll([
                'menu_id' => $menu_id,
            ], [
                    'id' => $routes_ids,
                ]
            );

        }
    }

    /**
     * 更新route 的title数据
     */
    public static function updateRouteTitle(array $routes): void
    {
        foreach ($routes as $route) {
            $route_name = $route['route_name'];
            $title      = $route['title'];
            Yii::$app->db->createCommand()->update(AuthRoute::tableName(), [
                'title' => $title
            ], ['route_name' => $route_name]
            )->execute();
        }
    }

    /**
     * 获取一个菜单的权限数据
     */
    public static function getMenuPermission($menu_id)
    {
//        $auth    = Configs::authManager();
//        $item_id = Menu::find()->where(['id' => $menu_id])->select('item_id')->scalar();

        //        'route_type' => $route_type
//        $list = Yii::$app->db->createCommand('SELECT
//	*
//FROM
//	' . AuthItemChild::tableName() . ' AS c
//	LEFT JOIN ' . \diandi\admin\acmodels\AuthRoute::tableName() . ' AS r ON c.item_id = r.item_id
//WHERE
//	c.parent_item_id = ' . $item_id . '
//	AND c.child_type = 0'
//        )
//            ->queryAll();

        $list = Yii::$app->db->createCommand('SELECT
	c.* ,r.route_name,r.title
FROM
	' . \diandi\admin\acmodels\AuthItem::tableName() . ' AS c
	LEFT JOIN ' . \diandi\admin\acmodels\AuthRoute::tableName() . ' AS r ON c.id = r.item_id 
WHERE
	c.menu_id = ' . $menu_id . '
	AND c.permission_level > 0'
        )
            ->queryAll();

        //       route_type 路由级别:0: 目录1: 页面 2: 按钮 3: 接口
        $lists = [];
        foreach ($list as $item) {
            $data = [
                'item_id'          => $item['id'],
                'route_name'       => $item['route_name'],
                //                'name' => $item[ 'name' ],
                'title'            => $item['title'],
                'permission_level' => $item['permission_level']
            ];
            switch ($item['permission_level']) {
                case 1:
                    $lists['routes'][] = $data;
                    break;
                case 2:
                    $lists['buttons'][] = $data;
                    break;
                case 3:
                    $lists['apis'][] = $data;
                    break;
            }
        }
        return $lists;
    }

    /**
     * 获取用户自己部门信息
     */
    public static function getUserDeptIds($user_id): array
    {
        if (empty($user_id)) {
            return [];
        }
//
//        $cacheKey = "user_dept_ids_{$user_id}";
//        $cache    = Yii::$app->cache;
//
//        $departmentIds = $cache->get($cacheKey);
//        if ($departmentIds !== false) {
//            return $departmentIds;
//        }

        $dependency = new DbDependency([
                'sql' => 'SELECT count(*) FROM ' . UserStore::tableName() . ' WHERE user_id = ' . Yii::$app->db->quoteValue($user_id),
            ]
        );

        $departmentIds = UserStore::find()->where(['user_id' => $user_id])->select('department_id')->distinct('department_id')->cache(86400, $dependency)->column();

//        // 缓存结果（有效期1小时，根据业务调整）
//        $cache->set($cacheKey, $departmentIds, 3600);

        return $departmentIds;
    }

    /**
     * 获取用户关联的用户和自己所持有的部门信息
     */
    public static function getUserAuthDepts($user_id): array
    {
        if (empty($user_id)) {
            return [];
        }
        $cacheKey = "user_auth_dept_ids_{$user_id}";
        $cache    = Yii::$app->cache;

        $deptIds = $cache->get($cacheKey);
        if ($deptIds !== false) {
            return $deptIds;
        }


        $userIds = UserService::getAuthUserLink($user_id);
        if (empty($userIds)) {
            return [];
        }

        $deptIds = UserStore::find()->where(['user_id' => $userIds])->select('department_id')->distinct('department_id')->column();

        $cache->set($cacheKey, $deptIds, 7200);

        return $deptIds ?? [];

    }

    /**
     * 获取用户信息
     */
    public static function getUserInfo($user_id): array
    {
        if (empty($user_id)) {
            return [];
        }
        $cacheKey = "user_info_{$user_id}";
        $cache    = Yii::$app->cache;

        $userInfo = $cache->get($cacheKey);
        if ($userInfo !== false) {
            return $userInfo;
        }


        $userInfo = User::find()->where(['id' => $user_id])->asArray()->one();

        $cache->set($cacheKey, $userInfo, 86400);

        return $userInfo;
    }

    /**
     * 获取用户的storeId
     */
    public static function getUserStoreIds($user_id): array
    {

        if (empty($user_id)) {
            return [];
        }
        $cacheKey = "user_store_ids_{$user_id}";
        $cache    = Yii::$app->cache;

        $storeIds = $cache->get($cacheKey);
        if ($storeIds !== false) {
            return $storeIds;
        }

//        $userIds = UserService::getAuthUserLink($user_id);
//        if (empty($userIds)) {
//            return [];
//        }
        #员工下的 store id
//        $storeIds = UserStore::find()->where(['user_id' => $userIds])->select('store_id')->distinct('store_id')->column();

        #当前用户下的 store id
        $storeIds = UserStore::find()->where(['user_id' => $user_id])->select('store_id')->column();
        /**
         * 需要包含自身的store_id 因为很多操作会根据自身store_id 做处理，但是查询是根据授权查询，就会有问题
         */
        $selfStoreId = DdUser::find()->where(['id' => $user_id])->select('store_id')->column();
        if (!in_array($selfStoreId, $storeIds)) {
            $storeIds[] = $selfStoreId;
        }
//        if (!empty($userStoreIds) && !empty($storeIds)) {
//            $storeIds = array_diff($userStoreIds, $storeIds);
//        }


        $cache->set($cacheKey, $storeIds, 7200);

        return $storeIds ?? [];
    }

    /**
     * 获取用户的blocids
     *
     */
    public static function getUserBlocIds($user_id): array
    {
        if (empty($user_id)) {
            return [];
        }
//        $cacheKey = "user_bloc_ids_{$user_id}";
//        $cache    = Yii::$app->cache;
//
//        $blocIds = $cache->get($cacheKey);
//        if ($blocIds !== false) {
//            return $blocIds;
//        }

        $dependency = new DbDependency([
                'sql' => 'SELECT count(*) FROM ' . UserBloc::tableName() . ' WHERE user_id = ' . Yii::$app->db->quoteValue($user_id),
            ]
        );

        $blocIds = UserBloc::find()->where(['user_id' => $user_id])->select('bloc_id')->distinct('bloc_id')->cache(86400, $dependency)->column();

//        $cache->set($cacheKey, $blocIds, 86400);

        return $blocIds ?? [];
    }

    /**
     * 获取一个公司所有的storeIds
     */
    public static function getCompanyStoreIds($bloc_id): array
    {
        if (empty($bloc_id)) {
            return [];
        }
//        $cacheKey = "company_store_ids_{$bloc_id}";
//        $cache    = Yii::$app->cache;
//
//        $storeIds = $cache->get($cacheKey);
//        if ($storeIds !== false) {
//            return $storeIds;
//        }
        $dependency = new DbDependency([
                'sql' => 'SELECT count(*) FROM ' . BlocStore::tableName() . ' WHERE bloc_id = ' . Yii::$app->db->quoteValue($bloc_id),
            ]
        );

        $storeIds = BlocStore::find()->where(['bloc_id' => $bloc_id])->select('store_id')->cache(86400, $dependency)->column();

//        $cache->set($cacheKey, $storeIds, 86400);

        return $storeIds ?? [];
    }

    public static function createAuth($data)
    {
        ini_set('memory_limit', -1);


        $route       = $data['route'] ?? '';
        $module_name = $data['module_name'] ?? 'system';
        $apis        = $data['apis'] ?? [];
        $buttons     = $data['buttons'] ?? [];
//            $module_name = 'system';
//
//            $buttons = [
//                ['title' => '2授权1122扫码', 'name'=>'91111asasasasasa1222222','route_name' => '91111asasasasasa1222222-button'],
//                ['title' => '2添加11222素材', 'name'=>'91111asasasasasa1222222','route_name' => '9222sasasaassa2111111-button'],
//            ];
//            $apis = [
//                ['title' => '1111接口测试赛11', 'name'=>'91111asasasasasa1222222','route_name' => 'dfsghfdgsdshjfds22'],
//                ['title' => '2222接口测试赛22','name'=>'91111asasasasasa1222222', 'route_name' => 'dfsghfdgsdshjfds33333'],
//            ];
//
//            $route   = '/diandi_set/wxmp/fans-msg-manage/wx-msg-reply.vue';
        if (empty($route)) {
            return ResultHelper::json(400, '页面路径必填');
        }
        if (empty($route) && empty($buttons)) {
            return ResultHelper::json(200, '暂无新增');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            #新增按钮
            if (!empty($buttons)) {
                $buttons = AuthService::createAuthRoute($buttons, $module_name, 2);
            }

            #新增api
            if (!empty($apis)) {
                $apis = AuthService::createAuthRoute($apis, $module_name, 3);
            }

            if (empty($buttons) && empty($apis)) {
                return ResultHelper::json(200, '暂无更新');
            }

            $model = Menu::findOne(['route' => $route]);
            if (empty($model)) {
                return ResultHelper::json(400, '未查询到页面菜单');
            }

            Helper::invalidate();
            $menu_id = $model->id;

            AuthService::writeMenuPermissionOne($menu_id, $apis, $buttons);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            // 回滚事务
            $transaction->rollBack();
            Yii::error('保存失败：' . $e->getMessage());
            return false;
        }
    }

    public static function createAuthRoute($data, $module_name, $type = 2)
    {

        ini_set('memory_limit', -1);
        $model = new AuthRoute();
        foreach ($data as $bk => $bv) {
            if ($bv['name'] == '/' || empty($bv['route_name'])) {
                unset($data[$bk]);
                continue;
            }

            $res = AuthRoute::findOne(['name' => $bv['name']]);
            if (empty($res->id)) {
                $new_data                = [];
                $new_data                = $bv;
                $new_data['route_type']  = $type;
                $new_data['module_name'] = $module_name;
                $new_data['description'] = '';
                $new_data['item_id']     = 0;
                $new_data['is_sys']      = 0;
                $new_data['pid']         = 0;

                $_model = clone $model;
                $_model->setAttributes($new_data);
                $res = $_model->save();
                if (!$res) {
                    // 返回数据验证失败
                    $msg = ErrorsHelper::getModelError($_model);
//                    unset($data[ $bk ]);
//                    continue;
                    return ResultHelper::json(400, $msg, []);
//                    throw new \Exception($msg);
                }
                $data[$bk]['item_id'] = $_model->item_id;
            } else {
                $data[$bk]['item_id'] = $res->item_id;
            }
        }
        return $data;
    }

    /**
     * @return UserGroup
     * @throws NotFoundHttpException
     */
    static function getGroups($id)
    {
        if (($model = UserGroup::findOne($id)) !== null) {
            return new UserGroup($model);
        }
        throw new NotFoundHttpException('用户组不存在');
    }

    /**
     * 用户组权限变更
     */
    public static function changeGroupAuth($id, $type, $items, $module_name = ''): array
    {
        try {
            $model = self::getGroups($id);
            switch ($type) {
                case 'route':
                    $item  = new Route([
                        'name'        => $model['name'],
                        'id'          => $id,
                        'title'       => '',
                        'item_id'     => $model['item_id'],
                        'is_sys'      => $model['is_sys'],
                        'child_type'  => 0,
                        'description' => $model['description'],
                        'data'        => '',
                        'pid'         => 0,
                    ]);
                    $where = [];
                    if ($module_name) {
                        $where = ['module_name' => $module_name];
                    }
                    $have_ids = AuthItemChild::find()->where([
                        'parent_item_id' => $model['item_id'],
                        'child_type'     => 0,
                    ])->andWhere($where)->select('item_id')->asArray()->column();
                    $route    = new ModelsRoute($item);

                    if (empty($items)) {
                        //多公司下，需要根据parent_item_id进一步区分
                        $where['group_id'] = $id;
                        $route->removeChildren([
                            'route' => $have_ids,
                        ]);
                        AuthAssignmentGroupMenu::deleteAll($where);
                        return ResultHelper::json(200, '操作成功');
                    }
                    /**
                     * 区分route 和 menu
                     */
                    $routes     = \diandi\admin\acmodels\AuthRoute::find()->where(['item_id' => $items['route']])->andWhere($where)->select('item_id')->column();
                    $menus      = Menu::find()->where(['item_id' => $items['route']])->andWhere($where)->select('item_id')->column();
                    $diffArr    = ArrayHelper::array2diff($have_ids, $routes);
                    $remove_ids = $diffArr['delete'];
                    $add_ids    = $diffArr['add'];
                    if (!empty($remove_ids)) {

                        $route = new ModelsRoute($item);
                        $route->removeChildren([
                            'route' => $remove_ids,
                        ]);
                    }
                    if (!empty($add_ids)) {
                        $route->addChildren([
                            'route' => $add_ids,
                        ], 2
                        );
                    }

//                    if (!empty($menus)) {
                    $parentKeys = Yii::$app->request->input('parentKeys', []);
                    self::AssignmentMenu($model, $menus, $parentKeys);
//                    }

                    AuthService::clearUserGroupCache($id);

                    return ResultHelper::json(200, '操作成功');
                    break;
                case 'permission':
                    $item       = new Item([
                        'name'        => $model['name'],
                        'is_sys'      => $model['is_sys'],
                        'parent_id'   => $id,
                        'item_id'     => $model['item_id'],
                        'child_type'  => 1,
                        'ruleName'    => '',
                        'description' => $model['description'],
                        'data'        => '',
                    ]);
                    $permission = new AuthItem($item);
                    $have_ids   = AuthItemChild::find()->where([
                        'parent_item_id' => $model['item_id'],
                        'child_type'     => 1,
                    ])->select('item_id')->asArray()->column();
                    if (empty($items)) {
                        $permission->removeChildren([
                            'permission' => $have_ids,
                        ]);
                        return ResultHelper::json(200, '操作成功');
                    }
                    $list = $items['permission'];

                    $diffArr = ArrayHelper::array2diff($have_ids, $list);

                    $remove_ids = $diffArr['delete'] ?? [];
                    $add_ids    = $diffArr['add'] ?? [];
                    if (!empty($remove_ids)) {

                        $permission = new AuthItem($item);
                        $permission->removeChildren([
                            'permission' => $remove_ids,
                        ]);
                    }


                    if (!empty($add_ids)) {
                        $permission->addChildren([
                            'permission' => $add_ids,
                        ], 2
                        );
                    }
                    AuthService::clearUserGroupCache($id);

                    return ResultHelper::json(200, '操作成功');
                    break;
                case 'role':
                    $group    = UserGroup::findOne($id);
                    $model    = new UserGroup($group);
                    $have_ids = AuthItemChild::find()->where([
                            'parent_item_id' => $model['item_id'],
                            'child_type'     => 2,
                        ]
                    )->select('item_id')->asArray()->column();
                    if (empty($items)) {
                        $model->removeChildren(['group' => $have_ids]);

                        return ResultHelper::json(200, '操作成功');
                    }
                    $list = $items['role'];


                    $diffArr    = ArrayHelper::array2diff($have_ids, $list);
                    $remove_ids = $diffArr['delete'] ?? [];
                    if (!empty($remove_ids)) {
                        $model->removeChildren(['group' => $remove_ids]);
                    }

                    $add_ids = $diffArr['add'] ?? [];
                    if (!empty($add_ids)) {
                        $model->addChildren(['group' => $add_ids]);
                    }
                    AuthService::clearUserGroupCache($id);

                    return ResultHelper::json(200, '操作成功');
                    break;
            }

            return ResultHelper::json(200, '操作成功');
        } catch (NotFoundHttpException $e) {
            return ResultHelper::json(500, $e->getMessage());
        }

    }

    /**
     * 按钮授权
     *
     * @param $model
     * @param $menus
     * @param $parentKeys
     * @return array|void
     */
    public static function AssignmentMenu($model, $menus, $parentKeys)
    {
        $group_id      = $model['id'];
        $group_item_id = $model['item_id'];
        $group_name    = $model['name'];
        $allMenu       = array_merge($menus, $parentKeys);
        /**
         * 查询当前插件已有的
         */
        if ($menus) {
            $menu_module_name = Menu::find()->where(['item_id' => $menus[0]])->select('module_name')->scalar();

            $group_have_menu_ids = AuthAssignmentGroupMenu::find()
                ->where(['group_id' => $group_id, 'group_item_id' => $group_item_id, 'module_name' => $menu_module_name])
                ->select('item_id')->column();

        } else {
            $menu_module_name    = Menu::find()->where(['item_id' => $parentKeys[0]])->select('module_name')->scalar();
            $group_have_menu_ids = [];
        }
        $diff_arr = ArrayHelper::array2diff($group_have_menu_ids, $allMenu);

        $add_ids = $diff_arr['add'];

        $del_ids = $diff_arr['delete'];


        if (!empty($add_ids)) {

            $insertData = [];
            foreach ($add_ids as $menuId) {
                $insertData[] = [
                    'group_id'      => $group_id,
                    'is_options'    => in_array($menuId, $parentKeys) ? 0 : 1,
                    'group_item_id' => $group_item_id,
                    'group_name'    => $group_name,
                    'item_id'       => $menuId,
                    'module_name'   => $menu_module_name,
                    'created_at'    => time(),
                ];
            }

            if (!empty($insertData)) {
                try {
                    $columnNames = ['group_id', 'is_options', 'group_item_id', 'group_name', 'item_id', 'module_name', 'created_at'];
                    Yii::$app->db->createCommand()
                        ->batchInsert(AuthAssignmentGroupMenu::tableName(), $columnNames, $insertData)
                        ->execute();
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    if ($msg) {
                        return ResultHelper::serverJson(400, $msg);

                    }
                }
            }
        }

        if (!empty($del_ids)) {
            try {
                AuthAssignmentGroupMenu::deleteAll(['item_id' => $del_ids, 'group_id' => $group_id, 'module_name' => $menu_module_name]);
            } catch (Exception $e) {
                $msg = $e->getMessage();
                if ($msg) {
                    return ResultHelper::serverJson(400, $msg);

                }
            }
        }
    }

    /**
     * 获取用户app权限
     */
    public static function getUserAppPermission($user_id)
    {
        $isAdmin = UserService::isSuperAdminOrBusinessRoles($user_id);
        if ($isAdmin) {
            return ['*'];
        }

        $sql = "select i.permissionId from {{%auth_assignment_group}} as g left join {{%diandi_auth_role_permission}} as i on g.group_id = i.roleId  where g.user_id = {$user_id} and i.is_sys = 1";

        $permissionIds = Yii::$app->db->createCommand($sql)->queryColumn();
        loggingHelper::writeLog('AccessTokenService', 'getAccessToken', '获取角色权限0', [
            'uid'           => $user_id,
            'permissionIds' => $permissionIds,
            'sql'           => Yii::$app->db->createCommand($sql)->getRawSql(),
        ]);
        $permissionIdStr = "'" . implode("','", $permissionIds) . "'";
        $sql             = "select page_name from {{%diandi_auth_permission}} where id in ({$permissionIdStr})";

        $permission = Yii::$app->db->createCommand($sql)->queryColumn();
        return $permission ?? [];
    }

    /**
     * 获取导航权限
     */
    public static function getAppNavigationPermission($user_id = 0)
    {
        $isGuest = Yii::$app->user->isGuest;

        loggingHelper::writeLog('AuthService', 'getUserAppPermission', '开始获取导航权限', [
            'uid'     => $user_id,
            'isGuest' => $isGuest,
        ]);
        $where = [];
        if ($isGuest) {
            $where['is_guest'] = $isGuest;
        } else {

            $isAdmin = UserService::isSuperAdminOrBusinessRoles($user_id);
            if ($isAdmin) {
                $list = BlocConfAppNav::find()->findBloc()->with(['path'])->asArray()->all();
                loggingHelper::writeLog('AuthService', 'getUserAppPermission', '获取导航权限', [
                    'sql'   => BlocConfAppNav::find()->where($where)->findBloc()->with(['path'])->createCommand()->getRawSql(),
                    'list'  => $list,
                    'where' => $where,
                ]);
                $lists = [];
                foreach ($list as $item) {
                    $lists[] = [
                        'text'             => $item['text'],
                        'tabName'          => $item['tab_name'],
                        'pagePath'         => $item['path']['page_name'],
                        'iconPath'         => ImageHelper::tomedia($item['icon_path']),
                        'selectedIconPath' => ImageHelper::tomedia($item['selected_icon_path']),
                    ];
                }
                return $lists ?? [];
            }

            /**
             * 需要判断用户是客商还是管理员
             */
            $userType = Yii::$app->params['userType'];
            if ($userType === 'admin') {
                $sql = "select i.permissionId from {{%auth_assignment_group}} as g left join {{%diandi_auth_role_permission}} as i on g.group_id = i.roleId  where g.user_id = {$user_id} and i.is_sys = 1";

                $permissionIds = Yii::$app->db->createCommand($sql)->queryColumn();
                loggingHelper::writeLog('AuthService', 'getUserAppPermission', '获取角色权限0', [
                    'uid'           => $user_id,
                    'permissionIds' => $permissionIds,
                    'sql'           => Yii::$app->db->createCommand($sql)->getRawSql(),
                ]);
                $id = (new Query())->from('{{%diandi_auth_permission}}')->where(['id' => $permissionIds])->select('nav_id')->column();

                loggingHelper::writeLog('AuthService', 'getUserAppPermission', '获取导航ID', [
                    'sql' => (new Query())->from('{{%diandi_auth_permission}}')->where(['id' => $permissionIds])->select('nav_id')->createCommand()->getRawSql()
                ]);

                $where['id'] = $id ?? [];

            } else {
                $roleId        = (new Query())->from('{{%customer_user}}')->where(['id' => $user_id])->select('auth_role_id')->scalar();
                $permissionIds = (new Query())->from('{{%diandi_auth_role_permission}}')->where(['roleId' => $roleId])->select('permissionId')->column();

                loggingHelper::writeLog('AuthService', 'getUserAppPermission', '获取角色权限0', [
                    'uid'           => $user_id,
                    'permissionIds' => $permissionIds
                ]);
                $id = (new Query())->from('{{%diandi_auth_permission}}')->where(['id' => $permissionIds])->select('nav_id')->column();
                loggingHelper::writeLog('AuthService', 'getUserAppPermission', '获取导航ID', [
                    'sql' => (new Query())->from('{{%diandi_auth_permission}}')->where(['id' => $permissionIds])->select('nav_id')->createCommand()->getRawSql()
                ]);
                $where['id'] = $id ?? [];
            }

        }

        $list = BlocConfAppNav::find()->where($where)->orWhere(['is_guest' => 1])->findBloc()->with(['path'])->asArray()->all();
        loggingHelper::writeLog('AuthService', 'getUserAppPermission', '获取导航权限', [
            'sql'   => BlocConfAppNav::find()->where($where)->findBloc()->with(['path'])->createCommand()->getRawSql(),
            'list'  => $list,
            'where' => $where,
        ]);
        $lists = [];
        foreach ($list as $item) {
            $lists[] = [
                'text'             => $item['text'],
                'tabName'          => $item['tab_name'],
                'pagePath'         => $item['path']['page_name'],
                'iconPath'         => ImageHelper::tomedia($item['icon_path']),
                'selectedIconPath' => ImageHelper::tomedia($item['selected_icon_path']),
            ];
        }
        return $lists ?? [];
    }
}

