<?php

namespace common\modules\officialaccount\models\enums;

use common\components\BaseEnum;

/**
 * ?
 */
class WechatMaterialTypeEnum extends BaseEnum
{
    const MATERIAL_TEMPORARY = 1;
    const MATERIAL_PERMANENT = 2;

    /**
     * @var string message category
     * You can set your own message category for translate the values in the $list property
     * Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'App';

    /**
     * 素材类别
     * @var array
     */
    public static $list = [
        self::MATERIAL_TEMPORARY => '??',
        self::MATERIAL_PERMANENT => 'ッ'
    ];
}