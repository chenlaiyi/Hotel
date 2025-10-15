<?php

namespace common\modules\officialaccount\components;

use common\modules\officialaccount\services\OfficialaccountService;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class wechat extends BaseObject
{
    /**
     * @throws InvalidConfigException
     */
    function getApp($type = 0)
    {
        return OfficialaccountService::getWechatApp($type);
    }
}