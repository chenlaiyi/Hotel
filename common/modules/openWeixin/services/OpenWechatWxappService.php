<?php

namespace common\modules\openWeixin\services;

use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\modules\openWeixin\models\OpenWechatRegisterMiniprogram;
use common\services\BaseService;

/**
 * 微信小程序业务逻辑本地数据操作
 */
class OpenWechatWxappService extends BaseService
{

    static function registerMiniprogram($name, $code, $code_type, $legal_persona_wechat, $legal_persona_name,$component_phone = ''): array
    {
        $params = [
            'name'=> $name,
            'code'=> $code,
            'code_type'=> $code_type,
            'legal_persona_wechat'=> $legal_persona_wechat,
            'legal_persona_name'=> $legal_persona_name,
            'component_phone'=> $component_phone
        ];
        $OpenWechatRegisterMiniprogram = new OpenWechatRegisterMiniprogram();
        if ($OpenWechatRegisterMiniprogram->load($params,'') && $OpenWechatRegisterMiniprogram->save()){
            return ResultHelper::json(200, '注册成功，等待审核');
        }else{
            $msg = ErrorsHelper::getModelError($OpenWechatRegisterMiniprogram);
            return ResultHelper::json(400, $msg);
        }
    }

}