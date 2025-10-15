<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-13 01:01:58
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-04 11:43:09
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 酒店列表排序.
 *
 * @date 2023-01-09
 *
 * @example
 *
 * @author Wang Chunsheng
 *
 * @since
 */
class PlaceOrderByEnums extends BaseEnum
{
    const          orderStatus1 = 1;
    const          orderStatus2 = 2;
    const          orderStatus3 = 3;
    const          orderStatus4 = 4;
    const          orderStatus5 = 5;
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
        self::orderStatus1 => '综合排序',
        self::orderStatus2 => '好评排序',
        self::orderStatus3 => '低价优先',
        self::orderStatus4 => '高价优先',
        self::orderStatus5 => '近距离优先',
    ];
}
