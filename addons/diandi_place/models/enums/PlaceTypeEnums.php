<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-13 01:01:58
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 16:27:23
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 房间类型
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class PlaceTypeEnums extends BaseEnum
{
    const          status1 = 1;
    const          status2 = 2;
    const          status3 = 3;
    const          status4 = 4;
    const          status5 = 5;
    const          status6 = 6;
    const          status7 = 7;
    const          status8 = 8;
    const          status9 = 9;
    const          status10 = 10;
    const           status11 = 11;
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
        self::status1 => '酒店',
        self::status2 => '民宿',
        self::status3 => '公寓',
        self::status4 => '办公室',
        self::status5 => '茶室',
        self::status6 => '棋牌室',
        self::status7 => '自习室',
        self::status8 => '客栈',
        self::status9 => '露营地',
        self::status10 => '农家乐',
        self::status11 => '青年旅社'
    ];
}
