<?php

namespace common\modules\wechat\components;

use common\modules\wechat\services\WechatService;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class miniProgram extends BaseObject
{
    /**
     * @throws InvalidConfigException
     */
    function getApp($type = 0)
    {
        return WechatService::getWechatApp($type);
    }
}