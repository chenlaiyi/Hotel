<?php

namespace common\plugins\diandi_auth\models\enums;

use common\components\BaseEnum;

class ServiceMainEnum extends BaseEnum
{
    const OPERATION = 1;
    const PRODUCTION = 2;
    const STORE = 3;
    /**
     * @var string message category
     *             You can set your own message category for translate the values in the $list property
     *             Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'App';

    public static $list = [
        self::OPERATION => '运营',
        self::PRODUCTION => '生产',
        self::STORE => '门店',
    ];


}
