<?php

namespace common\modules\openWeixin\Handler;


use common\helpers\loggingHelper;
use diandi\addons\models\DdAddons;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\OpenPlatform\Server\Guard;
use Yii;


class ImageHandler implements EventHandlerInterface
{
    protected $server;

    protected $message;

    protected $open_platform;
    protected string $moduleName;
    /**
     * @var mixed|string
     */
    protected mixed $eventKey;

    public function __construct(Guard $server,$openPlatform)
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

    public function handle($payload = null)
    {
        try {

            $FromUserName = $this->message['FromUserName']??'';
            loggingHelper::writeLog('TextHandler','handle','全局调度微信消息1',[
                'FromUserName' => $FromUserName,
                'message' => $this->message,
                'addons' => $this->moduleName
            ]);

            $customer_service = $this->open_platform->customer_service;
//            $customer_service->message(new Text('这里输出测试消息'))->to($FromUserName)->send();

            /**
             * 为空，全局消息，不为空，模块消息
             */
            if ($this->moduleName){
                $addons = DdAddons::find()->where(['identifie'=>$this->moduleName])->exists();
                if ($addons){
                    $class = \Yii::$app->getModule($this->moduleName);
                    // 检查$addon是否是一个对象并且实现了特定的接口，或者简单地检查方法是否存在
                    if (method_exists($class, 'wechatMsgHandle')) {
                        // 调用方法
                        $MsgType = $this->message['MsgType'];

                        $class->wechatMsgHandle($customer_service, $FromUserName,$MsgType, $this->message);
                    }
                }

            }
            $this->server->serve()->send();
        }catch (\Exception $e){
            loggingHelper::writeLog('TextHandler','handle','全局调度微信消息-err',[
                'error' => $e->getMessage()
            ]);

        }

    }
}
