<?php

namespace common\modules\openWeixin\services;


// 基础模板消息类
use common\services\BaseService;
use Yii;

class WeChatTemplateMessage extends BaseService
{
    protected string $templateId; // 模板ID
    protected array $data; // 模板数据

    public function __construct($templateId)
    {
        $this->templateId = $templateId;
        $this->data = [];
        parent::__construct();
    }

    public function addData($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * 公众号发送模板消息
     * @param $toUser
     * @param $template_id
     * @param $url
     * @param $appid
     * @param $pagePath
     * @return void
     */
    public function wechatSend($toUser, $template_id, $url, $appid, $pagePath): void
    {
        $wechat = Yii::$app->OpenApp->getApp(1);

        // 在这里实现发送模板消息的逻辑
        // 可以使用微信提供的API来发送消息
        // $this->templateId 和 $this->data 将被用于构造发送请求
        $wechat->template_message->send([
            'touser' => $toUser,
            'template_id' => $template_id,
            'url' => $url,
            'miniprogram' => [
                'appid' => $appid,
                'pagepath' => $pagePath,
            ],
            'data' => $this->getData(),
        ]);
    }

    /**
     * 消息程序发送模板消息
     * @param $toUser
     * @param $template_id
     * @param $url
     * @param $appid
     * @param $pagePath
     * @return void
     */
    public function wxappSend($toUser, $template_id, $url, $appid, $pagePath): void
    {
        $wechat = Yii::$app->OpenApp->getMiniProgram(1);

        // 在这里实现发送模板消息的逻辑
        // 可以使用微信提供的API来发送消息
        // $this->templateId 和 $this->data 将被用于构造发送请求
        $wechat->template_message->send([
            'touser' => $toUser,
            'template_id' => $template_id,
            'url' => $url,
            'miniprogram' => [
                'appid' => $appid,
                'pagepath' => $pagePath,
            ],
            'data' => $this->getData(),
        ]);
    }
}