<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2023-05-19 10:08:30
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-19 10:08:30
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 房东认证
 * @date 2023-05-18
 * @example
 * @author YuH
 * @since
 */
class PlaceMemberAuthStatusEnums extends BaseEnum
{
    const          NO_AUTH      = 0;
    const          AUTHING      = 1;
    const          AUTH_SUCCESS = 2;
    const          AUTH_FAIL    = 3;
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
        self::AUTH_FAIL    => '认证失败',
        self::AUTH_SUCCESS => '认证成功',
        self::AUTHING      => '认证中',
        self::NO_AUTH      => '未认证',
    ];
}
