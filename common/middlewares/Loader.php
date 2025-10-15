<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-26 12:59:45
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 11:42:46
 */

namespace common\middlewares;


use diandi\addons\models\searchs\DdAddons;
use GuzzleHttp\Client;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\UnauthorizedHttpException;

class Loader implements BootstrapInterface
{
    /**
     * 应用标识.
     */
    protected $id;

    protected $app;

    /**
     * @param \yii\base\Application $app
     *
     * @throws UnauthorizedHttpException
     * @throws \Exception
     */
    public function bootstrap($app): void
    {
        $this->id = $app->id;
        $response = $app->response;

        switch ($this->id) {
            case 'app-api':
                $app->response->format = \yii\web\Response::FORMAT_JSON;
                if (Yii::$app->request->getMethod() == 'OPTIONS') {
                    $response->data = 'options请求 快速响应';
                    $response->statusCode = 200;
                    $response->send();
                    Yii::$app->end();
                    die;
                }

                $access_token = Yii::$app->request->input('access-token', '');
                $bloc_id = Yii::$app->request->input('bloc_id', 0);

                $store_id = Yii::$app->request->input('store_id', 0);
                // if ($access_token) {
                //     Yii::$app->service->commonMemberService->setAccessToken($access_token);
                // }
                $this->afreshLoad($bloc_id, $store_id);
                break;
            case 'app-admin':
                $app->response->format = \yii\web\Response::FORMAT_JSON;
                if (Yii::$app->request->getMethod() == 'OPTIONS') {
                    $response->data = 'options请求 快速响应1';
                    $response->statusCode = 200;
                    $response->send();
                    Yii::$app->end();
                    die;
                }
                $store_id = Yii::$app->request->input('store_id', 0);
                $bloc_id = Yii::$app->request->input('bloc_id', 0);
                $this->afreshLoad($bloc_id, $store_id);
                break;
            case 'app-install':
                return;
                break;
            case 'app-console':
                // 迁移不执行相关的全局方法
                $argvStr = implode(',', $_SERVER['argv']);
                $argvs = $this->getArgv($_SERVER['argv']);
                if (isset($argvs['--app'])) {
                    $this->app = $argvs['--app'];
                }
                if (strpos($argvStr, 'migrate') == false && strpos($argvStr, 'install') == false) {
                    $this->afreshLoad(isset($argvs['--bloc_id']) ?? 0, isset($argvs['--store_id']) ?? 0, isset($argvs['--addons']) ?? 0);
                }
                break;
        }
    }

