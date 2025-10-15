<?php

namespace common\modules\officialaccount\models\enums;

use common\components\BaseEnum;


class WechatMsgTypeEnum extends BaseEnum
{
    const          msg    = 1;

    const          view = 2;

    const          miniprogram = 3;



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
        self::msg=>"发送消息",
        self::view=>"跳转网页",
        self::miniprogram =>"跳转小程序",
    ];

}