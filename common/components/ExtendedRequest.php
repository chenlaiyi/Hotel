<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 11:22:36
 */


namespace common\components;


use common\models\WebsiteStationGroup;
use Yii;
use yii\db\Query;
use yii\web\Request;

/**
 * @inheritdoc
 *
 * @property yii\web\Request Request
 */
class ExtendedRequest extends Request
{
    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function input($key = null, $default = null): mixed
    {
        $getParams  = $this->get();
        $postParams = $this->post();

        //头部bloc_id 与store_id 参数优先级低于自身请求体里面的参数
        $headerParams = [
            'bloc_id'      => Yii::$app->request->headers->get('bloc-id'),
            'store_id'     => Yii::$app->request->headers->get('store-id'),
            'access_token' => Yii::$app->request->headers->get('access-token'),
        ];
        if (empty(Yii::$app->request->headers->get('bloc-id'))) {
            $domain                   = Yii::$app->request->getHostName();
            $site_info                = (new Query())->from(WebsiteStationGroup::tableName())->where(['domain_url' => $domain])->select(['bloc_id', 'store_id'])->one();
            $headerParams['bloc_id']  = $site_info['bloc_id'] ?? 0;
            $headerParams['store_id'] = $site_info['store_id'] ?? 0;
        }

        $data = array_merge($headerParams, $getParams, $postParams);

        // 如果指定了 $key，则返回对应的值，否则返回整个合集
        if ($key !== null) {
            return $data[$key] ?? $default;
        }

        return $data;
    }
}
