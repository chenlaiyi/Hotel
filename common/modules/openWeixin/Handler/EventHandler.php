<?php

namespace common\modules\openWeixin\Handler;


use common\helpers\loggingHelper;
use common\modules\openWeixin\services\OpenWechatAuthService;
use diandi\addons\models\DdAddons;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OpenPlatform\Server\Guard;


class EventHandler implements EventHandlerInterface
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
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws InvalidConfigException
     */
    public function __construct(Guard $server, $openPlatform)
    {
        $this->server = $server;
        $this->message = $server->getMessage();
        $this->open_platform = $openPlatform;
        /**
         * 如果EventKey含有#，就分割出前面的模块名，并调用相应的模块的handler
         */
        $eventKey = $this->message['EventKey'];
        $moduleName = '';
        if (str_contains($eventKey, '#')) {
            $eventKey = explode('#', $eventKey);
            $moduleName = $eventKey[0];
            $eventKey = $eventKey[1];
        }
        $this->moduleName = $moduleName;
        $this->eventKey = $eventKey;
        /**
         * 重置message
         */
        $this->message['EventKey'] = $eventKey;
    }

    public function handle($payload = null): void
    {
        try {
            $FromUserName = $this->message['FromUserName']??'';
            loggingHelper::writeLog('openWeixin','EventHandler','全局调度微信消息1',[
                'FromUserName' => $FromUserName,
                'message' => $this->message,
                'addons' => $this->moduleName
            ]);

            /**
             * 为空，全局消息，不为空，模块消息
             */
            if ($this->moduleName){
                $customer_service = $this->open_platform->customer_service;

                $addons = DdAddons::find()->where(['identifie'=>$this->moduleName])->exists();
                if ($addons){
                    $class = \Yii::$app->getModule($this->moduleName);
                    // 检查$addon是否是一个对象并且实现了特定的接口，或者简单地检查方法是否存在
                    if (method_exists($class, 'wechatMsgHandle')) {
                        $MsgType = $this->message['MsgType'];
                        $class->wechatMsgHandle($customer_service, $FromUserName,$MsgType, $this->message);
                    }
                }
            }else{
                $message = $this->message;
                // 事件消息处理
                //                if ($message['Event'] == 'VIEW') {
                ////                    return new Text($message['EventKey']);
                //                } else
                if ($message['Event'] == 'SCAN' && $message['EventKey'] === 'login') {
                    OpenWechatAuthService::autoScanLogin($message['FromUserName'], $message['Ticket']);
                } elseif ($message['Event'] == 'SCAN' && $message['EventKey'] === 'userbind') {
                    OpenWechatAuthService::autoUserBind($message['FromUserName'], $message['Ticket']);
                } elseif ($message['Event'] == 'subscribe' && $message['EventKey'] === 'qrscene_login') {
                    /**
                     * 第一次扫描登录，关注+登录
                     */
                    OpenWechatAuthService::autoScanLogin($message['FromUserName'], $message['Ticket']);
                } elseif ($message['Event'] == 'subscribe' && $message['EventKey'] === 'qrscene_userbind') {
                    /**
                     * 第一次扫描登录，关注+绑定
                     */
                    OpenWechatAuthService::autoUserBind($message['FromUserName'], $message['Ticket']);
                }
            }
            $this->server->serve()->send();
        }catch (\Exception $e){
            loggingHelper::writeLog('openWeixin','EventHandler','全局调度微信消息-err',[
                'error' => $e->getMessage()
            ]);

        }

    }
}
