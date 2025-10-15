<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:51:24
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:08:42
 */
//  订房是否免费取消 `free_cancel` tinyint(4) NULL DEFAULT 0 COMMENT '是否免费取下1是0否',
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 订房是否免费取消
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class FreeCancelEnums extends BaseEnum
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
      self::YES => '可免费取消',
      self::NO => '收费取消',
   ];
}
