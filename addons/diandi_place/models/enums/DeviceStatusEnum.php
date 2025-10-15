<?php
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
class DeviceStatusEnum extends BaseEnum
{
    const          BINDING = 1;
    const          NO_BIND = 2;
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
        self::BINDING => '已绑定',
        self::NO_BIND => '未绑定',
    ];
}