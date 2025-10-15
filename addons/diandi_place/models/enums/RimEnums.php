<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-13 01:01:58
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-04 11:41:35
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 周边类型.
 *
 * @date 2023-01-09
 *
 * @example
 *
 * @author Wang Chunsheng
 *
 * @since
 */
class RimEnums extends BaseEnum
{
    const status1 = 1;
    const status2 = 2;
    const status3 = 3;
    const status4 = 4;
    const status5 = 5;
    const status6 = 6;
    const status7 = 7;
    const status8 = 8;
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
        self::status1 => '景点',
        self::status2 => '地铁线',
        self::status3 => '高校',
        self::status4 => '机场车站',
        self::status5 => '商业区',
        self::status6 => '行政区',
        self::status7 => '医院',
        self::status8 => '场馆演出',
    ];
}
