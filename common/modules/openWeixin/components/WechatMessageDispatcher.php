<?php

namespace common\modules\openWeixin\components;

use common\helpers\loggingHelper;
use common\modules\openWeixin\Handler\EventHandler;
use common\modules\openWeixin\Handler\FileHandler;
use common\modules\openWeixin\Handler\ImageHandler;
use common\modules\openWeixin\Handler\LinkHandler;
use common\modules\openWeixin\Handler\LocationHandler;
use common\modules\openWeixin\Handler\publicHandler;
use common\modules\openWeixin\Handler\TextHandler;
use common\modules\openWeixin\Handler\VideoHandler;
use common\modules\openWeixin\Handler\VoiceHandler;
use common\modules\openWeixin\services\OpenWechatAuthService;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OpenPlatform\Server\Guard;
use Yii;
use yii\base\Component;

class WechatMessageDispatcher extends Component
{
    private array $handlers = [];

    /**
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->handle();
        parent::init();
    }

    /**
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws InvalidConfigException
     */
    public function handle(): Text|string|null
    {
        $server = Yii::$app->wechat->openPlatform->server;
        $message = $server->getMessage();
        loggingHelper::writeLog('openWeixin','handle','消息统一调度',[
            'message' => $message,
            'server' => $server,
        ]);
        $openPlatform = Yii::$app->wechat->openPlatform;
        if (key_exists('MsgType', $message)) {
            switch ($message['MsgType']) {
                case 'text':
                    // 文本消息处理
                    if ($message['Content'] == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
                        return 'TESTCOMPONENT_MSG_TYPE_TEXT_callback';
                    }

                    // 若为事件地理位置选择，则回复相应消息
                    if (str_contains($message['Content'], 'LOCATION_INFO')) {
                        return 'TESTCOMPONENT_MSG_TYPE_LOCATION_callback';
                    }

                    // 若为事件普通地理位置，回复相应消息
                    if (str_contains($message['Content'], 'LOCATION')) {
                        return 'TESTCOMPONENT_MSG_TYPE_LOCATION_callback';
                    }
                    loggingHelper::writeLog('openWeixin','handle','准备调度TextHandler',[
                        'message' => $message,
                        'openPlatform' => $openPlatform,
                        'server' => $server,
                    ]);
                    $this->registerHandler(new TextHandler($server,$openPlatform));
                    return null;
                    break;
                case 'image':

                    $this->registerHandler(new ImageHandler($server,$openPlatform));
                    return '收到图片消息';
                    break;
                case 'voice':
                    $this->registerHandler(new VoiceHandler($server,$openPlatform));
                    return '收到语音消息';
                    break;
                case 'video':
                    $this->registerHandler(new VideoHandler($server,$openPlatform));
                    return '收到视频消息';
                    break;
                case 'location':
                    $this->registerHandler(new LocationHandler($server,$openPlatform));
                    return '收到坐标消息';
                    break;
                case 'link':
                    $this->registerHandler(new LinkHandler($server,$openPlatform));

                    return '收到链接消息';
                    break;
                case 'file':
                    $this->registerHandler(new FileHandler($server,$openPlatform));

                    return '收到文件消息';
                case 'event':
                    $this->registerHandler(new EventHandler($server,$openPlatform));
                    return '收到事件消息';
                default:
                    return '收到其它消息';
                    break;
            }
        }else {
            $this->registerHandler(new publicHandler($server,$openPlatform));
        }
        return 'success';
    }

    /**
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws InvalidConfigException
     */
    public function dispatchMessage(Guard $server, $channel = 'addons'): void
    {
        loggingHelper::writeLog('openWeixin', 'dispatchMessage', '消息分发01', [
            'message' => $server->getMessage(),
            'channel' => $channel,
        ]);
        $handler = $this->getHandler($channel);
        loggingHelper::writeLog('openWeixin', 'dispatchMessage', '消息分发02', [
            'message' => $server->getMessage(),
        ]);
        if ($handler !== null) {
            $handler->handle();
        } else {
            // 如果找不到对应的消息处理器，则可以记录日志或默认处理
            echo "No handler found for message type: " . $channel.PHP_EOL;
        }
    }

    private function registerHandler(EventHandlerInterface $handler, string $channel = 'addons')
    {
        $this->handlers[$channel] = $handler;
    }

    private function getHandler(string $channel): ?EventHandlerInterface
    {
        return $this->handlers[$channel] ?? null;
    }
}
