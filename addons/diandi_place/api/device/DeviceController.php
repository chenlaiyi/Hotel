<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-09 13:55:53
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-06 16:57:02
 */
namespace addons\diandi_place\api\device;
use addons\diandi_place\services\device\DeviceService;
use api\controllers\AController;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use Yii;
class DeviceController extends AController
{
    public function actionAdd(): array
    {
        $title = \Yii::$app->request->input('title') ?? '';
        $hotel_id = (int)Yii::$app->request->input('hotel_id') ?? 0;
        $tier_id = (int)Yii::$app->request->input('tier_id') ?? 0;
        $room_id = (int)Yii::$app->request->input('room_id') ?? 0;
        $mac = \Yii::$app->request->input('mac') ?? '';
        $project_id = (int)Yii::$app->request->input('project_id') ?? 0;
        $device_id = \Yii::$app->request->input('device_id') ?? 0;
        $device_type = (int)Yii::$app->request->input('device_type') ?? 0;
        $manufactor_id = (int)Yii::$app->request->input('manufactor_id') ?? 0;
        $res = DeviceService::addRoomDevice($title, $hotel_id, $tier_id, $room_id, $mac, $project_id, $device_id, $device_type, $manufactor_id);
        return ResultHelper::json(200, '添加成功', $res);
    }
    public function actionDel(): array
    {
        $id = \Yii::$app->request->input('id');
        $res = DeviceService::delRoomDevice($id);
        return ResultHelper::json(200, '删除成功', $res);
    }
    public function actionEdit(): array
    {
        $id = (int)Yii::$app->request->input('id') ?? 0;
        $title = \Yii::$app->request->input('title') ?? '';
        $type_id = (int)Yii::$app->request->input('type_id') ?? 0;
        $hotel_id = (int)Yii::$app->request->input('hotel_id') ?? 0;
        $tier_id = (int)Yii::$app->request->input('tier_id') ?? 0;
        $room_id = (int)Yii::$app->request->input('room_id') ?? 0;
        $mac = \Yii::$app->request->input('mac') ?? '';
        $device_id = \Yii::$app->request->input('device_id') ?? 0;
        $project_id = (int)Yii::$app->request->input('project_id') ?? 0;
        $device_type = (int)Yii::$app->request->input('device_type') ?? 0;
        $manufactor_id = (int)Yii::$app->request->input('manufactor_id') ?? 0;
        $displayorder = (int)Yii::$app->request->input('displayorder') ?? 0;
        $status = (int)Yii::$app->request->input('status') ?? 0;
        $res = DeviceService::editLock($id, $title, $type_id, $hotel_id, $tier_id, $room_id, $mac, $project_id, $device_id, $device_type, $manufactor_id, $displayorder, $status);
        return ResultHelper::json(200, '编辑成功', $res);
    }
    public function actionList(): array
    {
        $page = Yii::$app->request->input('page', 1);
        $pageSize = Yii::$app->request->input('pageSize', 10);
        $title = Yii::$app->request->input('title', '');
        $type_id = Yii::$app->request->input('type_id', 0);
        $hotel_id = Yii::$app->request->input('hotel_id', 0);
        $tier_id = Yii::$app->request->input('tier_id', 0);
        $room_id = Yii::$app->request->input('room_id', 0);
        $project_id = Yii::$app->request->input('project_id', 0);
        $device_type = Yii::$app->request->input('device_type', 0);
        $hotel_type = Yii::$app->request->input('hotel_type', 0);
        loggingHelper::writeLog('diandi_place','device/list','参数校验',[
            'inputdata'=>$device_type
        ]);
        $where = [
            'and',
            ['d.device_type' => (int) $device_type]
        ];
        if ($hotel_type) {
            $where[] = ['d.hotel_type' => $hotel_type];
        }
        if ($hotel_id) {
            $where[] = ['d.hotel_id' => $hotel_id];
        }
        if ($room_id) {
            $where[] = ['d.room_id' => $room_id];
        }
        if ($tier_id) {
            $where[] = ['d.tier_id' => $tier_id];
        }
        if ($project_id) {
            $where[] = ['d.project_id', $project_id];
        }
        if ($title) {
            $where[] = ['like', 'd.title', $title];
        }
        $res = DeviceService::deviceList($page, $pageSize, $where);
        return ResultHelper::json(200, '获取成功', $res);
    }
    /**
     * 房间关联设备
     * @return array
     */
    public function actionRoomDevice(): array
    {
        $hotel_type = Yii::$app->request->get('hotel_type', 0);
        $hotel_id = Yii::$app->request->get('hotel_id', 0);
        $tier_id = Yii::$app->request->get('tier_id', 0);
        $room_id = Yii::$app->request->get('room_id', 0);
        $unit_id = Yii::$app->request->get('unit_id', 0);
        $res = DeviceService::roomDevice($hotel_type, $hotel_id, $tier_id, $room_id,$unit_id);
        return ResultHelper::json(200, '获取成功', $res);
    }
    /**
     * 设备详细信息
     * @return array
     * @date 2023-07-06
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionDevinfo(): array
    {
        $device_id = (int)Yii::$app->request->input('device_id');
        $res = DeviceService::devInfo($device_id);
        return ResultHelper::json(200, '获取成功', $res);
    }
}