    /**
     * 重载配置.
     *
     * @throws UnauthorizedHttpException
     */
    public function afreshLoad($bloc_id, $store_id): void
    {
        try {
            Yii::info('afreshLoad', 'afreshLoad');
            Yii::$app->service->commonGlobalsService->initId((int)$bloc_id, (int)$store_id);
            Yii::$app->service->commonGlobalsService->getConf((int)$bloc_id);
            // 初始化模块
            $modules = array_merge($this->getModulesByAddons(), $this->getPluginsByAddons(), $this->getCommonModules());
            Yii::$app->setModules($modules);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取模块.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getCommonModules()
    {
        $app_id = $this->id;
        $moduleFile = '';
        switch ($app_id) {
            case 'app-api':
                $moduleFile = 'api';
                break;
            case 'app-admin':
                $moduleFile = 'admin';
                break;
                break;
            case 'app-public':
                $moduleFile = 'public';
                break;
            case 'app-console':
                $moduleFile = 'console';
                break;
            default:
                $moduleFile = 'api';
        }

        $modules = [];
        $extendMethod = 'OPTIONS,';
        $extraPatterns = [];
        $authListAddons = ['openWeixin', 'officialaccount', 'wechat'];

        foreach ($authListAddons as $name) {
            $configPath = Yii::getAlias('@common/modules/' . $name . '/config/' . $moduleFile . '.php');
            if (file_exists($configPath)) {
                $config = require $configPath;
                if (!empty($config)) {
                    foreach ($config as $key => &$value) {
                        if (!empty($value['extraPatterns']) && is_array($value['extraPatterns'])) {
                            foreach ($value['extraPatterns'] as $k => $val) {
                                $newK = !(strpos($k, 'OPTIONS') === false) ? $k : $extendMethod . $k;
                                $extraPatterns[$newK] = $val;
                            }
                            $value['extraPatterns'] = $extraPatterns;
                        }
                    }

                    Yii::$app->getUrlManager()->addRules($config);

                    if (isset($config['controllerMap']) && is_array($config['controllerMap'])) {
                        foreach ($config['controllerMap'] as $key => $val) {
                            Yii::$app->controllerMap[$key] = $val;
                        }
                    }
                }
            }
            // 服务定位器注册
            $ClassName = 'common\\modules\\' . $name . '\module';

            $modules[self::toUnderScore($name)] = [
                'class' => $ClassName,
            ];
        }
        return $modules;
    }

    /**
     * 获取模块.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getPluginsByAddons()
    {
        $app_id = $this->id;
        $authListAddons = ['diandi_website', 'diandi_auth', 'diandi_senangpay'];
        $moduleFile = '';
        switch ($app_id) {
            case 'app-api':
                $moduleFile = 'api';
                break;
            case 'app-admin':
                $moduleFile = 'admin';
                break;
                break;
            case 'app-public':
                $moduleFile = 'public';
                break;
            case 'app-console':
                $moduleFile = 'console';
                break;
            default:
                $moduleFile = 'api';
        }

        $modules = [];
        $extendMethod = 'OPTIONS,';
        $extraPatterns = [];
        foreach ($authListAddons as $name) {
            $configPath = Yii::getAlias('@common/plugins/' . $name . '/config/' . $moduleFile . '.php');
            if (file_exists($configPath)) {
                $config = require $configPath;
                if (!empty($config)) {
                    foreach ($config as $key => &$value) {
                        if (!empty($value['extraPatterns']) && is_array($value['extraPatterns'])) {
                            foreach ($value['extraPatterns'] as $k => $val) {
                                $newK = !(strpos($k, 'OPTIONS') === false) ? $k : $extendMethod . $k;
                                $extraPatterns[$newK] = $val;
                            }
                            $value['extraPatterns'] = $extraPatterns;
                        }
                    }

                    Yii::$app->getUrlManager()->addRules($config);

                    if (isset($config['controllerMap']) && is_array($config['controllerMap'])) {
                        foreach ($config['controllerMap'] as $key => $val) {
                            Yii::$app->controllerMap[$key] = $val;
                        }
                    }
                }
            }
            // 服务定位器注册
            $ClassName = 'common\\plugins\\' . $name . '\\' . $moduleFile;

            $modules[self::toUnderScore($name)] = [
                'class' => $ClassName,
            ];
        }

        return $modules;
    }

    /**
     * 获取模块.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getModulesByAddons()
    {
        // 系统已经安装的
        $DdAddons = new DdAddons();
        $addons = $DdAddons->find()->asArray()->all();

        $app_id = $this->id;
        $authListAddons = array_column($addons, 'identifie');
        $moduleFile = '';
        switch ($app_id) {
            case 'app-api':
                $moduleFile = 'api';
                break;
            case 'app-admin':
                $moduleFile = 'admin';
                break;
                break;
            case 'app-public':
                $moduleFile = 'public';
                break;
            case 'app-console':
                $moduleFile = 'console';
                break;
            default:
                $moduleFile = 'api';
        }

        $modules = [];
        $extendMethod = 'OPTIONS,';
        $extraPatterns = [];
        foreach ($authListAddons as $name) {
            $configPath = Yii::getAlias('@addons/' . $name . '/config/' . $moduleFile . '.php');
            if (file_exists($configPath)) {
                $config = require $configPath;
                if (!empty($config)) {
                    foreach ($config as $key => &$value) {
                        if (!empty($value['extraPatterns']) && is_array($value['extraPatterns'])) {
                            foreach ($value['extraPatterns'] as $k => $val) {
                                $newK = !(strpos($k, 'OPTIONS') === false) ? $k : $extendMethod . $k;
                                $extraPatterns[$newK] = $val;
                            }
                            $value['extraPatterns'] = $extraPatterns;
                        }
                    }

                    Yii::$app->getUrlManager()->addRules($config);

                    if (isset($config['controllerMap']) && is_array($config['controllerMap'])) {
                        foreach ($config['controllerMap'] as $key => $val) {
                            Yii::$app->controllerMap[$key] = $val;
                        }
                    }
                }
            }
            // 服务定位器注册
            $ClassName = 'addons\\' . $name . '\\' . $moduleFile;
            $modules[self::toUnderScore($name)] = [
                'class' => $ClassName,
            ];
            /**
             *
             * 获取当前目录下所有的php类 $configPath = Yii::getAlias('@addons/' . $name . '/components/
             * 类名称要求是$name后半部分开头的，把这些类注册为yii的components组件.
             */
            $dir = Yii::getAlias('@addons/' . $name . '/components');
            if (is_dir($dir)) {
                $files = scandir($dir);
                unset($files[0], $files[1]);
                foreach ($files as $file) {
                    if (strpos($file, '.php') !== false) {
                        // 按照下划线分割字符串，例如 ['user', 'info', 'detail']
                        $ClassName = 'addons\\' . $name . '\\components\\' . str_replace('.php', '', $file);
                        $parts = explode('_', $name);
                        // 将每个部分首字母大写并拼接
                        $camelCaseName = '';
                        foreach ($parts as $index => $part) {
                            $camelCaseName .= $index > 0 ? ucfirst($part) : $part;
                        }
                        Yii::$app->setComponents([
                            $camelCaseName . str_replace('.php', '', $file) => [
                                'class' => $ClassName
                            ]
                        ]);
                    }
                }
            }
        }
        return $modules;
    }

    public static function toUnderScore($str)
    {
        $array = [];
        for ($i = 0; $i < strlen($str); ++$i) {
            if ($str[$i] == strtolower($str[$i])) {
                $array[] = $str[$i];
            } else {
                if ($i > 0) {
                    $array[] = '--';
                }

                $array[] = strtolower($str[$i]);
            }
        }

        return implode('', $array);
    }

    public function getArgv($argv)
    {
        $list = [];
        foreach ($argv as $key => $value) {
            if (strpos($value, '=') !== false) {
                list($k, $v) = explode('=', $value);
                if (!empty($v) && strpos($k, '--') !== false) {
                    $list[$k] = $v;
                }
            }
        }
        return $list;
    }
}
