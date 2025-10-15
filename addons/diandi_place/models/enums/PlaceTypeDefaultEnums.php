<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-08 11:38:50
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 房间类型默认状态
 * @date 2023-05-24
 * @example
 * @author Wang Chunsheng
 * @since
 */
class PlaceTypeDefaultEnums extends BaseEnum
{
    const          NORMAL  = 0;
    const          DEFAULT = 1;
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
        self::DEFAULT => '默认',
        self::NORMAL  => '正常',
    ];
}
