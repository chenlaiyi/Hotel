<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-13 01:01:58
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-01-09 11:06:46
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 人员类型
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class PersionEnums extends BaseEnum
{
    const          status1 = 1;
    const          status2 = 2;
    const          status3 = 3;
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
        self::status1 => '成人',
        self::status2 => '儿童',
        self::status3 => '客人'
    ];
}
