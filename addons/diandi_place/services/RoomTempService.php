<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 15:35:18
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\OrderMemberStatusEnums;
use addons\diandi_place\models\enums\OrderStatusEnums;
use addons\diandi_place\models\enums\RoomStatusEnums;
use addons\diandi_place\models\order\PlaceOrderList;
use addons\diandi_place\models\order\PlaceOrderMember;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\DateHelper;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Throwable;
use yii\db\StaleObjectException;
class RoomTempService extends BaseService
{
    public static function occupancy(int $hotel_id, int $room_id, string $start_time, string $end_time, array $persons): array
    {
        // 是否设置房间可容纳人数
        $room = PlaceRoom::find()->where(['id' => $room_id])->findBloc()->one();
        $maxNum = $room->persons;
        $num = count($persons);
        if (empty($maxNum)) {
            return ResultHelper::json(400, '请先设置最房间多容纳人数', [
                'errCode'=>1
            ]);
        }
        $OrderMember = new PlaceOrderMember();
        // 房间是否已出租
        $isRentOut = ($room->status == RoomStatusEnums::RENT_OUT);
        if ($isRentOut) {
            // 是否超出订单时间范围
            // if (strtotime($start_time) < strtotime($order->start_time) || strtotime($end_time) > strtotime($order->end_time)) {
            //     return ResultHelper::json(400, '超出订单时间范围,请重新选择时间', []);
            // }
            // 已入住人数 (不包含退房房客)
            $num += $OrderMember->find()->findBloc()
                ->andWhere(['room_id' => $room_id])
                ->andWhere(['<>', 'status', OrderMemberStatusEnums::OUT])
                ->count();
        }
        // 人数是否超过最大容纳人数
        if ($num > $maxNum) {
            return ResultHelper::json(400, '人员超过房间可容纳最大数量，操作失败',[
                'errCode'=>2
            ]);
        }
        $start_time = DateHelper::intToDate(strtotime($start_time));
        $end_time = DateHelper::intToDate(strtotime($end_time));
        // 房间未出租 创建订单
        if (!$isRentOut) {
            //不创建订单，后期增加对外处理标准
            $room->status = RoomStatusEnums::RENT_OUT;
            $room->save();
        }
        // 添加临时入住人员
        if ($persons) {
            loggingHelper::writeLog('diandi_place', 'occupancy', '临时入住人员', $persons);
            foreach ($persons as $v) {
                $_OrderMember = clone $OrderMember;
                $data = [
                    'hotel_id'=>$hotel_id,
                    'room_id'=>$room_id,
                    'mobile' => $v['mobile'],
                    'realname' => $v['realname'],
                    'icard_code' => $v['icard_code'] ?? '',
//                    'allow_add_key' => (int)$v['allow_add_key'],
                    'notice' => (int)$v['notice'],
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'status' => OrderMemberStatusEnums::NORMAL,
                    'member_id' => 0,
                    'check_in' => 0,
                ];
                $_OrderMember->setAttributes($data);
                if (!$_OrderMember->save()) {
                    $msg = ErrorsHelper::getModelError($_OrderMember);
                    loggingHelper::writeLog('diandi_place', 'occupancy', '临时入住人员-错误', [
                        'msg' => $msg,
                        'data' => $data
                    ]);
                }
            }
        }else{
            return ResultHelper::json(400, '缺少临时入住人员信息',[
                'errCode'=>3
            ]);
        }
        return [];
    }
    /**
     * 房客列表
     * @param int $room_id
     * @param string $realname
     * @return array
     */
    public static function personList(int $room_id,string $realname = ''): array
    {
        loggingHelper::writeLog('diandi_place','personList','获取房客',[
            'room_id'=> $room_id
        ]);
        $where = [];
        if (!empty($room_id)){
            $where['room_id'] = $room_id;
        }
        return PlaceOrderMember::find()->findBloc()
            ->andWhere($where)
            ->andFilterWhere(['like','realname',$realname])
            ->andWhere(['<>', 'status', OrderMemberStatusEnums::OUT])
            ->asArray()->all();
    }
    public static function frozen(int $id)
    {
        $orderMember = PlaceOrderMember::findOne($id);
        if (empty($orderMember)) {
            return ResultHelper::json(400, '房客不存在');
        }
        $orderMember->status = OrderMemberStatusEnums::FROZEN;
        $orderMember->save();
        return $orderMember;
    }
    public static function outRoomAll(int $room_id): array
    {
        try {
            $list = self::personList($room_id);
            foreach ($list as $item) {
                self::outRoom($item['id']);
            }
            return ResultHelper::json(200, '全部退房成功');
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }
    public static function outRoom(int $id): array
    {
        $orderMember = PlaceOrderMember::findOne($id);
        if (empty($orderMember)) {
            return ResultHelper::json(400, '房客不存在', []);
        }
        $orderMember->status = OrderMemberStatusEnums::OUT;
        // 修改订单状态
        $HotelOrderList = new PlaceOrderList();
        $order = $HotelOrderList->findOne($orderMember->order_id);
        $HotelOrderList->status = OrderStatusEnums::END;
        // 修改房间状态
        $PlaceRoom = new PlaceRoom();
        $room = $PlaceRoom->findOne($order->room_id);
        $room->status = RoomStatusEnums::LEAVE_UNUSED;
        try {
            $orderMember->update();
            $order->update();
            $room->update();
            return ResultHelper::json(200, '退房成功');
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }
}
