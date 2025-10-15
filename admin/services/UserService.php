<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-04-20 20:25:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:14:44
 */

namespace admin\services;

use admin\models\addons\models\Bloc;
use admin\models\BlocAddons;
use admin\models\DdApiAccessToken;
use admin\models\UserLink;
use common\components\Cache\enums\UserCacheKeyEnum;
use common\helpers\ArrayHelper;
use common\helpers\CacheHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\enums\UserStatus;
use common\models\User;
use common\models\UserStore;
use common\services\BaseService;
use diandi\addons\models\ActionLog;
use diandi\addons\models\AddonsUser;
use diandi\addons\models\BlocStore;
use diandi\addons\models\DdAddons;
use diandi\addons\models\StoreLabelLink;
use diandi\addons\models\UserBloc;
use diandi\admin\acmodels\AuthAssignmentGroupMenu;
use diandi\admin\acmodels\AuthItem;
use diandi\admin\acmodels\AuthUserGroup;
use diandi\admin\models\Assignment;
use diandi\admin\models\AuthAssignment;
use diandi\admin\models\AuthAssignmentGroup;
use diandi\admin\models\Menu;
use diandi\admin\models\UserGroup;
use Exception;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class UserService extends BaseService
{
    public static function updateOpenid(mixed $uid, mixed $openid)
    {
        $user = User::findOne($uid);
        if (!$user) {
            return false;
        }
        $user->openid = $openid;
        $result       = $user->save(false);
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * 判断用户是否是总管理员
     *
     * @return array|mixed
     */
    public static function isSuperAdmin(int $user_id = 0)
    {
        $user_id = $user_id > 0 ? $user_id : Yii::$app->user->id;

        $is_super_admin = User::find()->where([
                'id' => $user_id,
            ]
        )->select('is_super_admin')->scalar();

        return (int) $is_super_admin === 1;
    }

    /**
     * 判断是否是业务中心管理员
     *
     * @param int $user_id
     *
     * @return bool
     */
    public static function isbusinessRoles(int $user_id = 0): bool
    {
        $user_id = $user_id > 0 ? $user_id : Yii::$app->user->id;

        $is_business_admin = User::find()->where([
                'id' => $user_id,
            ]
        )->select('is_business_admin')->scalar();
        return (int) $is_business_admin === 1;
    }

    /**
     * 判断用户是超级管理员或者业务中心管理员
     */
    public static function isSuperAdminOrBusinessRoles(int $user_id = 0): bool
    {
        $user_id = $user_id > 0 ? $user_id : Yii::$app->user->id;
        loggingHelper::writeLog('UserService','isSuperAdminOrBusinessRoles','获取用户是否是管理员',[
            'user_id'=>$user_id,
            'sql' => User::find()->where([
                    'id' => $user_id,
                ]
            )->andWhere(['or',['is_business_admin'=>1],['is_super_admin'=>1]])->createCommand()->getRawSql()
        ]);
        return User::find()->where([
                'id' => $user_id,
            ]
        )->andWhere(['or',['is_business_admin'=>1],['is_super_admin'=>1]])->exists();
    }

    /**
     * 根据用户获取当前公司背后的应用权限
     *
     * @return array
     */
    public function getBlocByUidAddons()
    {
        $user_id  = Yii::$app->user->id;
        $is_admin = AuthAssignmentGroup::find()->where([
                'user_id'   => $user_id,
                'item_name' => '总管理员',
            ]
        )->exists();
        if ($is_admin) {
            return DdAddons::find()->where(['is_nav' => 1])->asArray()->all();
        }
        $bloc_id = User::find()->where(['id' => $user_id])->select(['bloc_id'])->scalar();
        $addons  = BlocAddons::find()->where(['bloc_id' => $bloc_id])->with(['addons'])->asArray()->all();
        $list    = [];
        foreach ($addons as $addon) {
            $list[] = $addon[ 'addons' ];
        }

        return $list;

    }

    public static function getUserMenus($is_sys): array
    {
        // 初始化菜单
        $AllNav       = Yii::$app->service->adminNavService->getMenu((int) $is_sys);
//        var_dump($AllNav['left']);die;
        $AddonsUser   = new AddonsUser();
        $module_names = [];
        if (isset(Yii::$app->user->identity->user_id)) {
            $module_names = $AddonsUser->find()->where([
                    'user_id' => Yii::$app->user->identity->user_id,
                ]
            )->with(['addons'])->asArray()->all();
        }

        foreach ($module_names as $key => &$value) {
            if (empty($value[ 'addons' ])) {
                unset($module_names[ $key ]);
            }
        }

        $moduleAll = $module_names ?? [];

        $Website = Yii::$app->settings->getAllBySection('Website');
        if ($Website) {
            $Website[ 'blogo' ] = isset($Website[ 'blogo' ]) ? ImageHelper::tomedia($Website[ 'blogo' ]) : '';
            $Website[ 'flogo' ] = isset($Website[ 'flogo' ]) ? ImageHelper::tomedia($Website[ 'flogo' ]) : '';
        }

        $user_id   = Yii::$app->user->id;
        $group_ids = \console\models\AuthAssignmentGroup::find()->where(['user_id' => $user_id])->select('group_id')->column();
        $Roles     = UserGroup::find()->where(['id' => $group_ids])->select('name')->column();

        return [
            'left'      => $AllNav[ 'left' ],
            'routeItem' => $AllNav[ 'routeItem' ],
            'top'       => $AllNav[ 'top' ],
            'Roles'     => $Roles,
            'moduleAll' => $moduleAll,
        ];
    }

    public static function deleteUser($user_id)
    {

        $user = User::findOne($user_id);
        if (!$user) {
            return false;
        }

        $result = $user->delete();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * 删除商户.
     *
     * @param $store_id
     *
     * @return void
     * @date 2022-10-28
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function deleteUserStore($store_id): void
    {
        BlocStore::deleteAll(['store_id' => $store_id]);
        StoreLabelLink::deleteAll(['store_id' => $store_id]);
    }

    /**
     * 删除资源文件.
     *
     * @param [type] $user_id
     *
     * @return void
     * @date 2022-10-28
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function deleteFile($user_id)
    {
        // dd_upload_file_user
    }

    public static function upStatus($user_id, $type): bool|int|array
    {
        $list         = UserStatus::getConstantsByName();
        $user         = User::findOne($user_id);
        $user->status = $list[ $type ];

        try {
            return $user->update();
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        }
    }

    /**
     * 用户注册完成后需要做的事情汇总.
     *
     * @param [type] $user_id
     *
     * @return void
     * @throws StaleObjectException
     * @throws Throwable
     * @throws \yii\db\Exception
     * @date 2022-10-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function initUserAuth($user_id, $is_create_bloc = false): void
    {
        /**
         * 初始化用户资产数据
         */
        UserAccountService::initAccount($user_id);
        // 初始权限组
        self::initGroup($user_id);
        if ($is_create_bloc) {
            self::SignBindBloc($user_id);
        }

        /**
         * 服务中心权限公开给所有人
         */
        $addons_id = DdAddons::find()->where(['identifie' => ['diandi_subscription', 'diandi_place']])->select('mid')->column();
        self::authUserAddons($user_id, $addons_id);
    }

    public static function initGroup($user_id): void
    {
        $authManager       = Yii::$app->getAuthManager();
        $defaultRoles      = $authManager->defaultRoles;
        $default_group_ids = AuthUserGroup::find()->where(['name' => $defaultRoles])->select('item_id')->column();
        $model             = new Assignment([
                'id'     => $user_id,
                'is_sys' => 3,
            ]
        );

        $model->assign([
                'role' => $default_group_ids,
            ]
        );

        $key = 'auth_' . $user_id . '_' . 'initmenu';
        Yii::$app->cache->delete($key);
    }

    /**
     * 授权用户组权限
     *
     * @param       $user_id
     * @param array $group_ids
     *
     * @return void
     * @throws ErrorException
     */
    public static function authUserGroup($user_id, array $group_ids = []): void
    {
        if (empty($group_ids)) {
            return;
        }
        $model = new Assignment([
                'id'     => $user_id,
                'is_sys' => 3,
            ]
        );

        $old_group_ids = AuthAssignmentGroup::find()->where(['user_id' => $user_id])->select('group_id')->column();

        $diffArr = ArrayHelper::array2diff($old_group_ids ?? [], $group_ids);
        $addArr  = $diffArr[ 'add' ];
        $delArr  = $diffArr[ 'delete' ];
        loggingHelper::writeLog('UserService', 'authUserGroup', '给用户授权用户组', [
                'user_id'   => $user_id,
                'old_sql'   => AuthAssignment::find()->where(['user_id' => $user_id])->select('item_id')->createCommand()->getRawSql(),
                'group_ids' => $addArr,
            ]
        );

        $model->assign([
                'role' => UserGroup::find()->where(['id' => $addArr])->select('item_id')->groupBy('item_id')->column(),
            ]
        );

        $model->revoke([
                'role' => UserGroup::find()->where(['id' => $delArr])->select('item_id')->groupBy('item_id')->column(),
            ]
        );

        $key = 'auth_' . $user_id . '_' . 'initmenu';
        Yii::$app->cache->delete($key);

    }

    public static function getAuthUserLink($user_id, $is_self = true): array
    {
        if (empty($user_id)) {
            return [];
        }
        $is_self = $is_self ? 1 : 0;

        $cacheKey = "auth_user_link_" . $is_self . "_" . $user_id;
        $cache    = Yii::$app->cache;

        $linkUserIds = $cache->get($cacheKey);
        if ($linkUserIds !== false) {
            return $linkUserIds;
        }

        $userLink    = new UserLink();
        $linkUserIds = $userLink::find()->where(['user_id' => $user_id])->select('link_user_id')->column() ?? [];

        if ($is_self) {
            $linkUserIds[] = $user_id;
            $linkUserIds   = array_unique($linkUserIds);
        }

        $cache->set($cacheKey, $linkUserIds, 7200);

        return $linkUserIds;
    }

    public static function getAuthUserLinkInfo($user_id): array
    {
        $userLink = new UserLink();
        $list     = $userLink::find()->alias('l')->where(['l.user_id' => $user_id])->joinWith('user')->select(['username', 'l.link_user_id'])->asArray()->all();
        return [
            'username' => $list ? array_column($list, 'username') : [],
            'user_id'  => $list ? array_column($list, 'link_user_id') : [],
            'list'     => $list,
        ];
    }

//    authUserLink
    public static function authUserLink($user_id, array $link_ids): array
    {
        UserLink::deleteAll([
                'user_id' => $user_id,
            ]
        );
        $assigned_ids = User::find()->where(['id' => $link_ids])->select('id')->column();

        loggingHelper::writeLog('UserService', 'authUserLink', '授权用户应用权限', [
                'user_id'      => $user_id,
                'assigned_ids' => $assigned_ids,
            ]
        );
        $userLink = new UserLink();

        foreach ($assigned_ids as $value) {
            $_userLink = clone $userLink;
            $data      = [
                'user_id'      => $user_id,
                'is_default'   => 0,
                'type'         => 1,
                'link_user_id' => $value,
                'status'       => 0,
            ];
            $_userLink->setAttributes($data);
            if (!$_userLink->save()) {
                $msg = ErrorsHelper::getModelError($_userLink);
                return ResultHelper::json(500, $msg);

            }
        }

        return ResultHelper::json(200, '授权成功');
    }

    /**
     * 授权用户应用权限
     *
     * @param       $user_id
     * @param array $addons_id
     *
     * @return void
     * @throws Exception
     */
    public static function authUserAddons($user_id, array $addons_id)
    {
        $AddonsUser   = new AddonsUser();
        $assigned_ids = $AddonsUser::find()->alias('u')->joinWith('addons as a')->where(['u.user_id' => $user_id, 'a.mid' => $addons_id])->select('a.mid')->indexBy('a.mid')->column();

        $add_ids = array_diff($addons_id, $assigned_ids);
        $addList = DdAddons::find()->where(['mid' => $add_ids])->asArray()->all();
        loggingHelper::writeLog('UserService', 'authUserAddons', '授权用户应用权限', [
                'user_id'      => $user_id,
                'addons_id'    => $addons_id,
                'add_ids'      => $add_ids,
                'addList'      => $addList,
                'assigned_ids' => $assigned_ids,
            ]
        );
        foreach ($addList as $value) {
            $_AddonsUser = clone $AddonsUser;
            $data        = [
                'user_id'     => $user_id,
                'is_default'  => 0,
                'type'        => 1,
                'module_name' => $value[ 'identifie' ],
                'status'      => 0,
            ];
            $_AddonsUser->setAttributes($data);
            if (!$_AddonsUser->save()) {
                $msg = ErrorsHelper::getModelError($_AddonsUser);
                throw new \Exception($msg);
            }
        }
        // 删除权限
        $delete_ids = array_diff($assigned_ids, $addons_id);
        $deleteList = DdAddons::find()->where(['mid' => $delete_ids])->select('identifie')->column();
        $AddonsUser::deleteAll([
                'user_id'     => $user_id,
                'module_name' => $deleteList,
            ]
        );
    }

    /**
     * 创建用户公司进行绑定.
     *
     * @param     $user_id
     * @param int $is_default
     *
     * @return void
     * @throws Throwable
     * @throws \yii\db\Exception
     * @throws StaleObjectException
     * @date 2022-08-28
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function SignBindBloc($user_id, int $is_default = 1): void
    {
        $transaction = Bloc::getDb()->beginTransaction();

        try {
            $have_user = User::findOne($user_id);
            if (!empty($have_user)) {
                // 创建公司
                $bloc     = new Bloc();
                $blocData = [
                    'business_name'  => $have_user[ 'username' ] . '的公司名称',
                    'pid'            => (int) $have_user[ 'parent_bloc_id' ],
                    'is_group'       => 0,
                    'province'       => 0,
                    'city'           => 0,
                    'district'       => 0,
                    'status'         => 0,
                    'address'        => '',
                    'register_level' => 0,
                    'longitude'      => '',
                    'latitude'       => '',
                ];
                $bloc->load($blocData, '');
                // 绑定用户
                if ($bloc->save()) {
                    $bloc_id  = $bloc->bloc_id;
                    $BlocUser = new UserBloc();
                    $data     = [
                        'user_id'    => $user_id,
                        'bloc_id'    => $bloc_id,
                        'status'     => 0,
                        'is_default' => $is_default,
                    ];
                    $BlocUser->load($data, '');
                    if (!$BlocUser->save()) {
                        $msg = ErrorsHelper::getModelError($BlocUser);
                        ErrorsHelper::throwError(0, $msg);
                    } else {
                        // 更新用户bloc_id
                        $userModel          = User::findOne($user_id);
                        $userModel->bloc_id = $bloc_id;
                        $userModel->update();
                    }
                } else {
                    $msg = ErrorsHelper::getModelError($bloc);
                    ErrorsHelper::throwError(0, $msg);
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            loggingHelper::writeLog('admin', 'SignBindBloc', 'Exception错误', $e);
            // 删除用户
            self::deleteUser($user_id);
            $transaction->rollBack();
            throw $e;
        } catch (Throwable $e) {
            loggingHelper::writeLog('admin', 'SignBindBloc', 'Throwable错误', $e);
            // 删除用户
            self::deleteUser($user_id);
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param       $user_id
     * @param int $store_id
     * @param array $addons_identifie_all
     *
     * @return void
     * @throws Exception
     */
    public static function AssignmentPermissionByUidAll($user_id, int $store_id = 0, array $addons_identifie_all = [])
    {
        try {
            foreach ($addons_identifie_all as $addons) {
                // 给用户授权应用权限
                $where = [
                    'module_name' => $addons,
                    'user_id'     => $user_id,
                ];
                if ($store_id) {
                    $where[ 'store_id' ] = $store_id;
                }
                $addonsUser = AddonsUser::find()->andWhere($where)->one();

                loggingHelper::writeLog('StoreService', 'createStore', 'addonsUser', [
                        'addonsUser' => $addonsUser,
                    ]
                );

                if (!$addonsUser) {
                    $addonsUser              = new AddonsUser();
                    $addonsUser->module_name = $addons;
                    $addonsUser->user_id     = Yii::$app->user->identity->user_id;
                    $addonsUser->store_id    = $store_id;
                    $addonsUser->type        = 1;
                    $addonsUser->status      = 1;
                    $addonsUser->is_default  = AddonsUser::find()->andWhere(['user_id' => Yii::$app->user->id])->andWhere('is_default = 1')->exists() ? 0 : 1;
                    if (!$addonsUser->save()) {
                        throw new Exception('保存用户模块数据失败!');
                    }
                }
                $user = User::find()->where(['id' => Yii::$app->user->identity->user_id])->one();
                if ($user->store_id == 0) {
                    $user->store_id = $store_id;
                    if (!$user->save(false)) {
                        throw new Exception('保存用户数据失败!');
                    }
                }
                self::AssignmentPermissionByUid($user_id, $addons);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 给创建用户授权整个应用的权限.
     *
     * @param $user_id
     * @param $addons_identifie
     *
     * @return array
     * @throws Exception
     * @date 2022-10-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function AssignmentPermissionByUid($user_id, $addons_identifie): array
    {
        if (!$user_id) {
            return ResultHelper::serverJson(1, 'user_id 不能为空');
        }

        if (!$addons_identifie) {
            return ResultHelper::serverJson(1, 'addons_identifie 不能为空');
        }
        loggingHelper::writeLog('StoreService', 'createStore', 'AssignmentPermissionByUid', [
                'user_id'          => $user_id,
                'addons_identifie' => $addons_identifie,
            ]
        );

        $items[ 'permission' ] = AuthItem::find()->where([
                'module_name'     => $addons_identifie,
                'parent_id'       => 0,
                'permission_type' => 1,
                'is_sys'          => 0,
            ]
        )->select('id')->column();
        loggingHelper::writeLog('StoreService', 'createStore', '初始权限数据', $items);

        if (!in_array(Yii::$app->id, ['install-console', 'app-console'])) {
            $class = Yii::$app->getUser()->identityClass ?: 'diandi\admin\models\User';
        } else {
            $class = 'diandi\admin\models\User';
        }

        $user = $class::findIdentity($user_id);
        // 获取原先的权限集
        $model      = new Assignment([
            'id'   => $user_id,
            'type' => 1, //0 系统，1模块
        ], $user
        );
        $itemsModel = $model->getItems(3); //3代表获取所有
        $all        = $itemsModel[ 'all' ];
        // 所有应用
        $all[ 'addons' ] = DdAddons::find()->asArray()->all();
        $addons_mids     = array_column($all[ 'addons' ], 'mid');
        // 所有商户
        $list      = Bloc::find()->with(['store'])->asArray()->all();
        $store_ids = [];
        foreach ($list as $key => &$value) {
            $value[ 'label' ] = $value[ 'business_name' ];
            $value[ 'id' ]    = $value[ 'bloc_id' ];
            $store            = $value[ 'store' ];
            if (!empty($value[ 'store' ])) {
                foreach ($store as $k => &$val) {
                    $val[ 'label' ] = $val[ 'name' ];
                    $val[ 'id' ]    = $val[ 'store_id' ];
                    $store_ids[]    = $val[ 'store_id' ];
                }
                $value[ 'children' ] = $store;
                $lists[]             = $value;
            } else {
                unset($list[ $key ]);
            }
        }
        $assigneds = $itemsModel[ 'assigned' ];
        // 用户的应用权限
        $AddonsUser            = new AddonsUser();
        $assigneds[ 'addons' ] = $AddonsUser::find()->alias('u')->joinWith('addons as a')->where(['u.user_id' => $user_id, 'a.mid' => $addons_mids])->select('a.mid')->indexBy('a.mid')->column();

        // 商户权限
        $UserStore            = new UserStore();
        $assigneds[ 'store' ] = $UserStore::find()->alias('u')->joinWith('store as s')->where(['u.user_id' => $user_id, 's.store_id' => $store_ids])->select('s.store_id')->indexBy('s.store_id')->column();

        $keyList = [
            'addons',
            'permission',
            'store',
        ];

        $assignedKey = [];
        unset($value);
        foreach ($assigneds as $key => $value) {
            $assignedKey[]    = $key;
            $assigned[ $key ] = array_keys($value);
        }

        $keyDiff = array_diff($keyList, $assignedKey);
        foreach ($keyDiff as $value) {
            $assigned[ $value ] = [];
        }

        $assigned_ids = $assigned[ 'permission' ];
        $authItems    = $items ? $items[ 'permission' ] : [];

        // 增加查看插件的权限
        $add_ids = array_diff($authItems, $assigned_ids);

        loggingHelper::writeLog('StoreService', 'createStore', 'AssignmentPermissionByUid', [
                'authItems'        => $authItems,
                'add_ids'          => $add_ids,
                'user_id'          => $user_id,
                'addons_identifie' => $addons_identifie,
            ]
        );

        $data = [
            'user_id'     => $user_id,
            'is_default'  => !empty($assigneds[ 'addons' ]) ? 0 : 1,
            'type'        => 1,
            'module_name' => $addons_identifie,
            'status'      => 0,
        ];
        $AddonsUser->load($data, '');
        if (!$AddonsUser->save()) {
            $msg = ErrorsHelper::getModelError($AddonsUser);
            loggingHelper::writeLog('StoreService', 'createStore', '授权插件错误', [
                    'err' => $msg,
                ]
            );
            return ResultHelper::json(400, $msg);
        }

        // 删除权限
        $delete_ids = array_diff($assigned_ids, $authItems);
        $deleteList = DdAddons::find()->where(['mid' => $delete_ids])->select('identifie')->column();
        $AddonsUser::deleteAll([
                'user_id'     => $user_id,
                'module_name' => $deleteList,
            ]
        );

        // 授权插件的权限
        $model = new Assignment([
                'id'     => $user_id,
                'is_sys' => 3,
            ]
        );

        // 增加权限
        $add_ids = array_diff($authItems, $assigned_ids);
        loggingHelper::writeLog('StoreService', 'createStore', '需要授权的数据', [
                'add_ids'      => $add_ids,
                'authItems'    => $authItems,
                'assigned_ids' => $assigned_ids,
            ]
        );

        if ($add_ids) {
            $model->assign([
                    'permission' => array_values($add_ids),
                ]
            );
        }

        $key = 'auth_' . $user_id . '_' . 'initmenu';
        Yii::$app->cache->delete($key);
        return ResultHelper::json(200, '授权成功');
    }

    /**
     * 用户创建商户后授权商户权限
     *
     * @param $user_id
     * @param $bloc_id
     * @param $store_id
     * @param $is_default
     *
     * @return array
     * @throws NotFoundHttpException
     * @date 2023-03-03
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function addUserBloc($user_id, $bloc_id, $store_id, $is_default): array
    {
        $UserStore = UserStore::find()->where([
                'user_id'  => $user_id,
                'bloc_id'  => $bloc_id,
                'store_id' => $store_id,
            ]
        )->asArray()->one();

        if ($UserStore) {
            UserStore::updateAll([
                'is_default' => $is_default,
            ], [
                    'user_id'  => $user_id,
                    'bloc_id'  => $bloc_id,
                    'store_id' => $store_id,
                ]
            );
        } else {
            $UserStoreModel = new UserStore();
            $Res            = $UserStoreModel->load([
                    'is_default' => $is_default,
                    'user_id'    => $user_id,
                    'bloc_id'    => $bloc_id,
                    'store_id'   => $store_id,
                    'status'     => 1,
                ], ''
                ) && $UserStoreModel->save();
            $msg            = ErrorsHelper::getModelError($UserStoreModel);
            if ($msg) {
                return ResultHelper::serverJson(1, $msg);

            }
        }

        return ResultHelper::serverJson(0, '保存成功');
    }

    /**
     * 删除指定用户缓存
     *
     * @param $uid
     *
     * @return void
     */
    public static function deleteUserCache($uid): void
    {
        $lists       = UserCacheKeyEnum::getConstantsByValue();
        $CacheHelper = new CacheHelper();
        foreach ($lists as $key => $item) {
            $CacheHelper->delByPrefix($key . $uid);
        }
    }

    public function getUserOptions($user_id, $bloc_id)
    {
        $is_admin = self::isSuperAdmin($user_id);

        if ($is_admin) {
            $users = User::find()
                ->where([
                        'bloc_id'     => $bloc_id,
                        'delete_time' => null,
                        'status'      => 1,
                    ]
                )->asArray()->all();

            $use_id_arr = array_column($users, 'id');
        } else {
            $user_link = UserLink::find()
                ->where([
                        'user_id' => $user_id,
                        'bloc_id' => $bloc_id,
                    ]
                )->asArray()->all();

            // 授权的用户id集合
            $user_link_id_arr = array_column($user_link, 'link_user_id');

            // // 用户授权的分公司
            // $user_store = UserStore::find()
            //     ->where([
            //         'user_id' => $user_id,
            //         'bloc_id' => $bloc_id,
            //     ])->asArray()->all();

            // $store_id_arr = array_column($user_store, 'store_id');

            // $store_user = User::find()
            //     ->where([
            //         'store_id'    => $store_id_arr,
            //         'delete_time' => null,
            //         'status'      => 1,
            //     ])->asArray()->all();

            // // 授权分公司的用户id集合
            // $store_user_id_arr = array_column($store_user, 'id');

            // $user_group = AuthAssignmentGroup::find()
            //     ->where([
            //         'user_id' => $user_id,
            //     ])->asArray()->all();

            // $user_group_id_arr = array_column($user_group, 'group_id');

            // $group_user = AuthAssignmentGroup::find()
            //     ->select([
            //         'user_id',
            //     ])
            //     ->where([
            //         'group_id' => $user_group_id_arr,
            //     ])->groupBy('user_id')
            //     ->asArray()
            //     ->all();

            // // 用户组包含的用户id集合
            // $group_user_id_arr = array_column($group_user, 'user_id');

            // $use_id_arr = array_merge($user_link_id_arr, $store_user_id_arr, $group_user_id_arr);
            $use_id_arr = $user_link_id_arr;
            $use_id_arr = array_unique($use_id_arr);
        }

        $users = User::find()
            ->where([
                    'delete_time' => null,
                    'status'      => 1,
                    'bloc_id'     => $bloc_id,
                    'id'          => $use_id_arr,
                ]
            )
            ->orderBy([
                    'store_id' => SORT_DESC,
                    'id'       => SORT_DESC,
                ]
            )
            ->with(['store'])
            ->asArray()
            ->all();

        $result = [];

        foreach ($users as $item) {
            $label = $item[ 'username' ];

            if (!empty($item[ 'store' ][ 'name' ])) {
                $name  = $item[ 'store' ][ 'name' ];
                $label .= " ($name)";
            }

            $result[] = [
                'value' => $item[ 'id' ],
                'label' => $label,
                'id'    => $item[ 'id' ],
                'name'  => $label,
            ];
        }

        return $result;
    }

}
