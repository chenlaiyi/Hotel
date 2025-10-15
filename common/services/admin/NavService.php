<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-04-27 03:18:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 21:57:18
 */

namespace common\services\admin;

use admin\models\auth\AuthRoute;
use admin\models\BlocAddons;
use admin\services\AuthService;
use admin\services\UserService;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;
use common\models\User;
use common\services\BaseService;
use diandi\addons\models\DdAddons;
use diandi\addons\services\addonsService;
use diandi\admin\components\Configs;
use diandi\admin\components\MenuHelper;
use diandi\admin\models\AuthAssignmentGroup;
use diandi\admin\models\Menu;
use Qiniu\Auth;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Class SmsService.
 *
 * @author chunchun <2192138785@qq.com>
 */
class NavService extends BaseService
{
    public static array $module_names;

    public function getMenuTop(): array | string
    {
        $lists = array_merge([
            0 => [
                'title'     => '系统',
                'identifie' => 'system',
            ],
        ], self::$module_names);
        foreach ($lists as $key => &$value) {
            if ($value) {
                $value['name']       = $value['title'] ?? '';
                $value['mark']       = $value['identifie'] ?? '';
                $value['icon']       = '';
                $value['targetType'] = 'top-nav';

                $value['url'] = ! empty($value['mark']) ? "system/welcome/{$value['mark']}" : '';
            }
        }

        return $lists;
    }

    public function getMenu($is_sys = false): array | bool | string
    {
        if ($is_sys) {
            $routeItem = [];
            $allMenu   = $this->getSysMenu();
        } else {
            $allMenu   = $this->allMenu();
            $routeItem = $this->getRouteItem();
        }

        $menuCate = $this->getMenuTop();

        foreach ($menuCate as &$value) {
            $value['text']       = $value['title'];
            $value['targetType'] = 'top-nav';
            $mark                = ! empty($value['mark']) ? $value['mark'] : '';
            $value['url']        = "system/welcome/{$mark}";
        }
        return [
            'top'       => $menuCate,
            'left'      => $allMenu,
            'routeItem' => $routeItem,
        ];
    }

