<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:56:00
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 11:03:04
 */
//   `type` smallint(6) NULL DEFAULT NULL COMMENT '卡券类型  1：代金券 2：时长卡  3：次卡 4：折扣券 5：体验券',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 卡券类型
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class CouponTypeEnums extends BaseEnum
{
   const          status1 = 1;
   const          status2 = 2;
   // const          status3 = 3;
   // const          status4 = 4;
   // const          status5 = 5;
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
      self::status1 => '代金券',
      self::status2 => '折扣券',
      // self::status3 => '次卡',
      // self::status4 => '时长卡',
      // self::status5 => '体验券',
   ];
}
