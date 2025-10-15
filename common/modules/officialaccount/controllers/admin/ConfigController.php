<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\officialaccount\services\OfficialaccountService;

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
        $Res = OfficialaccountService::getWechatConf();
        return ResultHelper::json(200, '获取成功',$Res);
    }
}