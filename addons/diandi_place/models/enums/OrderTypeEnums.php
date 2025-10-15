<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:54:54
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:05:31
 */
//   `order_type` smallint(6) NULL DEFAULT NULL COMMENT '订单类型 1.包间订单  2.续费订单',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 订单类型
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class OrderTypeEnums extends BaseEnum
{
   const          HOTEL = 1;
   const          ROOM = 2;
   const          RIM = 3;
   const          AD = 4;
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
      self::HOTEL => '酒店',
      self::ROOM => '房间',
      self::RIM => '周边',
      self::AD => '广告',
   ];
}
