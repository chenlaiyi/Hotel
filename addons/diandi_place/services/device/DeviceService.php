<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-09 14:01:34
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-06 16:58:38
 */
namespace addons\diandi_place\services\device;
use addons\diandi_place\models\device\PlaceRoomDevice;
use addons\diandi_place\models\enums\DeviceStatusEnum;
use addons\diandi_place\models\enums\DeviceTypeEnum;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceTier;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use yii\data\Pagination;
use yii\db\StaleObjectException;
class DeviceService extends BaseService
{
    public static function addRoomDevice($title, $hotel_id, $tier_id, $room_id, $mac, $project_id, $device_id, $device_type, $manufactor_id,$is_default=1): array
    {
        $PlaceRoomDevice = new PlaceRoomDevice();
        // 查询该设备是否已经绑定过
        $ishave = $PlaceRoomDevice->find()->where(['device_id' => $device_id])->select(['id'])->one();
        if ($ishave) {
            return ResultHelper::json(400, '该设备已绑定其他房间', [
                'sql' => $PlaceRoomDevice->find()->where(['device_id' => $device_id])->select(['id'])->createCommand()->getRawSql()
            ]);
        }
        $hotel = PlaceList::find()->where(['id' => $hotel_id])->select(['type','bloc_id'])->one();
        $hotel_type = $hotel['type'];
        $bloc_id = $hotel['bloc_id'];
        $PlaceRoomDevice->load([
            'title' => $title,
            'bloc_id' => $bloc_id,
            'hotel_type' => (int)$hotel_type,
            'hotel_id' => (int)$hotel_id,
            'tier_id' => (int)$tier_id,
            'room_id' => (int)$room_id,
            'project_id' => (int)$project_id,
            'mac' => $mac,
            'device_id' => (int)$device_id,
            'device_type' => $device_type,
            'is_default' => $is_default,
            'manufactor_id' => (int)$manufactor_id,
            'status' => DeviceStatusEnum::BINDING,
        ], '');
        if (!$PlaceRoomDevice->save()) {
            $msg = ErrorsHelper::getModelError($PlaceRoomDevice);
            return ResultHelper::json(400, $msg,[
                'title' => $title,
                'hotel_type' => $hotel_type,
                'hotel_id' => $hotel_id,
                'tier_id' => $tier_id,
                'room_id' => $room_id,
                'project_id' => $project_id,
                'mac' => $mac,
                'device_id' => $device_id,
                'device_type' => $device_type,
                'manufactor_id' => $manufactor_id,
                'status' => DeviceStatusEnum::BINDING,
            ]);
        }
        return $PlaceRoomDevice->toArray();
    }
    public static function editLock($id, $title, $type_id, $hotel_id, $tier_id, $room_id, $mac, $project_id, $device_id, $device_type, $manufactor_id, $displayorder, $status): array
    {
        $PlaceRoomDevice = new PlaceRoomDevice();
        $hotel = $PlaceRoomDevice->findOne($id);
        if (empty($hotel)) {
            return ResultHelper::json(400, '设备不存在', []);
        }
        $hotel->title = $title;
        $hotel->type_id = $type_id;
        $hotel->hotel_id = $hotel_id;
        $hotel->tier_id = $tier_id;
        $hotel->room_id = $room_id;
        $hotel->project_id = $project_id;
        $hotel->mac = $mac;
        $hotel->device_id = $device_id;
        $hotel->device_type = $device_type;
        $hotel->manufactor_id = $manufactor_id;
        $hotel->displayorder = $displayorder;
        $hotel->status = $status;
        $hotel->save();
        return $hotel->toArray();
    }
    public static function delRoomDevice($mac): array
    {
        $PlaceRoomDevice = new PlaceRoomDevice();
        $isHave = $PlaceRoomDevice->find()->where(['mac' => $mac])->one();
        if (empty($isHave)) {
            return ResultHelper::json(400, '设备不存在', [
                'sql' => $PlaceRoomDevice->find()->where(['mac' => $mac])->createCommand()->getRawSql()
            ]);
        }
        try {
            $Res = $isHave->delete();
            if ($Res) {
                return ResultHelper::json(200, '删除成功');
            } else {
                $msg = ErrorsHelper::getModelError($isHave);
                return ResultHelper::json(400, $msg);
            }
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), [
                'fileLine' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), [
                'fileLine' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
    public static function deviceList($page, $pageSize, $where): array
    {
        $query = PlaceRoomDevice::find()->alias('d')->where($where)->findBloc('d');
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $list = $query->select(['d.*', 'h.name as hotel_title', 't.title as tier_title', 'r.title as room_title'])
            ->leftJoin(PlaceList::tableName() . 'h', 'd.hotel_id = h.id')
            ->leftJoin(PlaceTier::tableName() . 't', 'd.tier_id = t.id')
            ->leftJoin(PlaceRoom::tableName() . 'r', 'd.room_id = r.id')
            ->orderBy('d.displayorder desc, d.id desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        loggingHelper::writeLog('diandi_place','DeviceService/deviceList','设备信息查询',[
            'list'=>$list,
            'sql'=>$query->select(['d.*', 'h.name as hotel_title', 't.title as tier_title', 'r.title as room_title'])
                ->leftJoin(PlaceList::tableName() . 'h', 'd.hotel_id = h.id')
                ->leftJoin(PlaceTier::tableName() . 't', 'd.tier_id = t.id')
                ->leftJoin(PlaceRoom::tableName() . 'r', 'd.room_id = r.id')
                ->orderBy('d.displayorder desc, d.id desc')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->createCommand()
                ->getRawSql()
        ]);
        foreach ($list as &$value) {
            $value['device_status'] = !empty($value['device_status']) ? json_decode($value['device_status'], true) : [];
        }
        return [
            'total' => $count,
            'list' => $list,
        ];
    }
    /**
     * 房间或楼栋或单位关联设备
     * @param $hotel_type
     * @param $hotel_id
     * @param $tier_id
     * @param $room_id
     * @param $unit_id
     * @return array|string
     */
    public static function roomDevice($hotel_type, $hotel_id, $tier_id, $room_id,$unit_id): array|string
    {
        $query = PlaceRoomDevice::find()->alias('d')
            ->select(['d.*', 'h.name as hotel_title', 't.title as tier_title', 'r.title as room_title'])
            ->andWhere([
                'AND',
                ['d.hotel_type' => $hotel_type],
                ['OR', ['d.hotel_id' => $hotel_id,'d.room_id' => $room_id], ['d.tier_id' => $tier_id,'d.room_id' => $room_id], ['d.room_id' => $room_id],
                    ['d.tier_id' => $tier_id,'d.unit_id' => $unit_id]
                ],
            ])
            ->andWhere(['d.status' => DeviceStatusEnum::BINDING])
            ->leftJoin(PlaceList::tableName() . 'h', 'd.hotel_id = h.id')
            ->leftJoin(PlaceTier::tableName() . 't', 'd.tier_id = t.id')
            ->leftJoin(PlaceRoom::tableName() . 'r', 'd.room_id = r.id')
            ->orderBy('d.displayorder desc, d.id desc');
        $data = $query
            ->asArray()
            ->all();
        loggingHelper::writeLog('diandi_place','DeviceService/roomDevice', '房间关联设备', [
            'data' => $data,
            'sql' => $query->createCommand()->getRawSql()
        ]);
        $types = DeviceTypeEnum::listData();
        $paths = [
            81 => 'addBlueToothLock',
            48 => 'addSwitch',
            82 => 'addElemeter',
            7 => 'addAccessControl',
            80 => 'addGateway',
            8 => '',
        ];
        $list = [];
        foreach ($types as $k => $v) {
            if (!isset($list[$v])) {
                $list[$v] = ['id' => $k, 'list' => [], 'path' => $paths[$k]];
            }
            foreach ($data as $val) {
                $val['device_status'] = $val['device_status'] ? json_decode($val['device_status'], true) : [];
                ($k == $val['device_type']) && $list[$v]['list'][] = $val;
            }
        }
        return $list;
    }
    public static function devInfo($device_id): array
    {
        $query = PlaceRoomDevice::find()->alias('d')->where(['device_id' => $device_id])->findBloc();
        $detail = $query->select(['d.*', 'h.name as hotel_title', 't.title as tier_title', 'r.title as room_title'])
            ->leftJoin(PlaceList::tableName() . 'h', 'd.hotel_id = h.id')
            ->leftJoin(PlaceTier::tableName() . 't', 'd.tier_id = t.id')
            ->leftJoin(PlaceRoom::tableName() . 'r', 'd.room_id = r.id')
            ->orderBy('d.displayorder desc, d.id desc')
            ->asArray()
            ->one();
        $detail['device_status'] = json_decode($detail['device_status'], true);
        return $detail;
    }
}
