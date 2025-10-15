<?php

namespace common\modules\officialaccount\services;


// 基础模板消息类
use common\modules\officialaccount\models\DdWechatFans;
use common\modules\officialaccount\models\OfficialaccountMsgTemplate;
use common\modules\officialaccount\models\OfficialaccountMsgTemplateList;
use common\services\BaseService;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

class WeChatTemplateMessage extends BaseService
{
    static function parseData($str): bool|string
    {
        $patterns   = explode(PHP_EOL, $str);
        $parsedData = [];
        foreach ($patterns as $pattern) {
            if (strpos($pattern, ':')) {
                [$dataName, $fields] = explode(':', $pattern);
                $field = substr($fields, 2, -2);
            } else {
                $dataName = '';
                $field    = substr($pattern, 2, -2);
            }

            if ($field) {
                $parsedData[] = [
                    'name'  => $dataName,
                    'field' => str_replace('.DATA', '', $field),
                    'value' => '',
                    'color' => ''
                ];
            }


        }
        return json_encode($parsedData, JSON_UNESCAPED_UNICODE);

    }

    static function parseData1($str): bool|string
    {
        $pattern = "/\{\{(.*?)\.DATA}}/";
        preg_match_all($pattern, $str, $matches);

        $dataNames  = $matches[ 1 ];
        $parsedData = [];

        foreach ($dataNames as $dataName) {
            $parsedData[] = [
                'key'   => $dataName,
                'value' => '',
                'color' => ''
            ];
        }

        return json_encode($parsedData);
    }

    static function wechatSendAll($id, $openid = ''): array
    {
        if ($openid) {
            $Res = self::wechatSend($id, $openid);
            return $Res;
        } else {
            $openids = DdWechatFans::find()->select('openid')->column();
            $Res     = [];
            foreach ($openids as $toUser) {
                $Res[] = self::wechatSend($id, $toUser);
            }
            return $Res;
        }

    }

    /**
     * 公众号发送模板消息
     *
     * @param $id
     * @param $toUser
     *
     * @return mixed
     * @throws InvalidConfigException
     */
    static function wechatSend($id, $toUser): mixed
    {
        $tem         = OfficialaccountMsgTemplateList::find()->where(['id' => $id])->cache()->asArray()->one();
        $template_id = $tem[ 'template_id' ];
        $url         = $tem[ 'url' ];
        $appid       = $tem[ 'miniprogram_appid' ];
        $pagePath    = $tem[ 'miniprogram_pagepath' ];
        $list        = Json::decode($tem[ 'data' ]);
        $data        = [];
        foreach ($list as $item) {
            if ($item[ 'field' ]) {
                $data[ $item[ 'field' ] ] = [
                    "value" => $item[ 'value' ],
                    "color" => $item[ 'color' ]
                ];
            }
        }
        $res = self::send($toUser, $template_id, $data, $url, $appid, $pagePath);

        return $res;
//        $wechat = OfficialaccountService::getWechatApp(1);
//        // 在这里实现发送模板消息的逻辑
//        // 可以使用微信提供的API来发送消息
//        // $this->templateId 和 $this->data 将被用于构造发送请求
//        return $wechat->template_message->send([
//                'touser'      => $toUser,
//                'template_id' => $template_id,
//                'url'         => $url,
//                'miniprogram' => [
//                    'appid'    => $appid,
//                    'pagepath' => $pagePath,
//                ],
//                'data'        => $data,
//            ]
//        );
    }

    /**
     * @param $sign
     * @param $params
     * @param $toUser
     * @param $locationUrl
     * @param $miniprogramAppid
     * @param $miniprogramPagepath
     *
     * @return mixed
     * @throws \Exception
     */
    static function wxTemplateSend($sign, $params, $toUser, $locationUrl = '', $miniprogramAppid = '', $miniprogramPagepath = ''): mixed
    {
        $tem         = OfficialaccountMsgTemplate::find()->where(['sign' => $sign])->cache()->asArray()->one();
        $template_id = $tem[ 'template_id' ] ?? '';
        $url         = $locationUrl ?? env('DOMAIN_NAME');
        $appid       = $miniprogramAppid;
        $pagePath    = $miniprogramPagepath;
        $list        = Json::decode($tem[ 'content' ]);
        if (count($list) != count($params)) {
            throw new \Exception('消息模板格式数量不对');
        }
        $data = [];
        foreach ($list as $k => $v) {
            if (!isset($params[ $k ][ 'value' ])) {
                throw new \Exception('消息内容缺失,请检验消息内容!');
            }
            $data[ $v[ 'field' ] ] = [
                "value" => $params[ $k ][ 'value' ],
                "color" => $params[ $k ][ 'color' ] ?? ''
            ];
        }
        if (is_array($toUser)) {
            foreach ($toUser as $user_id) {
                $res = self::send($user_id, $template_id, $data, $url, $appid, $pagePath);
            }
        } else {
            $res = self::send($toUser, $template_id, $data, $url, $appid, $pagePath);
        }

        return $res;
    }

    static function send($toUser, $templateId, $data, $url = '', $appid = '', $pagePath = '')
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        // 在这里实现发送模板消息的逻辑
        // 可以使用微信提供的API来发送消息
        // $this->templateId 和 $this->data 将被用于构造发送请求
        return $wechat->template_message->send([
                'touser'      => $toUser,
                'template_id' => $templateId,
                'url'         => $url,
                'miniprogram' => [
                    'appid'    => $appid,
                    'pagepath' => $pagePath,
                ],
                'data'        => $data,
            ]
        );
    }
}