<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-26 15:58:12
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 房间房客状态
 * @date 2023-05-24
 * @example
 * @author YuH
 * @since
 */
class OrderMemberStatusEnums extends BaseEnum
{
    const          NORMAL = 1;
    const          FROZEN = 2;
    const          OUT    = 3;
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
        self::NORMAL => '正常',
        self::FROZEN => '冻结',
        self::OUT    => '退房',
    ];
}
