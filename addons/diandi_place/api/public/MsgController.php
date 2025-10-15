<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 09:12:06
 */
namespace addons\diandi_place\api\public;
use addons\diandi_place\models\order\PlaceOrderList;
use addons\diandi_place\services\MessageService;
use api\controllers\AController;
use common\helpers\ResultHelper;
class MsgController extends AController
{
    public $modelClass = '';
    protected array $authOptional = ['*'];
    public function actionTimeMsg()
   {
        $member_id =\Yii::$app->request->input('member_id');
        $order_id =\Yii::$app->request->input('order_id');
        $Res =  MessageService::orderTimeMsg($member_id);
        return ResultHelper::json(200, '请求成功', $Res);
    }
}
