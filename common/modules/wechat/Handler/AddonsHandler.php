<?php

namespace common\modules\wechat\Handler;


use common\helpers\loggingHelper;
use common\modules\openWeixin\services\OpenWechatAuthService;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\ServiceContainer;
use EasyWeChat\OpenPlatform\Server\Guard;

class AddonsHandler implements EventHandlerInterface
{
    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * EchoStrHandler constructor.
     *
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }


    public function handle($payload = null)
    {
        loggingHelper::writeLog('AddonsHandler','handle','获取消息主题内容',[
//           'addons' => $modules,
            'event' => $payload,
        ]);
        var_dump(21432);
        $modules = \Yii::$app->getModules();
//    elseif ($message['Event'] == 'subscribe' && $this->startsWith($message['EventKey'], 'qrscene_sy')){
//        /**
//         * 正一净收衣处理
//         */
//    OpenWechatAuthService::zyjSy($message['FromUserName'],$message['Ticket']);
//    }elseif ($message['Event'] == 'SCAN' &&  $this->startsWith($message['EventKey'], 'sy')){
//        /**
//         * 正一净收衣处理
//         */
//    OpenWechatAuthService::zyjSy($message['FromUserName'],$message['Ticket']);
//    }
//        $message = $this->getMessage();
        // 确保传入的是MessageReceived事件
//        if (!$event instanceof MessageReceived) {
//            return;
//        }
//
//        // 获取消息内容
//        $messageContent = $event->getMessage()->Content;
//
//        // 示例处理逻辑：简单回复用户相同的内容
//        $replyContent = "您刚才发送的消息是：{$messageContent}";
//
//        // 构建回复消息
//        $reply = new Text($replyContent);
//
//        // 假设这里有一个 sendMessage 方法可以用来回复用户
//        // 注意：这一步需要根据您的应用实际逻辑来实现，下面的 sendMessage 是示意性的
//        $this->sendMessage($event, $reply);
    }
}
