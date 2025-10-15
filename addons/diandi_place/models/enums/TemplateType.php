<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:50:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-08 14:37:28
 */
// 幻灯片类型 1.商店头部幻灯片  2.商店中间幻灯片
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 房源模板类型
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class TemplateType extends BaseEnum
{
    const          hotelManagement = 1;
    const          homestayManagement = 2;
    const          apartmentManagement = 3;
    const          teaManagement = 4;
    const          chessManagement = 5;
    const          factoryManagement = 6;
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
        self::hotelManagement => '酒店',
        self::homestayManagement => '民宿',
        self::apartmentManagement => '公寓',
        self::teaManagement => '茶室',
        self::chessManagement => '棋牌',
        self::factoryManagement => '工厂'
    ];
}
