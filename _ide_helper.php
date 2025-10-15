<?php


use common\components\ExtendedRequest;
use yii\web\Application;

class Yii
{
    /**
     * @var MyApplication
     */
    public static MyApplication $app;
}

/**
 * 组件代码自动提示助手
 * @property ExtendedRequest $request
 * @property common\rpc\services\BaseService $service
 * @property yii2mod\settings\components\Settings $settings
 * @method scheme(int $order_id)
 */
class MyApplication extends Application
{
    /**
     * @var mixed|object|null
     */
    public mixed $template_message;

    /**
     * @var \common\components\wechat\Wechat
     */
    public mixed $wechat;

    /**
     * @var yii\queue\Queue
     *
     */
    public mixed $queue;

    /**
     * @var common\modules\openWeixin\components\WxappClient
     */
    public mixed $WxappClient;

    /**
     * @var \common\modules\officialaccount\components\wechat
     */
    public mixed $officialaccount;
}

