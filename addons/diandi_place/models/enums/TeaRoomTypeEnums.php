<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:54:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-04 22:48:31
 */
//   `status` int(11) NULL DEFAULT 1 COMMENT '房间状态',
namespace addons\diandi_place\models\enums;
use common\components\BaseEnum;
/**
 * 房型.
 *
 * @date 2023-01-09
 *
 * @example
 *
 * @author Wang Chunsheng
 *
 * @since
 */
class TeaRoomTypeEnums extends BaseEnum
{
    const          ROOM_TYPE1 = 1;
    const          ROOM_TYPE2 = 2;
    const          ROOM_TYPE3 = 3;
    const          ROOM_TYPE4 = 4;
    const          ROOM_TYPE5 = 5;
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
        self::ROOM_TYPE1 => '豪华包房',
        self::ROOM_TYPE2 => '普通包房',
        self::ROOM_TYPE3 => '大包',
        self::ROOM_TYPE4 => '中包',
        self::ROOM_TYPE5 => '小包',
    ];
}
