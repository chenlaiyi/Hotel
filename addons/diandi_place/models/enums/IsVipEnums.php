<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:58:59
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:09:18
 */
//   `is_vip` int(11) NULL DEFAULT 0 COMMENT '是否是会员',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 是否是会员
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class IsVipEnums extends BaseEnum
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
      self::YES => '是',
      self::NO => '不显',
   ];
}
