<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:51:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-08 18:01:30
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 承租方式
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class LesseeEnums extends BaseEnum
{
    const          ENTIRE_TENANCY = 1;
    const          JOINT_RENT = 2;
    /**
     * @var string message category
     *             You can set your own message category for translate the values in the $list property
     *             Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'App';
    /**
     * @var array
     */
    public static $list = [
        self::ENTIRE_TENANCY => '整租',
        self::JOINT_RENT => '合租'
    ];
}
