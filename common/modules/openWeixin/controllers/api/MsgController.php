<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-11-14 22:17:14
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-07-11 18:52:57
 */

namespace common\modules\openWeixin\controllers\api;

use api\controllers\AController;
use common\helpers\loggingHelper;
use common\modules\officialaccount\services\FansService;
use common\modules\officialaccount\services\MessageService;
use common\modules\openWeixin\components\WechatMessageDispatcher;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OpenPlatform\Server\Guard;
use Yii;
use yii\web\NotFoundHttpException;

class MsgController extends AController
{
    protected array $authOptional = ['event', 'open'];

    public $modelClass = 'api\modules\officialaccount\models\DdWechatFans';

    public $defaultAction = 'event';

    public function actionOpen(): void
    {
        $request = Yii::$app->request;
        $app = Yii::$app->wechat->getApp();
            loggingHelper::writeLog('openWeixin', 'actionOpen', '事件监听处理', [
            'getMethod' => $request->getMethod(),
        ]);

        $configPath = Yii::getAlias('@common/config/wechat.php');
        $config = [];
        if (file_exists($configPath)) {
            $config = require_once $configPath;
        }
        $data = [
            'app_id' => $config['app_id'],
            'secret' => $config['secret'],
            'token' => $config['token'],
            'aes_key' => $config['aes_key'],
        ];
        loggingHelper::writeLog('openWeixin', 'actionOpen', '配置信息', [
            'data' => $data,
            'config' => $config
        ]);
        $openPlatform = Factory::openPlatform($data);
        $server = $openPlatform->server;

        loggingHelper::writeLog('openWeixin', 'actionOpen', '服务数据', [
            'server' => $server->serve(),
            'data' => $server
        ]);
        $response = $app->server->serve();
        $response->send();
    }

    /**
     * 微信请求关闭CSRF验证
     *
     */
    // public $enableCsrfValidation = false;

    /**
     * 只做微信公众号激活，不做其他消息处理.
     * https://dev.hopesfire.com/api/officialaccount/msg/event?store_id=81&bloc_id=32.
     * @param $appid
     * @return void
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function actionEvent($appid)
    {
        $request = Yii::$app->request;
        $openPlatform = Yii::$app->wechat->openPlatform;
        $wechatOpenPlatformConfig = Yii::$app->params['wechatOpenPlatformConfig'];
        loggingHelper::writeLog('openWeixin', 'actionEvent', '事件监听处理', [
            'appid' => $appid,
            'wechatOpenPlatformConfig' => $wechatOpenPlatformConfig,
            'data' => Yii::$app->request->input(),
            'getMethod' => $request->getMethod(),
            'body' => Yii::$app->request->getRawBody()
        ]);

        $server = $openPlatform->server;

        $message = $openPlatform->server->getMessage();
        loggingHelper::writeLog('openWeixin', 'actionEvent', '解密内容', [
            'message' => $message
        ]);
        try {
            /**
             * 注册全局消息处理
             */
            $serverPlatForm = new Guard($openPlatform);
            $dispatcher = new WechatMessageDispatcher();
            $dispatcher->dispatchMessage($serverPlatForm);
            // 将响应输出
            $server->serve()->send();
            exit();
        } catch (\Exception $e) {
            loggingHelper::writeLog('openWeixin', 'actionEvent', '事件监听处理-err', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ]);
        }
    }

    /**
     * 事件处理.
     *
     * @param $message
     *
     * @return bool|mixed
     *
     * @throws NotFoundHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function event($message): mixed
    {
        Yii::$app->params['msgHistory']['event'] = $message['Event'];
        $FansService = new FansService();
        $MessageService = new MessageService();
        loggingHelper::writeLog('openWeixin', 'subscribe', '事件开始', [
            'message' => $message,
        ]);
        switch ($message['Event']) {
            // 关注事件
            case 'subscribe':
                loggingHelper::writeLog('openWeixin', 'subscribe', '关注事件', [
                    'msg' => $message,
                ]);
                $FansService->follow($message['FromUserName']);

                // 判断是否是二维码关注
                // if ($qrResult = Yii::$App->wechatService->qrcodeStat->scan($message)) {
                //     $message['Content'] = $qrResult;
                //     $MessageService->setMessage($message);

                //     return $MessageService->text();
                // }

                // return $MessageService->follow();
                break;
            // 取消关注事件
            case 'unsubscribe':
                loggingHelper::writeLog('openWeixin', 'subscribe', '取消关注事件', [
                    'FromUserName' => $message['FromUserName'],
                ]);
                $FansService->unFollow($message['FromUserName']);

                return false;
                break;
            // 二维码扫描事件
            case 'SCAN':
                // if ($qrResult = Yii::$App->wechatService->qrcodeStat->scan($message)) {
                //     $message['Content'] = $qrResult;
                //     $MessageService->setMessage($message);

                //     return $MessageService->text();
                // }
                break;
            // 上报地理位置事件
            case 'LOCATION':

                //TODO 暂时不处理

                break;
            // 自定义菜单(点击)事件
            case 'CLICK':
                $message['Content'] = $message['EventKey'];
                $MessageService->setMessage($message);

                return $MessageService->text();
                break;
        }

        return false;
    }


    /**
     * 判断字符串是否以指定前缀开始
     *
     * @param string $str 需要检查的字符串
     * @param string $prefix 前缀字符串
     * @return bool 如果字符串以指定前缀开始则返回true，否则返回false
     */
    public function startsWith($str, $prefix)
    {
        // substr函数用于提取字符串的一部分，第二个参数是起始位置，第三个参数是长度
        // 当只提供起始位置时，默认提取到字符串末尾
        // strcmp函数用于比较两个字符串，如果相等返回0，如果第一个字符串小于第二个字符串返回负数，如果大于返回正数
        // 通过strcmp(substr($str, 0, strlen($prefix)), $prefix) === 0来判断是否相等
        return strcmp(substr($str, 0, strlen($prefix)), $prefix) === 0;
    }
}
