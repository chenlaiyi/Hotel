<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:50:13
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:12:10
 */
//   `time_length` int(11) NULL DEFAULT 0 COMMENT '租约时长（年，天，月，时）',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 租约时长
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class TimelengthEnums extends BaseEnum
{
   const          HOUR = 1;
   const          DAY = 2;
   const          MOUTH = 3;
   const          YEAR = 4;
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
      self::HOUR => '小时',
      self::DAY => '天',
      self::MOUTH => '月',
      self::YEAR => '年',
   ];
}
