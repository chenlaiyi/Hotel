<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-12-30 02:21:32
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-09-09 12:04:15
 */


namespace common\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * 公司类型
 */
class BlocTypeEnum extends BaseEnum
{
    const HOTEL = 1;

    const INDUSTRY = 2;


    /**
     * @var string message category
     * You can set your own message category for translate the values in the $list property
     * Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'App';

    /**
     * @var array
     */
    public static $list = [
        self::HOTEL => '酒旅',
        self::INDUSTRY => '工业'
    ];
}
