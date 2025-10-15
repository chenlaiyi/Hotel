<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-11-18 13:50:47
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-07-12 10:48:41
 */

namespace admin\modules\customer\services;

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
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
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

    public function __construct()
    {
        parent::__construct();
        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $info = $settings->getAllBySection('Website');
        $this->config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => OrderStrategy::class,
                // 默认可用的发送网关
                'gateways' => [
                    'qcloud',
                    'aliyun'
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => Yii::getAlias('@admin/runtime') . '/easy-sms.log',
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
        loggingHelper::writeLog('sms', 'SmsService', '配置数据', [
            'config' => $this->config,
            'info' => $info
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
     * @return string|null
     *
     * @throws UnprocessableEntityHttpException
     */
    public function send($mobile, int $code, string $usage, int $member_id = 0)
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
            $settings->invalidateCache();
            $info = $settings->getAllBySection('Website');
            // 校验发送是否频繁
            if (($smsLog = $this->findByMobile($mobile)) && $smsLog['created_at'] + 60 > time()) {
                throw new NotFoundHttpException('请不要频繁发送短信');
            }
            $smsIpLog = $this->findByIp();
            if ($smsIpLog && $smsIpLog['created_at'] + 60 > time()){
                throw new NotFoundHttpException('请不要频繁发送短信');
            }

            $easySms = new EasySms($this->config);
            $content = [
                'template' => function ($gateway) use ($settings) {
                    loggingHelper::writeLog('sms', 'SmsService', '发送模板', [
                        'gateway' => $gateway->getName(),
                        'aliyun_template_code'=> $settings->get('Website', 'aliyun_template_code'),
                        'qcloud_template_code'=> $settings->get('Website', 'qcloud_template_code')
                    ]);
                    if ($gateway->getName() == 'aliyun') {
                        return $settings->get('Website', 'aliyun_template_code');
                    }
                    return (int) $settings->get('Website', 'qcloud_template_code');
                },
                'data' => function ($gateway) use ($code)  {
                    if ($gateway->getName() == 'aliyun')  {
                        return [
                            'code' => $code
                        ];
                    }else{
                        return [
                            $code,60
                        ];
                    }
                },
            ];
            loggingHelper::writeLog('sms', 'SmsService', '发送内容', [
                'content' => $content
            ]);
            $result = $easySms->send($mobile, $content);

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
            loggingHelper::writeLog('sms', 'SmsService', '发送失败', [
                'errorMessage' => $errorMessage
            ]);
            $log = $this->saveLog([
                'mobile' => $mobile,
                'code' => $code,
                'member_id' => $member_id,
                'usage' => $usage,
                'ip' => $ip,
                'error_code' => 422,
                'error_msg' => '发送失败',
                'error_data' => Json::encode($errorMessage),
            ]);

            throw new UnprocessableEntityHttpException('短信发送失败');
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
     * 查询ip发送
     */
    public function findByIp(): array|ActiveRecord|null
    {
        $ip = ip2long(Yii::$app->request->userIP);

        return DdAiSmsLog::find()
            ->where(['ip' => $ip])
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
}
