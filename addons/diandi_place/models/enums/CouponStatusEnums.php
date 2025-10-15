<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:58:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 15:24:25
 */
//   `is_use` smallint(6) NULL DEFAULT NULL COMMENT '是否正在使用 ：1.未使用  2.使用中  3.已使用 4.已过期',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 卡券状态
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class CouponStatusEnums extends BaseEnum
{
   const          status1 = 0;
   const          status2 = 1;
   const          status3 = 2;
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
      self::status1 => '未使用',
      self::status2 => '已使用',
      self::status3 => '已失效'
   ];
}
