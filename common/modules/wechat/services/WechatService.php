<?php

namespace common\modules\wechat\services;

use common\helpers\ImageHelper;
use common\models\User;
use common\modules\openWeixin\models\enums\OpenWeixinAuthCode;
use common\modules\wechat\models\WxappWechatConf;
use common\services\BaseService;
use diandi\addons\models\form\Wxapp;
use diandi\addons\services\addonsService;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

class WechatService extends BaseService
{

    static function getBlocId(): int
    {
        /**
         * 默认是系统配置
         */
        $bloc_id = env('WECHATOPENPLATFORMCONFIG_BLOC_ID');
        if (Yii::$app->request->input('auth_bloc_id')) {
            $bloc_id = Yii::$app->request->input('auth_bloc_id');
        }
        return $bloc_id;
    }

    /**
     * 获取操作实例，0 公众号 1第三方平台
     * @param int $type
     * @return mixed
     * @throws InvalidConfigException
     */
    static function getWechatApp(int $type = 0): mixed
    {
        if ($type === 0){
            $miniProgram = Yii::$app->wechat->miniProgram;
        }else{
            $miniProgram = Yii::$app->getModule('openWeixin')->get('OpenApp')->getMiniProgram();
        }
        return $miniProgram;
    }

    /**
     * 获取当前公众号配置信息
     * @return array
     */
    static function getWechatConf(): array
    {
        $bloc_id = self::getBlocId();
        $model = new Wxapp();

        $conf = WxappWechatConf::find()->where(['bloc_id' => $bloc_id])->with(['open'])->asArray()->one();

        $conf['original'] = $model->decodeConf($conf['original']);
        $conf['AppId'] = $model->decodeConf($conf['AppId']);
        $conf['AppSecret'] = $model->decodeConf($conf['AppSecret']);

        $conf['headimg'] = ImageHelper::tomedia($conf['headimg']);
        if ($conf['open']) {
            $conf['open']['authorizer_appid'] = addonsService::hideStr($conf['open']['authorizer_appid'], 5, 10);
            $conf['open']['authorizer_access_token'] = addonsService::hideStr($conf['open']['authorizer_access_token'], 10, 40);
            $conf['open']['authorizer_refresh_token'] = addonsService::hideStr($conf['open']['authorizer_refresh_token'], 10, 40);
            $func_info = Json::decode($conf['open']['func_info']);

            $func_infos = [];
            foreach ($func_info as $item) {
                $func_infos[] =OpenWeixinAuthCode::getLabel($item['funcscope_category']['id']);
            }
            $conf['open']['func_info'] = $func_infos;
        }



        return $conf;
    }
}