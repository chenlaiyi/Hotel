<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:58:01
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-04 22:22:35
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 酒店星级.
 *
 * @date 2023-01-09
 *
 * @example
 *
 * @author Wang Chunsheng
 *
 * @since
 */
class PlaceLevelEnums extends BaseEnum
{
    const          LEVEL1 = 1;
    const          LEVEL2 = 2;
    const          LEVEL3 = 3;
    const          LEVEL4 = 4;
    const          LEVEL5 = 5;
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
        self::LEVEL1 => '一星级',
        self::LEVEL2 => '二星级',
        self::LEVEL3 => '三星级',
        self::LEVEL4 => '四星级',
        self::LEVEL5 => '五星级',
    ];
}
