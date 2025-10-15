<?php

namespace common\modules\openWeixin\Handler;

use common\helpers\loggingHelper;
use common\modules\openWeixin\services\OpenWechatMsgService;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\OpenPlatform\Server\Guard;
use Yii;

class publicHandler  implements EventHandlerInterface
{
    protected $server;

    protected $message;

    protected $open_platform;
    protected string $moduleName;
    /**
     * @var mixed|string
     */
    protected mixed $eventKey;

    /**
     * @param Guard $server
     * @param mixed $openPlatform
     */
    public function __construct(\EasyWeChat\OpenPlatform\Server\Guard $server, mixed $openPlatform)
    {
        $this->server = $server;
        $this->open_platform = $openPlatform;
    }

    public function handle($payload = null)
    {

        $from_xml = Yii::$app->request->getRawBody();
        $nonce = Yii::$app->request->input('nonce');
        $timeStamp = Yii::$app->request->input('timestamp');
        $msg_sign = Yii::$app->request->input('msg_signature');
        loggingHelper::writeLog('openWeixin-publicHandler', 'handle', '接收到消息-第三方平台', [
            'from_xml' => $from_xml,
            'nonce' => $nonce,
            'timeStamp' => $timeStamp,
            'msg_sign' => $msg_sign,
        ]);
        $server = $this->open_platform->server;
        // 处理授权成功事件
        $server->push(function ($message) use ($from_xml, $nonce, $timeStamp, $msg_sign) {
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', '事件消息处理', [
                'message' => $message,
                'event' => Guard::EVENT_AUTHORIZED
            ]);


            $Res = OpenWechatMsgService::decodeMsg($from_xml, $nonce, $timeStamp, $msg_sign);
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', 'xml解析', [
                'Res' => $Res
            ]);
            return null;

        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) use ($from_xml, $nonce, $timeStamp, $msg_sign) {
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', '事件消息处理', [
                'message' => $message,
                'event' => Guard::EVENT_UPDATE_AUTHORIZED
            ]);
            $Res = OpenWechatMsgService::decodeMsg($from_xml, $nonce, $timeStamp, $msg_sign);

            loggingHelper::writeLog('openWeixin-publicHandler', Guard::EVENT_AUTHORIZED, 'xml解析', [
                'Res' => $Res
            ]);
            return null;
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) use ($from_xml, $nonce, $timeStamp, $msg_sign) {
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', '事件消息处理', [
                'message' => $message,
                'event' => Guard::EVENT_UNAUTHORIZED
            ]);
            $Res = OpenWechatMsgService::decodeMsg($from_xml, $nonce, $timeStamp, $msg_sign);
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', 'xml解析', [
                'Res' => $Res
            ]);
            return null;
        }, Guard::EVENT_UNAUTHORIZED);

        //快速注册完成
        $server->push(function ($message) use ($from_xml, $nonce, $timeStamp, $msg_sign) {
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', '事件消息处理', [
                'message' => $message
            ]);


            $Res = OpenWechatMsgService::decodeMsg($from_xml, $nonce, $timeStamp, $msg_sign);
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', 'xml解析', [
                'Res' => $Res
            ]);
            return null;

        }, Guard::EVENT_THIRD_FAST_REGISTERED);

        //tick
        $server->push(function ($message) use ($from_xml, $nonce, $timeStamp, $msg_sign) {
            loggingHelper::writeLog('openWeixin-publicHandler', 'handle', '事件消息处理', [
                'message' => $message,
                'event' => Guard::EVENT_COMPONENT_VERIFY_TICKET
            ]);

            return null;

        }, Guard::EVENT_COMPONENT_VERIFY_TICKET);
    }
}