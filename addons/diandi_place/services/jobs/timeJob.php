<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-05-21 15:25:05
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 08:21:22
 */
namespace addons\diandi_place\services\jobs;
use addons\diandi_place\services\MessageService;
use common\components\Job;
use common\helpers\loggingHelper;
use common\helpers\UrlHelper;
use GuzzleHttp\Client;
use Yii;
class timeJob extends Job
{
    public $member_id;
    public $order_id;
    public function execute($queue)
    {
        parent::init();
        loggingHelper::writeLog('diandi_place', 'timeJob', '消息队列', [
            'member_id' => $this->member_id,
            'bloc_id' => $this->bloc_id,
            'store_id' => $this->store_id,
        ]);
        $member_id = $this->member_id;
        $order_id = $this->order_id;
        $url = "https://hotelapi.ddicms.cn";
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $url,
            // You can set any number of default request options.
            'timeout' => 10,
            // 'verify' => false
        ]);
        $res = $client->request('POST', '/api/diandi_place/msg/timemsg', [
            'json' => [
                'member_id' => $member_id,
                'order_id' => $order_id,
            ],
            'headers' => [
                // "Access-Token" => '104231665f5749ecd79122edbcb89b55f7c55e40',
                "Content-type" => 'application/json',
                'bloc-id' => $this->bloc_id,
                'store-id' => $this->store_id,
            ],
        ]);
        $body = $res->getBody();
        $remainingBytes = $body->getContents();
        // $Res =  MessageService::orderTimeMsg($member_id);
        loggingHelper::writeLog('diandi_place', 'timeJob', '消息队列结果', [
            'Res' => $remainingBytes,
            'url' => $url
        ]);
    }
}
