<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:54:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:14:21
 */
//   `status` int(11) NULL DEFAULT 1 COMMENT '房间状态',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 房间状态
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class RoomStatusEnums extends BaseEnum
{
    const          LEAVE_UNUSED = 1;
    const          ORDER_BOOK = 2;
    const          RENT_OUT = 3;
    const          RENT_OUT_IN = 4;
    const          MAINTAIN = 5;
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
        self::LEAVE_UNUSED => '闲置',
        self::ORDER_BOOK => '预订',
        self::RENT_OUT => '入住中',
        self::RENT_OUT_IN => '已入住',
        self::MAINTAIN => '维护',
    ];
}
