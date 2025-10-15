<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:51:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:18:45
 */
namespace addons\diandi_place\models\enums;
use yii2mod\enum\helpers\BaseEnum;
/**
 * 语言类型
 * @date 2023-01-09
 * @example
 * @author Wang Chunsheng
 * @since
 */
class LanguageEnums extends BaseEnum
{
    const          CHINESE = 1;
    const          ENGLISH = 2;
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
        self::CHINESE => '中文',
        self::ENGLISH => '英文'
    ];
}
