<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:55:14
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:17:40
 */
//   `pay_type` smallint(6) NULL DEFAULT NULL COMMENT '支付方式：1.现金支付 2.余额支付',
//   `pay_type` smallint(6) NULL DEFAULT NULL COMMENT '支付方式：1.现金支付 2.余额支付 3其他平台购买',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 支付方式
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class PayTypeEnums extends BaseEnum
{
   const          CASH = 1;
   const          BALANCE = 2;
   const          ON_LINE = 3;
   const          OTHER = 4;
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
      self::CASH => '现金支付',
      self::BALANCE => '余额支付',
      self::ON_LINE => '在线支付',
      self::OTHER => '其他支付',
   ];
}
