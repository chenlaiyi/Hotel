<?php

namespace common\modules\officialaccount\models\enums;

use common\components\BaseEnum;

/**
 * 微信素材类型
 */
class WechatMaterialTypesEnum extends BaseEnum
{
//$type 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
    const TYPE_IMAGE = 'image';
    const TYPE_THUMB = 'thumb';
    const TYPE_VOICE = 'voice';
    const TYPE_VIDEO = 'video';
    const TYPE_ARTICLE = 'news';



    public static $messageCategory = 'App';


    public static $list = [
        self::TYPE_IMAGE => '图片',
        self::TYPE_THUMB => '缩略图',
        self::TYPE_VOICE => '语音',
        self::TYPE_VIDEO => '视频',
        self::TYPE_ARTICLE => '图文',
    ];


}