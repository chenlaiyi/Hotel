<?php


/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-07-11 13:04:33
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-11 13:22:41
 */

namespace common\modules\wechat\controllers\api;

use api\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\wechat\services\DecryptService;

class DecryptController extends AController
{
    protected array $authOptional = ['msg','mobile'];

    public function actionMsg(): array
    {

        $encryptedData = \Yii::$app->request->input('encryptedData');
        $iv = \Yii::$app->request->input('iv');
        $code = \Yii::$app->request->input('code');
        $Res = DecryptService::decryptWechatData($encryptedData, $iv, $code);
        return ResultHelper::json(200, '解密成功', $Res);
    }

    public function actionMobile(): array
    {
        $code = \Yii::$app->request->input('code');
        $app = \Yii::$app->wechat->miniProgram;
        $mobile = $app->phone_number->getUserPhoneNumber($code);
        if ($mobile['errcode'] === 0){
            return ResultHelper::json(200, '解密成功', $mobile['phone_info']);
        }else{
            return ResultHelper::json(400, '解密失败',$mobile);
        }
    }
}
