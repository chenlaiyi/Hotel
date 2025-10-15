<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-02-09 00:46:02
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-09 01:03:39
 */


namespace common\behaviors;

use common\services\common\WebsiteGroup;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;

/**
 * 接口数据返回行为
 */
class BeforeSend extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            'beforeSend' => 'beforeSend',
        ];
    }

    /**
     * 格式化返回
     *
     * @param $event
     * @throws InvalidConfigException
     */
    public function beforeSend($event)
    {
        if (YII_DEBUG && isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id === "debug") {
            return;
        }

        $response = $event->sender;

        $origin = Yii::$app->request->headers['origin'];

        /**
         * 放行授权域名
         */
        $isAllowed  = WebsiteGroup::checkHeaderOrigin($origin);
        if ($isAllowed){
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        // 加入ip黑名单
        $ip = Yii::$app->request->userIP;
        if (!WebsiteGroup::checkIp($ip)){
            $response->data = [
                'code' => 403,
                'message' => 'ip黑名单',
                'data' => [],
            ];
        }

        $response->format = yii\web\Response::FORMAT_JSON;
        $response->statusCode = 200;
        $response->data = $this->formatSnowflakeIdToString($response->data);
    }

    /**
     * 处理数组中的雪花ID，将其转为字符串
     * @param array $data 需要处理的数组
     * @return array 处理后的数组
     */
    public function formatSnowflakeIdToString(array $data): array
    {
        foreach ($data as $key => $value) {
            // 1. 若值是数组，递归处理子数组
            if (is_array($value)) {
                $data[$key] = $this->formatSnowflakeIdToString($value);
            }
            // 2. 若值是18-20位数字（雪花ID典型长度），转为字符串
            elseif (is_int($value) && strlen((string)$value) >= 18 && strlen((string)$value) <= 20) {
                $data[$key] = (string)$value;
            }
        }
        return $data;
    }
}
