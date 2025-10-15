<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:58:45
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:15:54
 */
//   `receive_type` smallint(6) NULL DEFAULT NULL COMMENT '领取方式：1.领取 2.购买 3.充值赠送',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 领取方式：1
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class ReceiveTypeEnums extends BaseEnum
{
   const          FREE = 1;
   const          BUY = 2;
   const          RECAHGE = 3;
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
      self::FREE => '免费领取',
      self::BUY => '购买',
      self::RECAHGE => '充值赠送'
   ];
}
