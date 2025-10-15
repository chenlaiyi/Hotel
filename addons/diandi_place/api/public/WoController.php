<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-08-25 18:55:00
 */
namespace addons\diandi_place\api\public;
use addons\diandi_place\models\enums\OrderStatusEnums;
use addons\diandi_place\models\order\PlaceOrderList;
use addons\diandi_place\models\order\PlaceOrderMember;
use addons\diandi_place\services\MemberService;
use addons\diandi_place\services\MessageService;
use addons\diandi_place\services\WoSdk;
use api\controllers\AController;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
/**
 * 人脸机接口
 * @date 2023-05-30
 * @example
 * @author Wang Chunsheng
 * @since
 */
class WoController extends AController
{
    public $modelClass = '';
    protected array $authOptional = [];
    public function actionPersonAdd(): array
   {
        $cardNo =\Yii::$app->request->input('cardNo'); //	String	Y		卡号（IC卡／身份证等）
        $name =\Yii::$app->request->input('name'); //	String	N		人员姓名
        $phone =\Yii::$app->request->input('phone'); //	String	Y		手机号
        $tag =\Yii::$app->request->input('tag'); //	String	Y		人员tag
        $idCardNo =\Yii::$app->request->input('idCardNo'); //	String	Y		身份证号
        try {
            $res = WoSdk::personCreate($cardNo, $name, $phone, $tag, $idCardNo);
        } catch (GuzzleException $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
        return ResultHelper::json(200, '请求成功', $res);
    }
    public function actionWebhook(): array
   {
        $eventMsg = json_decode(Yii::$app->request->input('eventMsg'), true);
        loggingHelper::writeLog('diandi_place', 'actionWebhook', '回调数据开始', [
            'eventMsg' =>\Yii::$app->request->input('eventMsg')
        ]);
        $admitGuid = $eventMsg['admitGuid'];
        $order = PlaceOrderMember::find()->where(['personGuid' => $admitGuid])->select(['order_id', 'store_id', 'bloc_id'])->asArray()->one();
        $order_id = $order['order_id'];
        PlaceOrderList::updateAll([
            'status' => OrderStatusEnums::CHECKIN
        ], [
            'id' => $order_id
        ]);
        // 下发预订成功，
        MessageService::joinRoomMsg($order_id);
        MessageService::checkInMember($order_id);
        loggingHelper::writeLog('diandi_place', 'actionWebhook', '回调数据', [
            'eventMsg' =>\Yii::$app->request->input('eventMsg'),
            'order_id' => $order_id,
            'admitGuid' => $admitGuid,
        ]);
        return ResultHelper::json(200, '回调成功');
    }
    public function actionFaceAdd(): array
   {
        $personGuid =\Yii::$app->request->input('personGuid');
        $url =\Yii::$app->request->input('url');
        $bizData =\Yii::$app->request->input('bizData');
        try {
            $res = WoSdk::faceAdd($personGuid, $url, $bizData);
        } catch (GuzzleException $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
        return ResultHelper::json(200, '请求成功', $res);
    }
    public function actionAuthDevice(): array
   {
        $order_id = (int)\Yii::$app->request->input('order_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        $res = MemberService::getFace($member_id, $order_id);
        return ResultHelper::json(200, '设备授权成功', $res);
    }
    public function actionDeleteDevice(): array
   {
        $order_id = (int)\Yii::$app->request->input('order_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        $info = MemberService::getFace($member_id, $order_id);
        $deviceKey = '84E0F425C137527A'; //\Yii::$app->request->input('deviceKey');
        $personGuid = $info['personGuid']; //\Yii::$app->request->input('personGuid');
        if (empty($deviceKey)) {
            return ResultHelper::json(400, 'deviceKey 不能为空');
        }
        if (empty($personGuid)) {
            return ResultHelper::json(400, 'personGuid 不能为空', $info);
        }
        $res = WoSdk::authDevice($deviceKey, $personGuid);
        MessageService::lockAuth($order_id);
        return ResultHelper::json(200, '请求成功', $res);
    }
}
