<?php
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
class LandlordStatusEnums extends BaseEnum
{
    //0 注册，1认证中2认证通过3认证失败
    const          REGISTER = 1;
    const          AUTHIN = 2;
    const          AUTHPASS = 3;
    const          AUTHFAIL = 4;
    public static $messageCategory = 'App';
    /**
     * @var array
     */
    public static $list = [
        self::REGISTER => '已注册',
        self::AUTHIN => '认证中',
        self::AUTHPASS => '认证通过',
        self::AUTHFAIL => '认证失败'
    ];
}