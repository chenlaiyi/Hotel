<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-02 14:05:38
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 使用状态
 * @date 2023-05-24
 * @example
 * @author YuH
 * @since
 */
class IsUseEnums extends BaseEnum
{
    const          NO_USE  = 1;
    const          USE_ING = 2;
    const          USED    = 3;
    const          EXPIRE  = 4;
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
        self::NO_USE  => '未使用',
        self::USE_ING => '使用中',
        self::USED    => '已使用',
        self::EXPIRE  => '已过期',
    ];
}
