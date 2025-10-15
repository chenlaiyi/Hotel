<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-14 10:56:16
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-07 15:23:34
 */
namespace addons\diandi_place\api;
use addons\diandi_place\models\device\PlaceRoomDevice;
use addons\diandi_place\services\device\DeviceService;
use addons\diandi_place\services\NotifyService;
use api\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use Yii;
use yii\db\Exception;
/**
 * 物联网设备操作回调接口
 */
class NotifyController extends AController
{
    public $modelClass = '';
    protected array $authOptional = ['call-back'];
    /**
     * 物联网平台数据回调
     * @return array
     * @throws \Throwable
     * @date 2023-06-07
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionCallBack(): array
    {
        try {
        $all =  Yii::$app->request->post();
        loggingHelper::writeLog('diandi_place', 'CallBack', '数据回调', [
            'all' => $all
        ]);
        $eventName = Yii::$app->request->post('eventName', 'deviceStatus');
        $data = Yii::$app->request->post('data', []);
        $project_id = Yii::$app->request->post('project_id', 0);
        $device_id = Yii::$app->request->post('device_id', 0);
        $lockMac = Yii::$app->request->post('lockMac', '');
        $elemeterMac = Yii::$app->request->post('elemeterMac', '');
        $Res = [];
        switch ($eventName) {
            case 'deviceStatus':
                $device_id = Yii::$app->request->post('device_id');
                $dev = PlaceRoomDevice::findOne(['device_id' => $device_id]);
                if ($dev) {
                    loggingHelper::writeLog('diandi_place', 'CallBack', '设备数据', [
                        'device_id' => $device_id,
                        'dev' => $dev->toArray(),
                        'data' => Yii::$app->request->input('data')
                    ]);
                    $dev->device_status = json_encode($data, JSON_NUMERIC_CHECK);
                    $Res = $dev->update();
                    loggingHelper::writeLog('diandi_place', 'CallBack', '更新结果', [
                        'Res' => $Res,
                        'msg' => ErrorsHelper::getModelError($dev)
                    ]);
                }
                break;
            case 'GatewayBind':
                loggingHelper::writeLog('diandi_place', 'CallBack', '绑定网关', $all);
                $hotel_id = $data['hotel_id'] ?? 0;
                $tier_id = $data['tier_id'] ?? 0;
                $room_id = $data['room_id'] ?? 0;
                $gatewayMac = Yii::$app->request->post('gatewayMac', '');
                $Res = DeviceService::addRoomDevice($data['title'], $hotel_id, $tier_id, $room_id, $gatewayMac, $project_id, $device_id, $data['device_type'], $data['manufactor_id'],$data['is_default']??1);
                break;
            case 'LockBindHotel':
                loggingHelper::writeLog('diandi_place', 'CallBack', '绑定门锁', $all);
                $hotel_id = $data['hotel_id'] ?? 0;
                $tier_id = $data['tier_id'] ?? 0;
                $room_id = $data['room_id'] ?? 0;
                $Res = DeviceService::addRoomDevice($data['title'], $hotel_id, $tier_id, $room_id, $lockMac, $project_id, $device_id, $data['device_type'], $data['manufactor_id'],$data['is_default']??1);
                break;
            case 'LockUnBindHotel':
                loggingHelper::writeLog('diandi_place', 'CallBack', '门锁解除关联房间楼栋', $all);
                break;
            case 'DelBluetoothDoorLock':
                loggingHelper::writeLog('diandi_place', 'CallBack', '删除门锁', $all);
                $Res = DeviceService::delRoomDevice($lockMac);
                break;
            case 'LockUnBindingGateway':
                //门锁解绑网关
                loggingHelper::writeLog('diandi_place', 'CallBack', '门锁解绑网关');
                break;
            case 'LockBindingGateway':
                //门锁绑定网关
                loggingHelper::writeLog('diandi_place', 'CallBack', '门锁绑定网关');
                break;
            /**
             * 门禁
             */
            case 'AccesscontrolBindHotel':
                loggingHelper::writeLog('diandi_place', 'CallBack', '绑定门禁', $all);
                $hotel_id = $data['hotel_id'] ?? 0;
                $tier_id = $data['tier_id'] ?? 0;
                $room_id = $data['room_id'] ?? 0;
                $Res = DeviceService::addRoomDevice($data['title'], $hotel_id, $tier_id, $room_id, $lockMac, $project_id, $device_id, $data['device_type'], $data['manufactor_id'],$data['is_default']??1);
                break;
            case 'AccesscontrolUnBindHotel':
                loggingHelper::writeLog('diandi_place', 'CallBack', '门禁解除关联房间楼栋', $all);
                break;
            case 'DelBluetoothAccesscontrol':
                loggingHelper::writeLog('diandi_place', 'CallBack', '删除门禁', $all);
                $Res = DeviceService::delRoomDevice($lockMac);
                break;
            case 'AccesscontrolUnBindingGateway':
                //门禁解绑网关
                loggingHelper::writeLog('diandi_place', 'CallBack', '门禁解绑网关');
                break;
            case 'AccesscontrolBindingGateway':
                //门禁绑定网关
                loggingHelper::writeLog('diandi_place', 'CallBack', '门禁绑定网关');
                break;
            /**
             * 忠精门锁
             */
            case 'ZjLockBindHotel':
                loggingHelper::writeLog('diandi_place', 'CallBack', '绑定忠精门锁', $all);
                $hotel_id = $data['hotel_id'] ?? 0;
                $tier_id = $data['tier_id'] ?? 0;
                $room_id = $data['room_id'] ?? 0;
                $Res = DeviceService::addRoomDevice($data['title'], $hotel_id, $tier_id, $room_id, $lockMac, $project_id, $device_id, $data['device_type'], $data['manufactor_id'],$data['is_default']??1);
                break;
            case 'ZjLockUnBindHotel':
                loggingHelper::writeLog('diandi_place', 'CallBack', '忠精门锁解除关联房间楼栋', $all);
                break;
            case 'ZjDelBluetoothDoorLock':
                loggingHelper::writeLog('diandi_place', 'CallBack', '删除忠精门锁', $all);
                $Res = DeviceService::delRoomDevice($lockMac);
                break;
            case 'elemeterAddDev':
                //新增电表
                $hotel_id = $data['hotel_id'] ?? 0;
                $tier_id = $data['tier_id'] ?? 0;
                $room_id = $data['room_id'] ?? 0;
                loggingHelper::writeLog('diandi_place', 'CallBack', '新增电表', [
                    'title' => $data['title'],
                    'hotel_id' => $hotel_id,
                    'tier_id' => $tier_id,
                    'room_id' => $room_id,
                    'elemeterMac' => $elemeterMac,
                    'project_id' => $project_id,
                    'device_id' => $device_id,
                    'device_type' => $data['device_type'],
                    'manufactor_id' => $data['manufactor_id']
                ]);
                $Res = DeviceService::addRoomDevice($data['title'], $hotel_id, $tier_id, $room_id, $elemeterMac, $project_id, $device_id, $data['device_type'], $data['manufactor_id'],$data['is_default']??1);
                break;
            case 'elemeterRemoveDev':
                //电表删除
                $Res = DeviceService::delRoomDevice($elemeterMac);
                break;
            case 'elemeterUnBindingGateway':
                //电表解绑网关
                loggingHelper::writeLog('diandi_place', 'CallBack', '电表解绑网关');
                break;
            case 'elemeterUnBindHotel':
                //电表解绑楼栋
                loggingHelper::writeLog('diandi_place', 'CallBack', '电表解绑楼栋');
                break;
            case 'receptacleOnOffDel':
                //删除零火开关
                loggingHelper::writeLog('diandi_place', 'CallBack', '删除零火开关');
                $switch_mac = Yii::$app->request->post('switchMac', '');
                $Res = DeviceService::delRoomDevice($switch_mac);
                break;
            case 'receptacleOnOffUnBind':
                //解绑零火开关
                loggingHelper::writeLog('diandi_place', 'CallBack', '解绑零火开关');
                break;
            case 'receptacleOnOffBind':
                //零火开关绑定
                $hotel_id = $data['hotel_id'] ?? 0;
                $tier_id = $data['tier_id'] ?? 0;
                $room_id = $data['room_id'] ?? 0;
                $switch_mac = Yii::$app->request->post('switchMac', '');
                loggingHelper::writeLog('diandi_place', 'CallBack', '零火开关绑定');
                $Res = DeviceService::addRoomDevice($data['title'], $hotel_id, $tier_id, $room_id, $switch_mac, $project_id, $device_id, $data['device_type'], $data['manufactor_id'],$data['is_default']??1);
                loggingHelper::writeLog('diandi_place', 'CallBack', '零火开关绑定结果',[
                    'res'=>$Res
                ]);
                break;
        }
        }catch (\Throwable $e){
            loggingHelper::writeLog('diandi_place', 'CallBack', '错误信息', [
                'error'=>$e->getMessage(),
                'trace'=>$e->getTrace()
            ]);
        }
        return ResultHelper::json(200, '请求成功', [
            'Res' => $Res
        ]);
    }
    /**
     * @throws \Throwable
     * @throws Exception
     */
    public function actionNotify()
    {
        $data = Yii::$app->request->input();
        if (empty($data['out_trade_no'])) {
            return ResultHelper::json(401, '缺少订单id');
        }
        //手动回调
        $data['is_auto'] = 1;
        NotifyService::Notify($data);
        return ResultHelper::json(200, '请求成功');
    }
}
