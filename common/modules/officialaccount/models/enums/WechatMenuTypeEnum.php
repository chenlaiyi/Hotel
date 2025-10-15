<?php

namespace common\modules\officialaccount\models\enums;

use common\components\BaseEnum;

class WechatMenuTypeEnum extends BaseEnum
{
    const   appmsg = 1; // 图文消息
    const   text = 2; // 文字
    const   img = 3; // 图片
    const   audio = 4; // 音频
    const   snap = 5; // 视频号动态
    const   video = 6; // 视频
    const   cardticket = 7; // 卡券


    /**
     * @var string message category
     * You can set your own message category for translate the values in the $list property
     * Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'App';

    /**
     * @var array
     */
    public static $list = [
        self::appmsg => '图文消息',
        self::text => '文字',
        self::img => '图片',
        self::audio => '音频',
        self::snap => '视频号动态',
        self::video => '视频',
        self::cardticket => '卡券',
    ];

}