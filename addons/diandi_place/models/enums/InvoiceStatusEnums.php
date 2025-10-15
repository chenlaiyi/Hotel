<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:57:05
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:09:02
 */
//   `status` tinyint(3) NULL DEFAULT NULL COMMENT '是否开票: 1.已开  2.未开',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 是否开票
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class InvoiceStatusEnums extends BaseEnum
{
   const          YES = 1;
   const          NO = 0;
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
      self::YES => '已开',
      self::NO => '未开',
   ];
}
