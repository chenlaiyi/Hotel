<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-08-09 13:32:52
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
class DeviceTypeEnum extends BaseEnum
{
    const          HOTEL_LOCK     = 81;
    const          SWITCH         = 48;
    const          ELECTRIC_METER = 82;
    const          BREAKER        = 7;
    const          GATEWAY        = 80;
    const          MONITORING        = 8;
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
        self::HOTEL_LOCK     => '智能门锁',
        self::SWITCH         => '智能开关',
        self::ELECTRIC_METER => '电表',
        self::BREAKER        => '智能门禁',
        self::GATEWAY        => '智能网关',
        self::MONITORING        => '视频监控',
    ];
}
