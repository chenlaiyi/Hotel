<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:58:01
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:19:42
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 酒店状态
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class PlaceStatusEnums extends BaseEnum
{
    const          OPEN = 1;
    const          NOOPEN = 2;
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
        self::OPEN => '公开',
        self::NOOPEN => '未公开',
    ];
}
