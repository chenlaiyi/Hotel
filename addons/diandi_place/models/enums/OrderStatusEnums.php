<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-13 01:01:58
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-30 16:15:21
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 订单状态
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class OrderStatusEnums extends BaseEnum
{
    const          NOTPAY = 1;
    const          ISPAY = 2;
    const          CHECKIN = 3;
    const          CHECKOUT = 4;
    const          END = 5;
    const          CANCEL = 6;
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
        self::NOTPAY => '待支付',
        self::ISPAY => '待入住',
        self::CHECKIN => '已入住',
        self::CHECKOUT => '待评价',
        self::END => '已完成',
        self::CANCEL => '已取消',
    ];
}
