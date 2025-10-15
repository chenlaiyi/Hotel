<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-08 03:04:55
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-14 17:16:29
 */

namespace common\modules\wechat;

use common\helpers\FileHelper;
use common\helpers\loggingHelper;
use common\helpers\StringHelper;
use common\models\DdCorePaylog;
use common\models\User;
use diandi\addons\models\Bloc;
use diandi\addons\models\BlocConfWechatpay;
use diandi\addons\models\form\Wechatpay;
use Yii;

/**
 * 小程序接口
 */
class module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\wechat\controllers\api';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
        /* 加载语言包 */
        if (!isset(Yii::$app->i18n->translations['wechat'])) {
            Yii::$app->i18n->translations['wechat'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@api/modules/wechat/messages',
            ];
        }

        // 微信回调跟进订单初始化

        $input = file_get_contents('php://input');
        loggingHelper::writeLog('wechat','moduleInit', '入口配置回来的值' . $input);
        loggingHelper::writeLog('wechat','moduleInit', '入口配置回来的值0-5' . substr($input, 0, 5));
        if (str_starts_with($input, '<xml>')) {
            loggingHelper::writeLog('wechat','moduleInit', '准备处理');
            $xmlData = StringHelper::getXml($input);
            loggingHelper::writeLog('wechat','moduleInit', 'xml解析后' . $xmlData['trade_type'] . '/' . json_encode($xmlData));
            if ($xmlData['trade_type'] == 'JSAPI') {
                $out_trade_no = $xmlData['out_trade_no'];
                loggingHelper::writeLog('wechat','moduleInit', '入口配置回来的订单编号：' . $out_trade_no);
                $DdCorePayLog = new DdCorePaylog();
                $orderInfo = $DdCorePayLog->find()->where([
                    'uniontid' => trim($out_trade_no),
                ])->select(['bloc_id', 'store_id', 'module'])->asArray()->one();
                loggingHelper::writeLog('wechat','moduleInit', '入口配置回来的xml值订单日志sql' . $DdCorePayLog->find()->where([
                        'uniontid' => trim($out_trade_no),
                    ])->select(['bloc_id', 'store_id', 'module'])->createCommand()->getRawSql());
                loggingHelper::writeLog('wechat','moduleInit', '入口配置回来的xml值订单日志' . json_encode($orderInfo));
                $pay_bloc_id = Bloc::find()->where(['bloc_id' => $orderInfo['bloc_id']])->select('group_bloc_id')->scalar();
                $conf = new BlocConfWechatpay();
                $payExit = $conf::find()->where(['bloc_id' => $orderInfo['bloc_id']])->asArray()->one();
                /**
                 * 独立配置了支付，就独立处理，否则就找父级配置
                 */
                if (!$payExit){
                    $pay_bloc_id = (int)($pay_bloc_id?:$orderInfo['bloc_id']);
                }else{
                    $pay_bloc_id = $orderInfo['bloc_id'];
                }
                loggingHelper::writeLog('wechat','moduleInit', '支付对应的参数公司' . json_encode([
                    'pay_bloc_id'=>$pay_bloc_id
                ]));
                Yii::$app->service->commonGlobalsService->initId($pay_bloc_id, (int)$orderInfo['store_id']??0);
                Yii::$app->service->commonGlobalsService->getConf($pay_bloc_id);
                Yii::$app->params['bloc_id'] = $pay_bloc_id;
                Yii::$app->params['store_id'] = $orderInfo['store_id'];
            }

        }

        $appId = Yii::$app->id;
        switch ($appId) {
            case 'app-admin':
                $this->controllerNamespace = 'common\modules\wechat\controllers\admin';
                $this->on(self::EVENT_BEFORE_ACTION, function ($event) {
                    // 监听用户登录事件
                    Yii::$app->user->on(Yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
                        $userId = Yii::$app->user->getId();
                        // 使用 $userId 进行初始化处理数据...
                        loggingHelper::writeLog('officialaccount','moduleInit','用户初始事件',[
                            'userId' => $userId
                        ]);
                        $this->initGlobalConf($userId);

                    });
                });
                $config = $this->initConf();
                // 将新的配置设置到应用程序
                // 很多都是写 Yii::configure($this, $config)，但是并不适用子模块，必须写 Yii::$App
                Yii::configure(Yii::$app, $config);
                break;
            case 'app-api':
                $this->controllerNamespace = 'common\modules\wechat\controllers\api';
                $config = $this->initConf();
                // 将新的配置设置到应用程序
                // 很多都是写 Yii::configure($this, $config)，但是并不适用子模块，必须写 Yii::$App
                Yii::configure(Yii::$app, $config);
                break;
        }
    }


    public function initGlobalConf($user_id): void
    {
        $bloc_id = User::find()->where(['id'=>$user_id])->select('bloc_id')->scalar();
        $params = Yii::$app->params;
        $conf = $params['conf'];
        loggingHelper::writeLog('AccessControl','initGlobalConf','权限校验通过处理初始配置数据',[
            'user_id'=> $user_id,
            'bloc_id'=> $bloc_id,
            'conf'=> $conf
        ]);
        Yii::$app->service->commonGlobalsService->getConf($bloc_id);
        loggingHelper::writeLog('AccessControl','initGlobalConf','权限校验通过处理初始配置处理后数据',[
            'user_id'=> $user_id,
            'bloc_id'=> (int)$bloc_id,
            'conf'=> $conf
        ]);
    }

    private function initConf(): array
    {
        $config = require __DIR__ . '/config/config.php';
        // 获取应用程序的组件
        $components = Yii::$app->getComponents();

        // 遍历子模块独立配置的组件部分，并继承应用程序的组件配置
        foreach ($config['components'] as $k => $component) {
            if (isset($component['class']) && !isset($components[$k])) {
                continue;
            }
            $config['components'][$k] = array_merge($components[$k], $component);
        }


        $params = Yii::$app->params;
        $conf = $params['conf'];

        $Wechatpay = (array) $conf['wechatpay'];
        $Wxapp = $conf['wxapp'];

        $apiclient_certUrl = $apiclient_keyUrl = '';
        if (key_exists('apiclient_cert',$Wechatpay)){
            $apiclient_certUrl = $Wechatpay['apiclient_cert']?:'';
        }

        if (key_exists('apiclient_key',$Wechatpay)){
            $apiclient_keyUrl = $Wechatpay['apiclient_key']?:'';
        }

        $apiclient_cert = Yii::getAlias('@attachment/' . $apiclient_certUrl);
        $apiclient_key = Yii::getAlias('@attachment/' . $apiclient_keyUrl);


        // 支付参数设置
        $config['params']['wechatPaymentConfig'] = [
            'app_id' => $Wxapp['AppId'] ?? '',
            'mch_id' => $Wechatpay['mch_id'] ?? '',
            'key' => $Wechatpay['key'] ?? '',  // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path' => $apiclient_cert, // XXX: 绝对路径！！！！
            'key_path' => $apiclient_key, // XXX: 绝对路径！！！！
            'notify_url' => Yii::$app->request->hostInfo . '/api/wechat/basics/notify',
        ];

        loggingHelper::writeLog('wechat','moduleInit', '入口配置' . json_encode($config['params']['wechatPaymentConfig']));
        loggingHelper::writeLog('wechat','moduleInit', '总配置' . json_encode($conf));
        loggingHelper::writeLog('wechat','moduleInit', '小程序配置' . json_encode($Wxapp));

        // 小程序参数设置
        $config['params']['wechatMiniProgramConfig'] = [
            'app_id' => $Wxapp['AppId'] ?? '',
            'secret' => $Wxapp['AppSecret'] ?? '',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => Yii::getAlias('@runtime/miniprogram'),
            ],
            //必须添加部分
            'guzzle' => [ // 配置
                'verify' => false,
                'timeout' => 4.0,
            ],
        ];

        $params = Yii::$app->params;

        foreach ($params as $key => $value) {
            if (!isset($config['params'][$key])) {
                $config['params'][$key] = $value;
            }
        }
        return $config;
    }
}
