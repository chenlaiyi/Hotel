<?php

namespace common\components\Cache\enums;

use yii2mod\enum\helpers\BaseEnum;

class UserCacheKeyEnum extends BaseEnum
{
    public const PERMISSIONS = 'permissions_';
    public const GET_DIRECT_PERMISSIONS_BY_USER = 'getDirectPermissionsByUser_';
    public const STORES_BLOC = 'storesbloc-';
    public const BLOCS = 'blocs_';
    public const STORES = 'stores_';
    public const BLOCS_ALL = 'blocsall_';
    public const AUTH_SYS_INITMENU = 'auth_sys_initmenu_';
    public const AUTH_ALL_INITMENU = 'auth_all_initmenu_';

    public static $messageCategory = 'App';

    public static $list = [
        self::PERMISSIONS => '权限列表',
        self::GET_DIRECT_PERMISSIONS_BY_USER => '用户权限列表',
        self::STORES_BLOC => '商户列表',
        self::BLOCS => '商户列表',
        self::STORES => '商户列表',
        self::BLOCS_ALL => '商户列表',
        self::AUTH_SYS_INITMENU => '系统菜单',
        self::AUTH_ALL_INITMENU => '全部菜单',
    ];

}