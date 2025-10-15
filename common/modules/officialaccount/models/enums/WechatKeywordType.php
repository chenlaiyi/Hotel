<?php

namespace common\modules\officialaccount\models\enums;

use common\components\BaseEnum;

class WechatKeywordType extends BaseEnum
{

    /**
     * text类型请求 直接匹配关键字
     */
    const TYPE_MATCH = 'match';
    /**
     * text类型请求 包含关键字
     */
    const TYPE_REGULAR = 'include';
    /**
     * text类型请求 正则表达式
     */
    const TYPE_INCLUDE = 'regular';
    /**
     * image类型请求
     */
    const TYPE_IMAGE = 'image';
    /**
     * 语音类型请求
     */
    const TYPE_VOICE = 'voice';
    /**
     * 视频类型请求
     */
    const TYPE_VIDEO = 'video';
    /**
     * 短视频类型请求
     */
    const TYPE_SHORT_VIDEO = 'short_video';
    /**
     * 位置类型请求
     */
    const TYPE_LOCATION = 'location';
    /**
     * 链接类型请求
     */
    const TYPE_LINK = 'link';
    /**
     * 关注请求
     */
    const TYPE_SUBSCRIBE = 'subscribe';
    /**
     * 取消关注请求
     */
    const TYPE_UNSUBSCRIBE = 'unsubscribe';

    /**
     * 触发类型
     * @var array
     */
    public static $list = [
        self::TYPE_MATCH => '直接匹配关键字',
        self::TYPE_REGULAR => '正则匹配关键字',
        self::TYPE_INCLUDE => '包含关键字',
        self::TYPE_IMAGE => '图片请求',
        self::TYPE_VOICE => '语音请求',
        self::TYPE_VIDEO => '视频请求',
        self::TYPE_SHORT_VIDEO => '短视频请求',
        self::TYPE_LOCATION => '位置请求',
        self::TYPE_LINK => '链接请求',
        self::TYPE_SUBSCRIBE => '关注请求',
        self::TYPE_UNSUBSCRIBE => '取消关注请求'
    ];
}