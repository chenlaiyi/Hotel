<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-11-18 13:50:47
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-09-29 20:13:43
 */

namespace common\services\api;

use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\DdAiSmsLog;
use common\queues\SmsJob;
use common\services\BaseService;
use Exception;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\Strategies\OrderStrategy;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class SmsService.
 *
 * @author chunchun <2192138785@qq.com>
 */
class SmsService extends BaseService
{
    /**
     * 消息队列.
     *
     * @var bool
     */
    public bool $queueSwitch = false;

    /**
     * @var array
     */
    protected array $config = [];

    protected $conf_type = 'addons';//addons sys

    public function __construct()
    {
        parent::__construct();
        /**
         * 优先使用公司的配置
         */
        $smsConfig = Yii::$app->params['conf']['sms'] ?? [];
        loggingHelper::writeLog('SmsService', '__construct', '短信验证码', [
            'smsConfig' => $smsConfig,
            'gconfig' => Yii::$app->params['conf'],
            'bloc_id' => Yii::$app->request->input('bloc_id'),
        ]);
        if (empty($smsConfig)) {
            $settings = Yii::$app->settings;
            $settings->invalidateCache();
            $info = $settings->getAllBySection('Website');
            $this->conf_type = 'sys';

            $this->config = [
                // HTTP 请求的超时时间（秒）
                'timeout' => 20.0,
                // 默认发送配置
                'default' => [
                    // 网关调用策略，默认：顺序调用
                    'strategy' => OrderStrategy::class,
                    // 默认可用的发送网关
                    'gateways' => [
                        'aliyun',
                        'qcloud'
                    ],
                ],
                // 可用的网关配置
                'gateways' => [
                    'errorlog' => [
                        'file' => Yii::getAlias('runtime') . '/easy-sms.log',
                    ],
                    'aliyun' => [
                        'access_key_id' => $info['aliyun_access_key_id'] ?? '',
                        'access_key_secret' => $info['aliyun_access_key_secret'] ?? '',
                        'sign_name' => $info['aliyun_sign_name'] ?? '',
                    ],
                    'qcloud' => [
                        'sdk_app_id' => $info['qcloud_sdk_app_id'] ?? '', // 短信应用的 SDK APP ID
                        'secret_id' => $info['qcloud_secret_id'] ?? '', // SECRET ID
                        'secret_key' => $info['qcloud_secret_key'] ?? '', // SECRET KEY
                        'sign_name' => $info['qcloud_sign_name'] ?? '', // 短信签名
                    ]
                ],
            ];
        } else {
            //        access_key_id
            //        access_key_secret
            //        sign_name
            //        template_code

            $this->config = [
                // HTTP 请求的超时时间（秒）
                'timeout' => 5.0,
                // 默认发送配置
                'default' => [
                    // 网关调用策略，默认：顺序调用
                    'strategy' => OrderStrategy::class,
                    // 默认可用的发送网关
                    'gateways' => [
                        'aliyun',
                        'qcloud'
                    ],
                ],
                // 可用的网关配置
                'gateways' => [
                    'errorlog' => [
                        'file' => Yii::getAlias('runtime') . '/easy-sms.log',
                    ],
                    'aliyun' => [
                        'access_key_id' => $smsConfig['access_key_id'] ?? '',
                        'access_key_secret' => $smsConfig['access_key_secret'] ?? '',
                        'sign_name' => $smsConfig['sign_name'] ?? '',
                    ]
                ],
            ];
        }

        loggingHelper::writeLog('SmsService', '__construct', '短信验证码', [
            'smsConfig' => $smsConfig,
            'conf' => $this->config
        ]);

    }

    /**
     * 发送短信
     *
     * ```php
     *       Yii::$App->services->sms->send($mobile, $code, $usage, $member_id)
     * ```
     *
     * @param int $mobile 手机号码
     * @param int $code 验证码
     * @param string $usage 用途
     * @param int $member_id 用户ID
     *
     * @return array|string|null
     *
     * @throws UnprocessableEntityHttpException
     */
    public function send(int $mobile, int $code, string $usage, int $member_id = 0): array|string|null
    {
        $ip = ip2long(Yii::$app->request->userIP);
        if ($this->queueSwitch) {
            return Yii::$app->queue->push(new SmsJob([
                'mobile' => $mobile,
                'code' => $code,
                'usage' => $usage,
                'member_id' => $member_id,
                'ip' => $ip,
            ]));
        }

        return $this->realSend($mobile, $code, $usage, $member_id, $ip);
    }

