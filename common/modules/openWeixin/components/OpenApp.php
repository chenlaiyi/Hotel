<?php

namespace common\modules\openWeixin\components;

use common\modules\openWeixin\services\OpenWechatAuthService;
use yii\base\BaseObject;

class OpenApp  extends BaseObject
{
    /**
     * 代公众号实现业务过程
     * @return mixed
     */
    function getApp(): mixed
    {
        return OpenWechatAuthService::getOfficialAccount();
    }


    function getMiniProgram(): mixed
    {
        return OpenWechatAuthService::getMiniProgram();
    }
}