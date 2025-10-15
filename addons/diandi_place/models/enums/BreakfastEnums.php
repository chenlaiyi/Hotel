<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:52:08
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-04 22:25:48
 */
//   `breakfast` tinyint(3) NULL DEFAULT 0 COMMENT '0无早 1单早 2双早',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 早晨配套.
 *
 * @date 2023-01-09
 *
 * @example
 *
 * @author Wang Chunsheng
 *
 * @since
 */
class BreakfastEnums extends BaseEnum
{
    // 0无早 1单早 2双早
    const           NO = 0;
    const           SINGLE = 1;
    const           DOUBLE = 2;
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
        self::DOUBLE => '双份早餐',
        self::SINGLE => '单份早餐',
        self::NO => '无早餐',
    ];
}
