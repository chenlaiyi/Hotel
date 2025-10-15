<?php

namespace common\modules\openWeixin\services;

use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use EasyWeChat\Kernel\Encryptor;
use EasyWeChat\Kernel\Support\XML;
use Yii;

class OpenWechatMsgService extends BaseService
{
    static function decodeMsg($from_xml,$nonce,$timeStamp,$msg_sign): array
    {
        $encodingAesKey = Yii::$app->params['wechatOpenPlatformConfig']['aes_key'];
        $token = Yii::$app->params['wechatOpenPlatformConfig']['token'];
        $appid = Yii::$app->params['wechatOpenPlatformConfig']['app_id'];
        // 实例化一个 Encryptor 对象
        $encryptor = new Encryptor($appid, $token, $encodingAesKey);
        // 其中 $appId、$token、$aesKey 为你的微信公众平台上的 AppID, Token 和 EncodingAESKey
        try {
            // 解密微信消息
            $contents = XML::parse($from_xml);
            loggingHelper::writeLog('openWeixin', 'decodeMsg', '解密后-内容1', [
                'contents'=> $contents
            ]);
            $decryptedData = $encryptor->decrypt($contents['Encrypt'],$msg_sign, $nonce, $timeStamp);
            loggingHelper::writeLog('openWeixin', 'decodeMsg', '解密后-内容', [
                'decryptedData'=> $decryptedData
            ]);
            return [
                'msg' => $decryptedData
            ];
        } catch (\Throwable $e) {
            // 处理解密错误
            loggingHelper::writeLog('openWeixin', 'decodeMsg', '解密后-失败', [
                'msg' => $e->getMessage()
            ]);
            return ResultHelper::json(400,$e->getMessage());
        }
    }
}