    /**
     * 真实发送短信
     *
     * @param $mobile
     * @param $code
     * @param $usage
     * @param int $member_id
     * @param int $ip
     * @return string|array|null
     * @throws UnprocessableEntityHttpException
     * @throws Exception
     */
    public function realSend($mobile, $code, $usage, int $member_id = 0, int $ip = 0): null|string|array
    {
        try {
            $settings = Yii::$app->settings;
//            $settings->invalidateCache();
//            $info = $settings->getAllBySection('Website');
            // 校验发送是否频繁
            if (($smsLog = $this->findByMobile($mobile)) && $smsLog['created_at'] + 60 > time()) {
                throw new NotFoundHttpException('请不要频繁发送短信');
            }


            $smsIpLog = $this->findByIp();
            if ($smsIpLog && $smsIpLog['created_at'] + 60 > time()) {
                throw new NotFoundHttpException('请不要频繁发送短信');
            }
            $smsConfig = Yii::$app->params['conf']['sms'] ?? [];

            $easySms = new EasySms($this->config);
            if ($this->conf_type === 'sys') {
                $content = [
                    'template' => function ($gateway) use ($settings) {
                        loggingHelper::writeLog('sms', 'SmsService', '发送模板', [
                            'gateway' => $gateway->getName(),
                            'aliyun_template_code' => $settings->get('Website', 'aliyun_template_code'),
                            'qcloud_template_code' => $settings->get('Website', 'qcloud_template_code')
                        ]);
                        if ($gateway->getName() == 'aliyun') {
                            return $settings->get('Website', 'aliyun_template_code');
                        }
                        return $settings->get('Website', 'qcloud_template_code');
                    },
                    'data' => function ($gateway) use ($code) {
                        if ($gateway->getName() == 'aliyun') {
                            return [
                                'code' => $code
                            ];
                        } else {
                            return [
                                $code, 60
                            ];
                        }
                    },
                ];
            } else {
                $content = [
                    'template' => function () use ($settings, $smsConfig) {
                        return $smsConfig['template_code'];
                    },
                    'data' => function ($gateway) use ($code) {
                        if ($gateway->getName() == 'aliyun') {
                            return [
                                'code' => $code
                            ];
                        } else {
                            return [
                                $code, 60
                            ];
                        }
                    },
                ];
            }


            $result = $easySms->send($mobile, $content);
            loggingHelper::writeLog('sms', 'SmsService', '发送内容', [
                'content' =>  $content,
                'conf_type' => $this->conf_type,
                'smsConfig' => $smsConfig,
                'mobile' => (string)$mobile,
                'result' => $result,
            ]);
            $this->saveLog([
                'mobile' => (string)$mobile,
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 200,
                'error_msg' => 'ok',
                'error_data' => Json::encode($result),
            ]);
            return ResultHelper::json(200, '发送成功');
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (NoGatewayAvailableException $e) {
            $errorMessage = [];
            $exceptions = $e->getExceptions();
            $gateways = $this->config['default']['gateways'];

            foreach ($gateways as $gateway) {
                if (isset($exceptions[$gateway])) {
                    $errorMessage[$gateway] = $exceptions[$gateway]->getMessage();
                }
            }
            $log = $this->saveLog([
                'mobile' => (string)$mobile,
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 422,
                'error_msg' => '发送失败',
                'error_data' => Json::encode($errorMessage),
            ]);

            loggingHelper::writeLog('sms', 'SmsService', '发送失败', [
                'errorMessage' => $errorMessage,
                'log' => $log
            ]);
            throw new UnprocessableEntityHttpException('短信发送失败');
        }
    }

    /**
     * 真实发送短信
     *
     * @param $mobile
     * @param array $data
     * @param string $template
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    public function sendContent($mobile, array $data = [], string $template = ''): array
    {
        try {
            $easySms = new EasySms($this->config);
            return $easySms->send($mobile, [
                'template' => $template,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            $errorMessage = [];
            $exceptions = $e->getExceptions();
            $gateways = $this->config['default']['gateways'];

            foreach ($gateways as $gateway) {
                if (isset($exceptions[$gateway])) {
                    $errorMessage[$gateway] = $exceptions[$gateway]->getMessage();
                }
            }

            throw new UnprocessableEntityHttpException('短信发送失败:' . implode(',', $errorMessage));
        }
    }

    /**
     * @return array
     */
    // public function stat($type)
    // {
    //     $fields = [
    //         'count' => '异常发送数量'
    //     ];

    //     // 获取时间和格式化
    //     list($time, $format) = EchantsHelper::getFormatTime($type);
    //     // 获取数据
    //     return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
    //         return SmsLog::find()
    //             ->select(["from_unixtime(created_at, '$formatting') as time", 'count(id) as count'])
    //             ->andWhere(['between', 'created_at', $start_time, $end_time])
    //             ->andWhere(['status' => StatusEnum::ENABLED])
    //             ->andWhere(['>', 'error_code', 399])
    //             ->andFilterWhere(['merchant_id' => Yii::$App->services->merchant->getId()])
    //             ->groupBy(['time'])
    //             ->asArray()
    //             ->all();
    //     }, $fields, $time, $format);
    // }

    /**
     * @param $mobile
     *
     * @return array|ActiveRecord|null
     */
    public function findByMobile($mobile): array|ActiveRecord|null
    {
        return DdAiSmsLog::find()
            ->where(['mobile' => $mobile])
            ->orderBy('id desc')
            ->asArray()
            ->one();
    }

    /**
     * @param array $data
     *
     * @return DdAiSmsLog
     * @throws Exception
     */
    protected function saveLog(array $data = []): DdAiSmsLog
    {
        $log = new DdAiSmsLog();

        if ($log->load($data, '') && $log->save()) {
            return $log;
        } else {
            $msg = ErrorsHelper::getModelError($log);
            throw new Exception($msg);
        }
    }

    private function findByIp()
    {
        $ip = ip2long(Yii::$app->request->userIP);

        return DdAiSmsLog::find()
            ->where(['ip' => $ip])
            ->orderBy('id desc')
            ->asArray()
            ->one();
    }
}
