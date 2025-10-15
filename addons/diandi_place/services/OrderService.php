<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-14 14:40:35
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-30 16:20:08
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\OrderStatusEnums;
use addons\diandi_place\models\enums\PlaceTypeEnums;
use addons\diandi_place\models\enums\TimeTypeEnums;
use addons\diandi_place\models\member\PlaceMemberFriend;
use addons\diandi_place\models\order\PlaceOrderContract;
use addons\diandi_place\models\order\PlaceOrderList;
use addons\diandi_place\models\order\PlaceOrderMember;
use addons\diandi_place\models\order\PlaceOrderRoom;
use addons\diandi_place\models\place\PlaceLandlord;
use addons\diandi_place\models\place\PlaceType;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use DateTime;
use diandi\addons\models\BlocStore;
use yii\data\Pagination;
use yii\db\StaleObjectException;
class OrderService extends BaseService
{
    public static function createOrder($member_id, $hotel_id, $room_id, $room_num, $start_time, $end_time, $coupon_id, $discount, $amount_payable, $real_pay, $order_type, $person_ids): array
    {
        // 创建订单
        $HotelOrderList = new PlaceOrderList();
        $HotelOrderList->load([
            'start_time'   => $start_time,
            'end_time'     => $end_time,
            'room_num'     => $room_num,
            'order_number' => self::CreateOrderno(),
            'status'       => OrderStatusEnums::NOTPAY,
            'member_id'    => $member_id,
            'room_id'     => $room_id,
            'hotel_id'     => $hotel_id,
            'coupon_id'      => $coupon_id,
            'discount'      => $discount,
            'amount_payable'      => $amount_payable,
            'real_pay'      => $real_pay,
            'order_type'      => $order_type,
        ], '');
        $HotelOrderList->save();
        $order_id = $HotelOrderList->id;
        // 写入订单入住人
        $persons = PlaceMemberFriend::find()->where(['id' => $person_ids])->asArray()->all();
        unset($persons['id']);
        if ($persons) {
            loggingHelper::writeLog('diandi_place', 'createOrder', '创建订单-人员存储', [
                'persons' => $persons
            ]);
            $orderMember            = new PlaceOrderMember();
            foreach ($persons as $key => $value) {
                $value['room_id']     = $room_id;
                $value['hotel_id']     = $hotel_id;
                $value['order_id'] = $order_id;
                $_orderMember = clone $orderMember;
                loggingHelper::writeLog('diandi_place', 'createOrder', '准备写入入住人员', [
                    'value' => $value
                ]);
                $_orderMember->setAttributes($value);
                if (!$_orderMember->save()) {
                    $msg = ErrorsHelper::getModelError($_orderMember);
                    loggingHelper::writeLog('diandi_place', 'createOrder', '创建订单-人员存储错误', [
                        'msg' => $msg
                    ]);
                    return ResultHelper::json(400, $msg);
                }
            }
        }
        // 判断是否是长租
        $room = PlaceRoom::find()->where(['id' => $room_id])->asArray()->one();
        if ($room && $room['time_type'] === TimeTypeEnums::LENGTH) {
            // 生成租约签署数据
            // 读取长租协议
            $content = PlaceLandlord::find()->where(['id' => $room['landlord_id']])->select('contract')->scalar();
            $HotelOrderContract = new PlaceOrderContract();
            $HotelOrderContract->load([
                'hotel_id' => $hotel_id,
                'room_id' => $room_id,
                'order_id' => $order_id,
                'status' => 1,
                'content' => (string) $content,
                'user_sign' => 0,
                'landlord_sign' => 0
            ], '');
            if (!$HotelOrderContract->save()) {
                $msg = ErrorsHelper::getModelError($HotelOrderContract);
                return ResultHelper::json(400, $msg);
            }
        }
        // 写入房间快照数据
        unset($room['id']);
        $room['order_id'] = $order_id;
        $HotelOrderRoom = new PlaceOrderRoom();
        $HotelOrderRoom->load($room, '');
        if (!$HotelOrderRoom->save()) {
            $msg = ErrorsHelper::getModelError($HotelOrderRoom);
            return ResultHelper::json(400, $msg);
        }
        return $HotelOrderList->find()->where(['id' => $order_id])->asArray()->one();
    }
    public static function CreateOrderno(): string
    {
        return date('Ymd') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    public static function checkInById($check_in_id): array|\common\components\ActiveRecord\YiiActiveRecord|null
    {
        return PlaceOrderMember::find()->where(['id' => $check_in_id])->asArray()->one();
    }
    public static function orderNotify($order_id): array
    {
        $HotelOrderList         = PlaceOrderList::findOne($order_id);
        $HotelOrderList->status = OrderStatusEnums::ISPAY;
        try {
            $HotelOrderList->update();
            return ResultHelper::json(200,'获取成功');
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
    }
    public static function orderDetail($order_id): array|\common\components\ActiveRecord\YiiActiveRecord
    {
        $HotelOrderList = new PlaceOrderList();
        $detail = $HotelOrderList->find()->where(['id' => $order_id])->with(['checkInPerson', 'room', 'hotel', 'contract'])->asArray()->one();
        $detail['member'] = PlaceOrderMember::find()->where([
            'member_id' => $detail['member_id'],
            'order_id'  => 1,
        ])->asArray()->one();
        $member_names = array_column($detail['checkInPerson'], 'realname');
        $detail['member_names'] = implode(',', $member_names);
        try {
            $detail['diff_day'] = self::getDuringDays($detail['start_time'], $detail['end_time']);
        } catch (\Exception $e) {
            return ResultHelper::json(400,$e->getMessage());
        }
        $orderStatus = OrderStatusEnums::listData();
        $detail['status_label'] = $orderStatus[$detail['status']];
        // 返回酒店具体地址
        $store_id = $detail['hotel']['store_id'];
        $detail['room']['address'] = BlocStore::find()->where(['store_id' => $store_id])->select('address')->scalar();
        return $detail;
    }
    // 生成订单编号
    /**
     * 获取两个日期的相隔天数
     * @param string $start 开始日期
     * @param string $end 结束日期
     * @return float|int
     * @throws \Exception
     * @throws \Exception
     */
    public static function getDuringDays(string $start = '', string $end = ''): float|int
    {
        loggingHelper::writeLog('diandi_place', 'getDuringDays', '时间计算1', [$start, $end]);
        // 定义开始时间和结束时间
        $startTime = new DateTime($start);
        $endTime = new DateTime($end);
        // 利用diff函数计算两个时间之间的差异
        $diff = $endTime->diff($startTime);
        loggingHelper::writeLog('diandi_place', 'getDuringDays', '时间计算2', [$startTime, $endTime, $diff]);
        // 计算相差的晚上数量
        return $diff->days;
    }
    public static function orderList($member_id, $status = null, $page = 1, $pageSize = 10): array
    {
        $HotelOrderList = new PlaceOrderList();
        $where = [];
        if (!empty($status) && is_numeric($status)) {
            $where['status'] = $status;
        }
        $query        = $HotelOrderList->find()->where(['member_id' => $member_id])->andFilterWhere($where)->with(['checkInPerson', 'room', 'hotel', 'contract'])->orderBy(['create_time' => SORT_DESC]);
        $orderStatus = OrderStatusEnums::listData();
        $total = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $total,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $orderBy = [
            'create_time' => SORT_DESC
        ];
        $list = $query->offset($pagination->offset)
            ->orderBy($orderBy)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        foreach ($list as $key => &$value) {
            $member_names = array_column($value['checkInPerson'], 'realname');
            $value['member_names'] = implode(',', $member_names);
            try {
                $value['diff_day'] = self::getDuringDays($value['start_time'], $value['end_time']);
            } catch (\Exception $e) {
                return ResultHelper::json(400,$e->getMessage());
            }
            $value['status_label'] = $orderStatus[$value['status']];
            // 房型-房间名称-室-卫
            $value['room_desc'] = $value['room']['title'] . '/' . $value['room']['room_num'] . '室' . $value['room']['toilet_num'] . '卫' . $value['room']['bed'] . '床';
        }
        return [
            'list' => $list,
            'total' => $total,
        ];
    }
    public static function Refund($order_id): array
    {
        return ResultHelper::json(200, '获取成功');
    }
    public static function contractByMid($member_id, $status, $page, $pageSize): array
    {
        // 房东ID
        $land_id = PlaceLandlord::find()->where(['member_id' => $member_id])->select('id')->scalar();
        $where = [];
        if ($status > 0) {
            $where['status'] = $status;
        }
        $query = PlaceOrderContract::find()->where(['landlord_id' => $land_id])->andWhere($where)->with(['order', 'hotel', 'room', 'member'])->asArray();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $orderBy = [
            'create_time' => SORT_DESC
        ];
        $list = $query->offset($pagination->offset)
            ->orderBy($orderBy)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $types = PlaceType::find()->indexBy('id')->select('template_type')->asArray()->column();
        $statusList = ['', '待签约', '签约中', '已签约', '已过期'];
        foreach ($list as $key => &$value) {
            $value['status_str'] = $statusList[$value['status']];
            if ($value['hotel']) {
                $value['hotel']['type_str'] = PlaceTypeEnums::getLabel($types[$value['hotel']['type']]);
            }
        }
        return [
            'list' => $list,
            'total' => $count
        ];
    }
    public static function CancelOrder($order_id): array|bool|int
    {
        $HotelOrderList = new PlaceOrderList();
        $order = $HotelOrderList->findOne($order_id);
        if (!$order) {
            return ResultHelper::json(400, '订单不存在');
        }
        $order->status = OrderStatusEnums::CANCEL;
        try {
            return $order->update();
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400,$e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400,$e->getMessage());
        }
    }
    public static function delOrder($order_id): array|bool|int
    {
        $HotelOrderList = new PlaceOrderList();
        $order = $HotelOrderList->findOne($order_id);
        if (!$order) {
            return ResultHelper::json(400, '订单不存在');
        }
        try {
            $Res = $order->delete();
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400,$e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400,$e->getMessage());
        }
        $Room = PlaceOrderRoom::findOne(['order_id' => $order_id]);
        if ($Room) {
            try {
                $Room->delete();
            } catch (StaleObjectException $e) {
                return ResultHelper::json(400,$e->getMessage());
            } catch (\Throwable $e) {
                return ResultHelper::json(400,$e->getMessage());
            }
        }
        $Member = PlaceOrderMember::findOne(['order_id' => $order_id]);
        if ($Member) {
            try {
                $Member->delete();
            } catch (StaleObjectException $e) {
                return ResultHelper::json(400,$e->getMessage());
            } catch (\Throwable $e) {
                return ResultHelper::json(400,$e->getMessage());
            }
        }
        $Contract = PlaceOrderContract::findOne(['order_id' => $order_id]);
        if ($Contract) {
            try {
                $Contract->delete();
            } catch (StaleObjectException $e) {
                return ResultHelper::json(400,$e->getMessage());
            } catch (\Throwable $e) {
                return ResultHelper::json(400,$e->getMessage());
            }
        }
        return $Res;
    }
    public static function upContractByid($id, $member_id, $user_sign_img = '', $landlord_sign_img = ''): array|bool|int
    {
        $Con = PlaceOrderContract::findOne($id);
        if ($user_sign_img) {
            $Con->user_sign_img = $user_sign_img;
            $Con->status = 2;
            $Con->user_sign_time = date('Y-m-d H:i:s', time());
        }
        if ($landlord_sign_img) {
            $Con->landlord_sign_img = $landlord_sign_img;
            $Con->status = 2;
            $Con->landlord_sign_time = date('Y-m-d H:i:s', time());
        }
        if ($Con->user_sign_time && $Con->landlord_sign_time) {
            $Con->status = 3;
        }
        try {
            return $Con->update();
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400,$e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400,$e->getMessage());
        }
    }
}
