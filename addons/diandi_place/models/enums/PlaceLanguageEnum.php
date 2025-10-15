<?php
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
class PlaceLanguageEnum extends BaseEnum{
    const          CN = 1;
    const          EN = 2;
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
        self::CN => '中文',
        self::EN => '英文',
    ];
}