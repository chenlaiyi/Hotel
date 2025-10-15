<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 16:12:15
 */


namespace admin\models\enums;

use common\components\BaseEnum;

class MenuTypeEnum extends BaseEnum
{
    //1菜单2按钮3外链4iframe
    const MENU = 1;
    const OUTLINK = 2;
    const IFRAME = 3;
    const SCREEN = 4;

    public static $messageCategory = 'App';

    /**
     * @var array
     */
    public static $list = [
        self::MENU => '页面',
        self::OUTLINK => '外链',
        self::IFRAME => 'Iframe',
        self::SCREEN => '大屏',
    ];
}
