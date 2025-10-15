<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-16 10:30:53
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-30 09:43:26
 */
namespace addons\diandi_place\services;
use common\components\addons\AddonsModule;
use common\helpers\ArrayHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\AccountLog;
use Yii;
use yii\db\Exception;
/**
 * diandi_dingzuo module definition class.
 */
class NotifyService extends AddonsModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = "addons\diandi_tea\api";
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
    // 支付回调
    // {
    //     "appid": "wx028eb56f4b4a7d99",
    //     "bank_type": "OTHERS",
    //     "cash_fee": "5",
    //     "fee_type": "CNY",
    //     "is_subscribe": "N",
    //     "mch_id": "1228641802",
    //     "nonce_str": "5e6be567474bb",
    //     "openid": "oE5EC0aqNTAdAXpPfikBpkHiSG1o",
    //     "out_trade_no": "2020031455505497",
    //     "result_code": "SUCCESS",
    //     "return_code": "SUCCESS",
    //     "sign": "99C78A7B9A9110E9A4EA4D5040596700",
    //     "time_end": "20200314035649",
    //     "total_fee": "5",
    //     "trade_type": "JSAPI",
    //     "transaction_id": "4200000518202003141950666245"
    // }
    /**
     * Undocumented function.
     *
     * @param [type] $params
     *
     * @return void
     * @throws \Throwable
     * @throws Exception
     */
    public static function Notify($params)
    {
        loggingHelper::writeLog('diandi_tea', 'Notify', '模块内回调', $params);
        $GorderType = StringHelper::msubstr($params['out_trade_no'], 0, 1);
        loggingHelper::writeLog('diandi_tea', 'Notify', '订单类型', $GorderType);
        if ($GorderType == 'H') {
            //$transaction = DistributionAccountStorePay::getDb()->beginTransaction();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'Notify', '包间订单处理,错误信息Exception', $e);
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'Notify', '包间订单处理,错误信息Throwable', $e);
                throw $e;
            }
            if ($params['is_auto'] == 1) {
                return ResultHelper::json(200, '支付成功');
            } else {
                echo ArrayHelper::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                die;
            }
        } elseif ($GorderType == 'X') {
            //$transaction = DistributionAccountStorePay::getDb()->beginTransaction();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'Notify', '包间续费订单处理,错误信息Exception', $e);
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'Notify', '包间续费订单处理,错误信息Throwable', $e);
                throw $e;
            }
            if ($params['is_auto'] == 1) {
                return ResultHelper::json(200, '支付成功');
            } else {
                echo ArrayHelper::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
                die;
            }
        }
        echo ArrayHelper::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
        die;
    }
    public static function AccountLog($data)
    {
        $AccountLog = new AccountLog();
        $AccountLog->load($data, '');
        $AccountLog->save();
    }
}
