<?php

namespace common\modules\wechat\controllers\admin;

use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\officialaccount\services\OfficialaccountService;
use common\modules\wechat\services\WechatService;

class ConfigController extends AController
{
    public $modelClass = 'DdAddons';

    public string $modelSearchName='OfficialaccountService';

    /**
     * 获取公众号配置信息
     * @return array
     */
    public function actionInfo(): array
    {
        $Res = WechatService::getWechatConf();
        return ResultHelper::json(200, '获取成功',$Res);
    }
}