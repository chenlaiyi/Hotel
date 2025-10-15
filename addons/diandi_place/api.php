<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-22 20:49:54
 */
namespace addons\diandi_place;
use addons\diandi_place\events\LoginEvent;
use addons\diandi_place\models\place\PlaceLandlord;
use common\components\addons\AddonsModule;
use common\helpers\loggingHelper;
use Yii;
use yii\base\Event;
use yii\web\HttpException;
use EasyWeChat\Kernel\Messages\Text;
/**
 * diandi_dingo module definition class.
 */
class api extends AddonsModule
{
    /**
     * {}
     */
    public $controllerNamespace = "addons\diandi_place\api";
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        Yii::$app->i18n->translations['place*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@addons/diandi_place/messages'
        ];
        loggingHelper::writeLog('diandi_place','init','微信消息处理');
        // 绑定事件监听器
        parent::init();
    }
    /**
     * 微信消息特殊处理
     * @param $customer_service
     * @param $FromUserName
     * @param $MsgType
     * @param $message
     * @return void
     */
    function wechatMsgHandle($customer_service, $FromUserName,$MsgType, $message): void
    {
        loggingHelper::writeLog('diandi_place','wechatMsgHandle','微信消息处理',[
            'message'=>$message,
            'customer_service'=>$customer_service,
            'FromUserName'=>$FromUserName,
            'MsgType'=>$MsgType,
        ]);
        switch ($MsgType) {
            case 'text':
                $message = new Text('欢迎使用房态管理系统text');
                break;
            case 'image':
                $message = new Text('欢迎使用房态管理系统image');
                break;
            case 'voice':
                $message = new Text('欢迎使用房态管理系统voice');
                break;
            case 'video':
                $message = new Text('欢迎使用房态管理系统video');
                break;
            case 'location':
                $message = new Text('欢迎使用房态管理系统location');
                break;
            case 'link':
                $message = new Text('欢迎使用房态管理系统link');
                break;
            case 'file':
                $message = new Text('欢迎使用房态管理系统file');
            case 'event':
                $message = new Text('欢迎使用房态管理系统event');
                break;
            // ... 其它消息
            default:
                $message = new Text('欢迎使用房态管理系统');
                break;
        }
        $customer_service->message($message)->to($FromUserName)->send();
    }
}
