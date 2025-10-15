<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-05 19:27:07
 */
namespace addons\diandi_place\api\public;
use api\controllers\AController;
use common\components\EasySwoole\IotClient;
use common\components\SenangPay\SenangPay;
use common\helpers\ResultHelper;
use Yii;
class ApiController extends AController
{
    public $modelClass = '';
    protected array $authOptional = ['*'];
    // protected $signOptional = ['rpc'];
    public function actionPayment(): ?array
   {
        $data = Yii::$app->request->post();
        if (!empty(Yii::$app->user->identity->member_id)) {
            $data['user_id'] = Yii::$app->user->identity->member_id??0;
        }
        $res = [];
        // 889167583736038
        // 38805-390178308
        $senangPay = new SenangPay(347168491126682, '5620-465', 0);
        // $res = $SenangPay->getTransactionList(1685946990, 1688538991);
        $payment_method = 'boost';
        $fpx_bank_code = '64';
        $customer_name = '王春生';
        $customer_email = '4564@qq.com';
        $order_id = '1212';
        $amount = '12';
        $detail = '121212';
        $res = $senangPay->payment($payment_method, $fpx_bank_code, $customer_name, $customer_email, $order_id, $amount, $detail);
        return ResultHelper::json(200, '请求成功',$res);
    }
    public function actionRpc(): ?array
   {
        $params = Yii::$app->request->post();
        $IotClient = new IotClient('common', 'CommonModule', 'mailBox', $params);
        $data = $IotClient->run();
        return ResultHelper::json(200, '请求成功', $data);
    }
    public function actionPay(): ?array
    {
        return ResultHelper::json(200, '请求成功');
    }
    function actionCeshi()
    {
        $data = Yii::$app->request->post();
        return ResultHelper::json(200, '请求成功',[$data]);
    }
}