    public function getSysMenu()
    {

        $module_names = $this->getBlocAddons();

        $is_lefts = DdAddons::find()->where(['identifie' => array_column($module_names, 'identifie')])->select(['identifie', 'is_left', 'is_nav'])->indexBy('identifie')->asArray()->all();

        self::$module_names = $module_names;
        $bloc_id            = Yii::$app->request->input('bloc_id', 0);
        $key                = 'auth_sys_initmenu_' . Yii::$app->user->id . 'bloc:sys:' . $bloc_id;

        $initMenu = Yii::$app->cache->get($key);

        if ($initMenu) {
            return $initMenu;
        } else {
            // 获取所有的路由
            $routeList = AuthRoute::find()->indexBy('id')->select(['id', 'route_name'])->asArray()->all();
            $callback  = function ($menu) use ($routeList, $is_lefts) {
                $route_name = ! empty($routeList[$menu['route_id']]) ? $routeList[$menu['route_id']]['route_name'] : '';
                // 解析地址路由参数
                $data = $menu['data'] ? json_decode($menu['data'], true) : [];

                $parent_id = intval($menu['parent']);

                //区分系统菜单和扩展模块菜单
                $menu_type = $menu['module_name'];

                if ($menu['is_sys'] === 1) {
                    $parent_id = intval($menu['parent']);
                }

                $route = $menu['route'];

                $return = [
                    'id'         => $menu['id'],
                    'hidden'     => ! ($menu['is_show'] == 0),
                    'parent'     => $parent_id,
                    'order'      => (int) $menu['order'],
                    'name'       => $route_name,
                    'level_type' => $menu['level_type'],
                    'type'       => $menu_type,
                    'meta'       => [
                        'parent_menu_id' => 0,
                        'is_left'        => $menu_type === 'system' ? 1 : $is_lefts[$menu_type]['is_left'],
                        'type'           => $menu_type,
                        'title'          => $menu['name'],
                        'level_type'     => $menu['level_type'],
                        'tag'            => $menu_type . '-' . $menu['level_type'] . '-' . ($menu['name'] != 'main-index' ? $menu['id'] : $menu['num']),
                        'icon'           => $menu['icon'],
                        'affix'          => false, // $menu['name'] === '工作台' && !empty($parent_id),
                        'parent'         => $parent_id,
                    ],
                    'path'       => $route ?? '/' . $menu['id'],
                    'children'   => $menu['children'],
                ];

                //处理我们的配置
                if ($data) {
                    isset($data['visible']) && $return['visible']            = $data['visible']; //visible
                    isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];    //icon//other attribute e.g. class...
                    $return['options']                                       = $data;
                }

                //没配置图标的显示默认图标
                (! isset($return['icon']) || ! $return['icon']) && $return['icon'] = 'fa fa-fw fa-cube';

                return $return;
            };
            $where        = ['is_sys' => 1];
            $user_id      = Yii::$app->user->id;
            $initMenus    = MenuHelper::getAssignedMenu($user_id, null, $callback, $where, 1);
            $initMenu     = ArrayHelper::arraySort($initMenus, 'order');
            $initMenuList = $this->menuChildRoute($initMenu);
            Yii::$app->cache->set($key, $initMenuList);
            return $initMenuList;
        }
    }

    /**
     * 初始化按钮菜单
     * @param $addons
     * @return true
     * @throws BadRequestHttpException
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public static function initMenu($addons)
    {
        // 写入基础信息进入模块目录
        $logPath     = Yii::getAlias('@runtime/log/install');
        $application = $addons['application'];
        $DdAddons    = new \diandi\addons\models\searchs\DdAddons();
        $transaction = $DdAddons::getDb()->beginTransaction();

        try {
            $parent = [];
            // 写入菜单信息进系统菜单
            $Menu     = new \diandi\admin\models\searchs\Menu();
            $menuFile = Yii::getAlias('@addons/' . $application['identifie'] . '/config/menu.php');

            $baseMenus = require_once $menuFile;
            if (is_array($baseMenus) && ! empty($baseMenus)) {
                foreach ($baseMenus as $item) {
                    $_Menu    = clone $Menu;
                    $MenuData = [
                        'name'        => $item['name'],
                        'parent'      => 0,
                        'level_type'  => $item['level_type'],
                        'is_show'     => $item['is_show'],
                        'route'       => $item['route'],
                        'order'       => ! empty($item['order']) ? $item['order'] : 0,
                        'type'        => 'plugins',
                        'icon'        => $item['icon'] ? $item['icon'] : '',
                        'is_sys'      => 0,
                        'module_name' => $application['identifie'],
                    ];

                    $_Menu->setAttributes($MenuData);
                    $_Menu->save();

                    $parent = $_Menu['attributes']['id'];
                    addonsService::createRoute($item['router'], $parent);

                    if (! empty($item['child'])) {
                        foreach ($item['child'] as $child) {
                            $_Menuchild = clone $Menu;
                            $MenuData   = [
                                'name'        => $child['name'],
                                'parent'      => $parent,
                                'level_type'  => $child['level_type'],
                                'is_show'     => $child['is_show'],
                                'route_type'  => $menu['type'],
                                'route'       => $child['route'],
                                'order'       => intval($child['order']),
                                'type'        => 'plugins',
                                'icon'        => $child['icon'] ? $child['icon'] : '',
                                'is_sys'      => 0,
                                'module_name' => $application['identifie'],
                            ];
                            // FileHelper::writeLog($logPath, '子类菜单' . Json::encode($MenuData));
                            $_Menuchild->setAttributes($MenuData);
                            $_Menuchild->save();

                            $parentChild = $_Menuchild['attributes']['id'];
                            addonsService::createRoute($child['router'], $parentChild);

                            if (! empty($child['child'])) {
                                foreach ($child['child'] as $childs) {
                                    $_Menuchild = clone $Menu;
                                    $MenuData   = [
                                        'name'        => $childs['name'],
                                        'parent'      => $parentChild,
                                        'level_type'  => $childs['level_type'],
                                        'is_show'     => $childs['is_show'],
                                        'order'       => intval($child['order']),
                                        'route'       => $childs['route'],
                                        'type'        => 'plugins',
                                        'icon'        => $childs['icon'] ? $childs['icon'] : '',
                                        'is_sys'      => 0,
                                        'module_name' => $application['identifie'],
                                    ];
                                    // FileHelper::writeLog($logPath, '子类菜单' . Json::encode($MenuData));
                                    $_Menuchild->setAttributes($MenuData);
                                    $_Menuchild->save();
                                    $parentChilds = $_Menuchild['attributes']['id'];
                                    addonsService::createRoute($childs['router'], $parentChilds);
                                }
                            }
                        }
                    }
                }
            }

            // 更新二级菜单
            $route_main_id = \diandi\admin\acmodels\AuthRoute::find()->where(['name' => '/main/index.vue'])->select('id')->scalar();
            $Menu->updateAll(['route_id' => $route_main_id], [
                'route'       => '/main/index.vue',
                'module_name' => $application['identifie'],
            ]);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return true;
        // 执行操作权限
    }

    public function getBlocAddons(): array
    {
        $user_id = Yii::$app->user->id;
        return AuthService::getUserPluginPermission($user_id);
    }

    public function allMenu()
    {
        $module_names       = $this->getBlocAddons();
        self::$module_names = $module_names;

        $module_name = array_column($module_names, 'identifie');
        $is_lefts    = DdAddons::find()->where(['identifie' => $module_name])->select(['identifie', 'is_left', 'is_nav', 'displayorder'])->indexBy('identifie')->asArray()->all();
        $bloc_id     = Yii::$app->request->input('bloc_id', 0);
        $key         = 'auth_sys_initmenu_' . Yii::$app->user->id . 'bloc：all:' . $bloc_id;
        $initMenu = Yii::$app->cache->get($key);


        if ($initMenu) {
             return $initMenu;
        } else {
            // 获取所有的路由
            $routeList = AuthRoute::find()->indexBy('id')->select(['id', 'route_name'])->asArray()->all();
            $callback  = function ($menu) use ($module_name, $routeList, $is_lefts) {
                $route_name = ! empty($routeList[$menu['route_id']]) ? $routeList[$menu['route_id']]['route_name'] : '';
                // 解析地址路由参数
                $data = $menu['data'] ? json_decode($menu['data'], true) : [];

                $parent_id = intval($menu['parent']);

                //区分系统菜单和扩展模块菜单
                $menu_type = $menu['module_name'];

                if ($menu['is_sys'] === 1) {
                    $parent_id = intval($menu['parent']);
                }

                $route = $menu['route'];

                $return = [
                    'id'         => $menu['id'],
                    'hidden'     => ! ($menu['is_show'] == 0),
                    'parent'     => $parent_id,
                    'order'      => (int) $menu['order'],
                    'name'       => $route_name,
                    'level_type' => $menu['level_type'],
                    'type'       => $menu_type,
                    'menu_type'  => $menu['type'],
                    'meta'       => [
                        'parent_menu_id' => 0,
                        'is_left'        => $menu_type === 'system' ? 1 : $is_lefts[$menu_type]['is_left'],
                        'type'           => $menu_type,
                        'route_type'     => $menu['type'],
                        'title'          => $menu['name'],
                        'icon'           => $menu['icon'],
                        'level_type'     => $menu['level_type'],
                        'tag'            => $menu_type . '-' . $menu['level_type'] . '-' . ($menu['name'] != 'main-index' ? $menu['id'] : $menu['num']),
                        'affix'          => false, // $menu['name'] === '工作台' && !empty($parent_id),
                        'parent'         => $parent_id,
                    ],
                    'path'       => $route ?? '/' . $menu['id'],
                    'children'   => $menu['children'],
                ];

                //处理我们的配置
                if ($data) {
                    isset($data['visible']) && $return['visible']            = $data['visible']; //visible
                    isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];    //icon//other attribute e.g. class...
                    $return['options']                                       = $data;
                }

                /**
                 * 如果菜单是2级，并且没有子集，就给他增加自身为自己的children
                 */
                if ($menu['level_type'] == 2 && empty($menu['children'])) {
                    $return['children'][] = array_merge($return, ['level_type' => 3]);
                }

                //没配置图标的显示默认图标
                (! isset($return['icon']) || ! $return['icon']) && $return['icon'] = 'fa fa-fw fa-cube';

                return $return;
            };
            $routes       = ['/dashboard', '/main/index.vue', '/system/profile/index.vue', '/default/store/index.vue', '/default/store/addstore.vue'];
            $where        = ['or', ['route' => $routes], ['module_name' => $module_name]];
            $user_id      = Yii::$app->user->id;
            $initMenus    = MenuHelper::getAssignedMenu($user_id, null, $callback, $where, 1);
            $initMenu     = ArrayHelper::arraySort($initMenus, 'order');
            $initMenuList = $this->menuChildRoute($initMenu);
            Yii::$app->cache->set($key, $initMenuList);
            return $initMenuList;
        }
    }

    // 处理模块菜单

    public function ModuleMenu($allMenu = []): array
    {
        $top      = [];
        $leftMenu = [];
        $allMenus = ArrayHelper::removeByValue($allMenu, '');
        $num      = 0;
        foreach (array_values($allMenus) as $key => $value) {
            $value['mark']       = $value['type'];
            $value['targetType'] = 'top-nav';
            if (isset($value['children'])) {
                foreach ($value['children'] as $k => $child) {
                    if ($num == 0) {
                        $child['is_show'] = 'show';
                    }
                    if (! empty($child['children'])) {
                        foreach ($child['children'] as $key => &$val) {
                            if ($num == 0) {
                                $val['is_show'] = 'show';
                            }
                            $val['type'] = $child['type'];
                        }
                    }

                    $leftMenu[] = $child;
                }
                unset($value['children'], $value['is_show'], $value['type']);
                $top[] = $value;
                ++$num;
            } else {
                unset($allMenus[$key]);
            }
        }
        return [
            'top'  => $top,
            'left' => $leftMenu,
        ];
    }

    public static function addonsMens($addons): array
    {
        $list = Menu::find()->where(['module_name' => $addons])->with(['router' => function ($query) {
            return $query->with(['item']);
        }])->asArray()->all();

        foreach ($list as &$value) {
            if (! empty($value['router']) && is_array($value['router'])) {
                unset($value['router']['id'], $value['router']['created_at'], $value['router']['updated_at']);
                if (! empty($value['router']['item']) && is_array($value['router']['item'])) {
                    unset($value['router']['item']['id'], $value['router']['item']['created_at'], $value['router']['item']['updated_at']);
                }
            }
        }
        unset($value);
        $lists = ArrayHelper::itemsMerge($list, 0, 'id', 'parent', 'child', 3);
        //    去除id
        $menu  = ArrayHelper::removeByKey($lists);
        $menus = ArrayHelper::removeByKey($menu, 'parent');
        $menus = ArrayHelper::removeByKey($menus, 'route_id');
        $text  = '<?php return ' . var_export($menus, true) . ';';

        $configFile = Yii::getAlias('@addons/' . $addons . '/config');
        if (! is_dir($configFile)) {
            FileHelper::mkdirs($configFile);
            @chmod($configFile, 0777);
        }
        $file = Yii::getAlias('@addons/' . $addons . '/config/menu.php');

        if (false !== fopen($file, 'w+')) {
            file_put_contents($file, $text);
            echo '菜单创建成功' . PHP_EOL;
        } else {
            echo '菜单创建失败' . PHP_EOL;
        }

        return $menus;
    }

    // 处理非页面菜单
    public static function menuChildRoute(&$menus = [])
    {
        foreach ($menus as $key => &$value) {
            if (! empty($value['children'])) {
                foreach ($value['children'] as $k => $val) {
                    if ($val['level_type'] == 6) {
                        array_unshift($value, $val);
                        unset($menus[$key]);
                    }

                    if ($val['children']) {
                        static::menuChildRoute($val['children']);
                    } else if ($val['name'] === 'main-index' && empty($val['children'])) {
                        unset($menus[$key]);
                    }
                }
            }
        }

        return array_values($menus);
    }

    // 获取父级模块应用菜单的ID
    public static function getPluginsMenuId()
    {
        $addon           = DdAddons::find()->indexBy('mid')->asArray()->all();
        $addonsIdentifie = [];
        foreach ($addon as $key => $value) {
            if (! empty($value['parent_mids'])) {
                $parent_mids = explode(',', $value['parent_mids']);
                foreach ($parent_mids as $k => $val) {
                    $addon[$val]['child'][]                  = $value;
                    $addonOne                                = $addon[$val];
                    $addonsIdentifie[$addonOne['identifie']] = $addonOne;
                }
            }
        }

        // $addonsLevel = ArrayHelper::itemsMerge($addon, 0, 'mid', 'parent_mid', 'child');
        // $addonsIdentifie = ArrayHelper::arrayKey($addonsLevel, 'identifie');
        $pluginsMenus = Menu::find()->where(['name' => '应用', 'parent' => 0])->andWhere(['!=', 'module_name', 'system'])->indexBy('module_name')->asArray()->all();
        // 以子模块为键值输出父级的菜单ID
        $lists = [];
        foreach ($pluginsMenus as $identifie => $value) {
            if (! empty($addonsIdentifie[$identifie])) {
                if (key_exists('child', $addonsIdentifie[$identifie]) && ! empty($addonsIdentifie[$identifie]['child'])) {
                    foreach ($addonsIdentifie[$identifie]['child'] as $key => $val) {
                        $lists[$val['identifie']] = $value['id'];
                    }
                }
            }
        }

        return $lists;
    }

    /**
     * 获取用户权限集合，包括路由，接口，按钮
     * @return array
     */
    public function getRouteItem()
    {
        $user_id = Yii::$app->user->id;

        if (empty($user_id)) {
            return [];
        }

        $isSuperAdmin    = UserService::isSuperAdmin($user_id);
        $isbusinessRoles = UserService::isbusinessRoles($user_id);

        if ($isSuperAdmin || $isbusinessRoles) {
//            return \diandi\admin\acmodels\AuthRoute::find()->select(['module_name', 'name', 'route_name', 'route_type', 'title'])->asArray()->all();
            return ['*'];
        }
        $config = Configs::instance();
        /* @var $manager \yii\rbac\BaseManager */
        $manager   = $config::authManager();
        $routeItem = $manager->getPermissionsByUser($user_id);
        $item_ids  = array_column($routeItem, 'item_id');
        return \diandi\admin\acmodels\AuthRoute::find()->where(['item_id' => $item_ids])->select(['module_name', 'name', 'route_name', 'route_type', 'title'])->asArray()->all();
    }

}
