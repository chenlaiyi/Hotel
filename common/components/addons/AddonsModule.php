<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-26 09:30:21
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-19 09:09:00
 */

namespace common\components\addons;

use admin\models\addons\models\Bloc;
use api\models\DdApiAccessToken;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;
use common\helpers\loggingHelper;
use common\helpers\StringHelper;
use common\models\User;
use diandi\addons\models\searchs\DdAddons;
use diandi\admin\components\MenuHelper;
use Yii;
use yii\base\Event;
use yii\base\Module;
use yii\web\HttpException;
use function Qcloud\Cos\startWith;

/**
 *
 * @property null|int $memberId
 * @property null|int $userId
 * @property-read array $menus
 */
class AddonsModule extends Module
{
    public int|null $_userId;

    public int|null $_memberId;

    /**
     * @throws HttpException
     */
    public function init(): void
    {
        $module = $this->id;

        $config = [];
        Yii::$app->params['bloc_id'] = Yii::$app->service->commonGlobalsService->getBloc_id();
        Yii::$app->params['store_id'] = Yii::$app->service->commonGlobalsService->getStore_id();

        $store_id = Yii::$app->params['store_id'];
        $bloc_id = Yii::$app->params['bloc_id'];

        $requestedRoute = $this->module->requestedRoute ?? '';
        loggingHelper::writeLog('AddonsModule', 'init', '请求路由', [
            'requestedRoute' => $requestedRoute
        ]);
        /**
         * 获取请求方式
         */
        $method = Yii::$app->request->getMethod();
        if ($method != 'GET' && Yii::$app->id == 'app-api' && !StringHelper::strExists($requestedRoute, 'notify')  && !StringHelper::strExists($requestedRoute, 'wechatMsgHandle') && !StringHelper::strExists($requestedRoute, 'openWeixin/msg/event') && !StringHelper::strExists($requestedRoute, 'admin/auth')) {
            if (empty($bloc_id)){
                throw new HttpException(400, '请求头部不能缺少bloc-id');
            }
            if (empty($store_id)){
                throw new HttpException(400, '请求头部不能缺少store-id');
            }
        }

        /* 加载语言包 */
        $this->registerTranslations($module);

        $appId = Yii::$app->id;
        $configPath = '';
        switch ($appId) {
            case 'app-admin':
                $accessToken = Yii::$app->request->headers->get('access-token');
                $userId = \admin\models\DdApiAccessToken::find()->where(['access_token' => $accessToken])->select('user_id')->scalar();
                $this->setUserId($userId);
                $this->initGlobalConf($userId);
                $configPath = Yii::getAlias('@addons/' . $module . '/config/admin.php');
                $cookies = Yii::$app->response->cookies;
                // 添加一个cookie
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => 'zh-CN',
                ]));
                break;
            case 'app-frontend':
                $configPath = Yii::getAlias('@addons/' . $module . '/config/frontend.php');
                break;
            case 'app-console':
                $runtimePath = Yii::getAlias('@console/runtime/' . $module . '/swoole');
                // define('SWOOLE_RUNTIME', $runtimePath);
                FileHelper::mkdirs($runtimePath);
                if (is_dir($runtimePath)) {
                    @chmod($runtimePath, 0777);
                }
                $files = ['baseserver.log', 'baseserver.pid', 'swoole.log', 'swoole.log'];
                foreach ($files as $key => $value) {
                    if (!file_exists($runtimePath . '/' . $value)) {
                        file_put_contents($runtimePath . '/' . $value, '');
                        @chmod($runtimePath . '/' . $value, 0777);
                    }
                }
                break;
            case 'app-api':
            case 'app-swoole':
            default:
                $accessToken = Yii::$app->request->headers->get('access-token');

                $memberId = DdApiAccessToken::find()->where(['access_token' => $accessToken])->select('member_id')->scalar();
                $this->setMemberId($memberId);

                $configPath = Yii::getAlias('@addons/' . $module . '/config/api.php');
                $cookies = Yii::$app->response->cookies;
                // 添加一个cookie
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => 'zh-CN',
                ]));
                break;
        }

        if (file_exists($configPath)) {
            $config = require $configPath;
        }

        // 获取应用程序的组件
        $components = Yii::$app->getComponents();

        if (!empty($config['components'])) {
            // 遍历子模块独立配置的组件部分，并继承应用程序的组件配置
            foreach ($config['components'] as $k => $component) {
                if (isset($component['class']) && !isset($components[$k])) {
                    continue;
                }
                $config['components'][$k] = array_merge($components[$k], $component);
            }

            Yii::$app->setComponents($config['components']);
        }

        if (in_array($appId, ['app-admin', 'app-api', 'app-frontend'])) {
            // 初始化公众号配置信息
            $this->initWechat();
        }
        loggingHelper::writeLog('AddonsModule', 'init', '全局事件监听');
    }



    public function getMenus(): array
    {
        $modules = DdAddons::findOne(['identifie' => $this->id]);
        $callback = function ($menu) {
            $data = json_decode($menu['data'], true);
            $items = $menu['children'];
            $return = [
                'id' => $menu['id'],
                'text' => $menu['name'],
                'icon' => $menu['icon'],
                'order' => $menu['order'],
                'type' => $menu['type'],
                'targetType' => 'iframe-tab',
                'url' => $menu['route'],
            ];
            //处理我们的配置
            if ($data) {
                isset($data['visible']) && $return['visible'] = $data['visible']; //visible
                isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon']; //icon
                //other attribute e.g. class...
                $return['options'] = $data;
            }

            //没配置图标的显示默认图标
            (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'fa fa-circle-o';
            $items && $return['children'] = $items;

            return $return;
        };
        $where = ['is_sys' => 'addons', 'module_name' => $this->id];
        $menus = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback, $where);

        return ArrayHelper::arraySort($menus, 'order', 'asc');
    }

    public function initWechat(): void
    {
        $store_id = Yii::$app->request->input('store_id', 0);
        $config = require Yii::getAlias('@common/modules/officialaccount/config/config.php');

        $params = Yii::$app->params;
        $conf = $params['conf'];

        $Wechatpay = $conf['wechatpay'];
        $wechat = $conf['wechat'];

        $apiclient_cert = $Wechatpay['apiclient_cert'] ?? '';
        $apiclient_key = $Wechatpay['apiclient_key'] ?? '';
        $appId = Yii::$app->id;

        $notify_url = $appId === 'app-api'? Yii::$app->request->hostInfo . '/api/wechat/basics/notify' : Yii::$app->request->hostInfo . '/admin/wechat/basics/notify';
        // 支付参数设置
        $config['params']['wechatPaymentConfig'] = [
            'app_id' => $Wechatpay ? $Wechatpay['app_id'] : '',
            'mch_id' => $Wechatpay ? $Wechatpay['mch_id'] : '',
            'key' => $Wechatpay ? $Wechatpay['key'] : '', // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path' => $apiclient_cert, // XXX: 绝对路径！！！！
            'key_path' => $apiclient_key, // XXX: 绝对路径！！！！
            'notify_url' => $notify_url,
        ];

        $redirect_uri = !empty(Yii::$app->request->input('redirect_uri')) ? Yii::$app->request->input('redirect_uri') : '';

        // 公众号设置
        $wechatConfig = [
            /*
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => $wechat ? $wechat['app_id'] : '',
            'secret' => $wechat ? $wechat['secret'] : '',
            'token' => $wechat ? $wechat['token'] : '', // Token
            'aes_key' => $wechat ? $wechat['aes_key'] : '',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => $redirect_uri,
            ],
            // 日志配置
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => Yii::getAlias('@api/runtime/officialaccount/' . date('Ym/d') . '.log'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => Yii::getAlias('@api/runtime/officialaccount/' . date('Ym/d') . '.log'),
                        'level' => 'info',
                    ],
                ],
            ],
        ];

        $config['params']['wechatConfig'] = array_merge($config['params']['wechatConfig'], $wechatConfig);
        $Wxapp = $conf['wxapp'];

        // 小程序初始化
        // 小程序参数设置
        $config['params']['wechatMiniProgramConfig'] = [
            'app_id' => $Wxapp ? $Wxapp['AppId'] : '',
            'secret' => $Wxapp ? $Wxapp['AppSecret'] : '',
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
        // 将新的配置设置到应用程序
        // 很多都是写 Yii::configure($this, $config)，但是并不适用子模块，必须写 Yii::$App
        Yii::configure(Yii::$app, $config);
    }

    public function registerTranslations($module): void
    {
        [, $cate] = explode('_', $module);
        Yii::$app->i18n->translations[$cate] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'zh',
            'basePath' => '@addons/' . $module . '/messages',
        ];
    }

    private function setUserId($user_id): void
    {
        $this->_userId = $user_id;
    }


    private function setMemberId($memberId): void
    {
        $this->_memberId = $memberId;
    }

    public function getUserId(): int|null
    {
       return $this->_userId;
    }


    public function getMemberId(): int|null
    {
        return $this->_memberId;
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

}
