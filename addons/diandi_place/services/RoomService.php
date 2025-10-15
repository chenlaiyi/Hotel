<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:50:48
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-27 17:20:38
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\device\PlaceRoomDevice;
use addons\diandi_place\models\enums\LesseeEnums;
use addons\diandi_place\models\enums\RoomStatusEnums;
use addons\diandi_place\models\enums\TimeTypeEnums;
use addons\diandi_place\models\member\PlaceMemberLike;
use addons\diandi_place\models\order\PlaceOrderList;
use addons\diandi_place\models\place\PlaceComment;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceTier;
use addons\diandi_place\models\room\PlaceRoom;
use addons\diandi_place\models\room\PlaceRoomSlide;
use addons\diandi_place\models\room\PlaceRoomType;
use common\helpers\DateHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Throwable;
use Yii;
use yii\data\Pagination;
use yii\db\StaleObjectException;
class RoomService extends BaseService
{
    public static function searchList($page = 1, $pageSize = 20, $regionC_id = 0, $start_time = '', $end_time = '', $bed_children = 0, $bed_adult = 0, $bed_guest = 0, $free_cancel = 1, $hotel_types = '', $min_price = 0, $max_price = 0, $rim_ids = '', $room_num = 0, $toilet_num = 0)
    {
        $query = PlaceRoom::find()->alias('r')
            ->joinWith(['hotel as h'])
            ->joinWith(['rim as i'])
            ->joinWith(['order as o']);
        // 城市
        if ($regionC_id) {
            $query->andWhere(['h.location_c' => $regionC_id]);
        }
        // 开始时间
        if ($start_time) {
            $query->andWhere(['and', [
                '>', 'o.start_time', $start_time
            ], ['is_use' => 2]]);
        }
        // 结束时间
        if ($end_time) {
            $query->andWhere(['and', [
                '<', 'o.end_time', $end_time
            ], ['is_use' => 2]]);
        }
        // 儿童人数
        if ($bed_children) {
            $query->andWhere([
                '>=', 'r.bed_children', $bed_children
            ]);
        }
        // 成人人数
        if ($bed_adult) {
            $query->andWhere([
                '>=', 'r.bed_adult', $bed_adult
            ]);
        }
        // 客人人数
        if ($bed_guest) {
            $query->andWhere([
                '>=', 'r.bed_guest', $bed_guest
            ]);
        }
        // 可免费取消
        if ($free_cancel) {
            $query->andWhere([
                '>=', 'r.free_cancel', $free_cancel
            ]);
        }
        // 房间类型
        if ($hotel_types) {
            $query->andWhere([
                'h.type' => $hotel_types
            ]);
        }
        // 价格范围
        if ($min_price) {
            $query->andWhere([
                '>', 'r.cprice' => $min_price
            ]);
        }
        if ($max_price) {
            $query->andWhere([
                '<', 'r.cprice' => $max_price
            ]);
        }
        // 区域范围
        if ($rim_ids) {
            $query->andWhere([
                'i.id' => $rim_ids
            ]);
        }
        // 卧室
        if ($room_num) {
            $query->andWhere([
                '>', 'r.room_num' => $room_num
            ]);
        }
        // 卫生间
        if ($toilet_num) {
            $query->andWhere([
                '>', 'r.toilet_num' => $toilet_num
            ]);
        }
        $query->orderBy('cprice');
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            // 'page'=>$page-1
            // 'pageParam'=>'page'
        ]);
        $list = $query->with(['service'])->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        foreach ($list as $key => &$value) {
            $value['thumb'] = ImageHelper::tomedia($value['thumb']);
        }
        return $list;
    }
    public static function listAll($hotel_id, $tier_id, $room_pid = 0): array
    {
        $query = PlaceRoom::find()->alias('r')
            ->joinWith(['hotel as h'])
            ->joinWith(['rim as i']);
        $query->where([
            'r.hotel_id' => $hotel_id,
            'tier_id' => $tier_id,
            'room_pid' => $room_pid
        ]);
        loggingHelper::writeLog('diandi_place', 'room-server/listAll', '获取房间列表', [
            'sql' => $query->createCommand()->getRawSql()
        ]);
        $list = $query->with(['server'])->orderBy([
            'id' => SORT_DESC
        ])->asArray()->all();
        foreach ($list as &$value) {
            $value['thumb'] = ImageHelper::tomedia($value['thumb']);
        }
        return $list;
    }
    public static function RoomZzDetail($room_id): array
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $detail = PlaceRoom::find()->where(['id' => $room_id])->with(['hotel', 'rim', 'server', 'slide', 'comment'])->asArray()->one();
        if ($detail) {
            $detail['thumb'] = ImageHelper::tomedia($detail['thumb']);
            $thumbs = unserialize($detail['thumbs']);
            $detail['thumbs'] = ImageHelper::tomedia($thumbs);
            $slide = [];
            foreach ($detail['slide'] as $value) {
                $slide['type'][$value['title']] = [
                    'key' => $value['id'],
                    'value' => $value['title'],
                ];
                $slide['list'][$value['title']][] = $value;
            }
            $slide['type'] = array_values($slide['type']);
            unset($detail['slide']);
            $detail['slides'] = $slide;
            // 获取评论内容最多的4个标签
            $detail['comment_labels'] = PlaceComment::find()->where(['room_id' => $room_id])->select('labels')->limit('5')->column();
            // 猜你喜欢
            $memberView = ViewService::viewRoomByMid($member_id, $room_id);
//            foreach ($memberView as $value) {
//            }
            $detail['memberView'] = $memberView;
            // 入住须知
            $detail['instructions'] = self::CheckInInstructions($room_id);
        }
        return $detail;
    }
    public static function RoomDetail($room_id): array
    {
        $detail = PlaceRoom::find()->where(['id' => $room_id])->with(['hotel', 'rim', 'server', 'slide'])->asArray()->one();
        if ($detail) {
            $detail['thumb'] = ImageHelper::tomedia($detail['thumb']);
            $thumbs = unserialize($detail['thumbs']);
            $detail['thumbs'] = ImageHelper::tomedia($thumbs);
            // $detail['comment_count'] = ;
        }
        return $detail;
    }
    public static function LikeByMid($member_id, $room_id = 0): array
    {
        $list = PlaceMemberLike::find()->where(['member_id' => $member_id])->andWhere(['!=', 'room_id', $room_id])->with(['room'])->asArray()->all();
        return $list['room'];
    }
    /**
     * 获取可预订日期
     * @param int $room_id
     * @return array
     * @date 2023-03-26
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getTimeByRid(int $room_id): array
    {
        $time = date('Y-m-d H:i:s', time());
        // 获取已被占用日期
        return [];
//        return PlaceOrderList::find()->where(['room_id' => $room_id, 'is_use' => 1])->andWhere(['>', 'start_time', $time])->select(['start_time', 'end_time'])->asArray()->all();
    }
    /**
     * 添加房间
     * @param $title
     * @param $hotel_id
     * @param $type_id
     * @param $room_type_id
     * @param $tier_id
     * @param int $unit_id
     * @param string $thumb
     * @param string $oprice
     * @param string $cprice
     * @param string $mprice
     * @param string $thumbs
     * @param string $device
     * @param string $room_pid
     * @param int $is_suite
     * @param string $area
     * @param string $room_num
     * @param string $toilet_num
     * @param string $floor
     * @param string $bed_children
     * @param string $bed_adult
     * @param string $bed_guest
     * @param string $bed
     * @param string $cleaning_fee
     * @param string $server_fee
     * @param string $persons
     * @param string $bedadd
     * @param int $status
     * @param int $isshow
     * @param int $sales
     * @param int $displayorder
     * @param string $area_show
     * @param string $floor_show
     * @param string $smoke_show
     * @param string $bed_show
     * @param string $persons_show
     * @param string $bedadd_show
     * @param string $score
     * @param string $breakfast
     * @param string $language
     * @param string $free_cancel
     * @param string $checkIn_start
     * @param string $checkIn_end
     * @param string $cancel_start
     * @param string $cancel_end
     * @param string $out_time
     * @param string $time_length
     * @param string $time_type
     * @param string $lease_type
     * @param string $remark
     * @param array $server
     * @param array $slides
     * @param array $titles
     * @return array
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function add($title, $hotel_id, $type_id, $room_type_id, $tier_id,$unit_id = 0, $thumb = '', $oprice = '', $cprice = '', $mprice = '', $thumbs = '', $device = '', $room_pid = '', $is_suite = 0, $area = '', $room_num = '', $toilet_num = '', $floor = '', $bed_children = '', $bed_adult = '', $bed_guest = '', $bed = '', $cleaning_fee = '', $server_fee = '', $persons = '', $bedadd = '', $status = 0, $isshow = 0, $sales = 0, $displayorder = 0, $area_show = '', $floor_show = '', $smoke_show = '', $bed_show = '', $persons_show = '', $bedadd_show = '', $score = '', $breakfast = '', $language = '', $free_cancel = '', $checkIn_start = '', $checkIn_end = '', $cancel_start = '', $cancel_end = '', $out_time = '', $time_length = '', $time_type = '', $lease_type = '', $remark = '',array $server = [],array $slides = [],array $titles = []): array
    {
        // 默认整租
        $lease_type = $lease_type ?: LesseeEnums::ENTIRE_TENANCY;
        if ($room_pid) {
            // 存在room_pid 则为合租
            $room = PlaceRoom::findOne($room_pid);
            $room && $lease_type = LesseeEnums::JOINT_RENT;
        }
        $PlaceRoom = new PlaceRoom();
        $PlaceRoom->load([
            'hotel_id' => (int)$hotel_id,
            'type_id' => (int)$type_id,
            'room_type_id' => (int)$room_type_id,
            'title' => $title,
            'tier_id' => (int)$tier_id,
            'unit_id' => $unit_id,
            'thumb' => $thumb,
            'oprice' => $oprice ? number_format($oprice, 2, '.', '') : 0,
            'cprice' => $cprice ? number_format($cprice, 2, '.', '') : 0,
            'mprice' => $mprice ? number_format($mprice, 2, '.', '') : 0,
            'thumbs' => serialize($thumbs),
            'device' => $device,
            'room_pid' => (int)$room_pid,
            'is_suite' => (int)$is_suite,
            'area' => $area,
            'room_num' => $room_num,
            'toilet_num' => $toilet_num,
            'floor' => $floor,
            'bed_children' => $bed_children,
            'bed_adult' => $bed_adult,
            'bed_guest' => $bed_guest,
            'bed' => $bed,
            'cleaning_fee' => $cleaning_fee,
            'server_fee' => $server_fee,
            'persons' => $persons,
            'bedadd' => $bedadd,
            'status' => (int)$status,
            'isshow' => (int)$isshow,
            'sales' => $sales,
            'displayorder' => (int)$displayorder,
            'area_show' => (int)$area_show,
            'floor_show' => (int)$floor_show,
            'smoke_show' => (int)$smoke_show,
            'bed_show' => (int)$bed_show,
            'persons_show' => (int)$persons_show,
            'bedadd_show' => (int)$bedadd_show,
            'score' => $score,
            'breakfast' => $breakfast,
            'language' => (int)$language,
            'free_cancel' => $free_cancel,
            'checkIn_start' => $checkIn_start,
            'checkIn_end' => $checkIn_end,
            'cancel_start' => $cancel_start,
            'cancel_end' => $cancel_end,
            'out_time' => $out_time,
            'time_length' => $time_length,
            'time_type' => $time_type,
            'lease_type' => (int)$lease_type,
            'remark' => $remark
        ], '');
        if (!$PlaceRoom->save()) {
            $msg = ErrorsHelper::getModelError($PlaceRoom);
            return ResultHelper::json(400, $msg);
        } else {
            $room_id = $PlaceRoom->id;
            self::addSlide($room_id, $titles, $slides);
        }
        if ($server) {
            RoomDataServer::adds($server, $PlaceRoom->id, 0);
        }
        return $PlaceRoom->toArray();
    }
    /**
     * 添加房间
     * @param $nums
     * @param $prefix
     * @param $hotel_id
     * @param $type_id
     * @param $tier_ids
     * @param int $unit_id
     * @param string $thumb
     * @param string $oprice
     * @param string $cprice
     * @param string $mprice
     * @param string $thumbs
     * @param string $device
     * @param string $room_pid
     * @param int $is_suite
     * @param string $area
     * @param string $room_num
     * @param string $toilet_num
     * @param string $floor
     * @param string $bed_children
     * @param string $bed_adult
     * @param string $bed_guest
     * @param string $bed
     * @param string $cleaning_fee
     * @param string $server_fee
     * @param string $persons
     * @param string $bedadd
     * @param int $status
     * @param int $isshow
     * @param int $sales
     * @param int $displayorder
     * @param string $area_show
     * @param string $floor_show
     * @param string $smoke_show
     * @param string $bed_show
     * @param string $persons_show
     * @param string $bedadd_show
     * @param string $score
     * @param string $breakfast
     * @param string $language
     * @param string $free_cancel
     * @param string $checkIn_start
     * @param string $checkIn_end
     * @param string $cancel_start
     * @param string $cancel_end
     * @param string $out_time
     * @param string $time_length
     * @param string $time_type
     * @param string $lease_type
     * @return array
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function addsPlaceRoom($nums, $prefix, $hotel_id, $type_id, $tier_ids, int $unit_id = 0, string $thumb = '', $oprice = '', $cprice = '', $mprice = '', $thumbs = '', $device = '', $room_pid = '', $is_suite = 0, $area = '', $room_num = '', $toilet_num = '', $floor = '', $bed_children = '', $bed_adult = '', $bed_guest = '', $bed = '', $cleaning_fee = '', $server_fee = '', $persons = '', $bedadd = '', $status = 0, $isshow = 0, $sales = 0, $displayorder = 0, $area_show = '', $floor_show = '', $smoke_show = '', $bed_show = '', $persons_show = '', $bedadd_show = '', $score = '', $breakfast = '', $language = '', $free_cancel = '', $checkIn_start = '', $checkIn_end = '', $cancel_start = '', $cancel_end = '', $out_time = '', $time_length = '', $time_type = '', $lease_type = ''): array
    {
        $PlaceRoom = new PlaceRoom();
        $tier_ids = explode(',', $tier_ids);
        foreach ($tier_ids as $tier_id) {
            $hotelTierLastRoomTotal = $PlaceRoom->find()->where(['hotel_id' => $hotel_id, 'tier_id' => $tier_id])
                ->findBloc()->count();
            // 房间批量开始编号初始化
            $beginRoomTitle = $hotelTierLastRoomTotal;
            $_PlaceRoom = null;
            for ($i = 0; $i < $nums; $i++) {
                $_PlaceRoom = clone $PlaceRoom;
                $beginRoomTitle += 1;
                $beginRoomTitle = sprintf('%02d', $beginRoomTitle);
                $_PlaceRoom->setAttributes([
                    'hotel_id' => (int)$hotel_id,
                    'type_id' => (int)$type_id,
                    'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                    'tier_id' => (int)$tier_id,
                    'unit_id' => $unit_id,
                    'thumb' => $thumb,
                    'oprice' => number_format($oprice, 2, '.', ''),
                    'cprice' => number_format($cprice, 2, '.', ''),
                    'mprice' => number_format($mprice, 2, '.', ''),
                    'thumbs' => serialize($thumbs),
                    'device' => $device,
                    'room_pid' => (int)$room_pid,
                    'is_suite' => (int)$is_suite,
                    'area' => $area,
                    'room_num' => $room_num,
                    'toilet_num' => $toilet_num,
                    'floor' => $floor,
                    'bed_children' => $bed_children,
                    'bed_adult' => $bed_adult,
                    'bed_guest' => $bed_guest,
                    'bed' => $bed,
                    'cleaning_fee' => $cleaning_fee,
                    'server_fee' => $server_fee,
                    'persons' => $persons,
                    'bedadd' => $bedadd,
                    'status' => (int)($status ?? RoomStatusEnums::LEAVE_UNUSED),
                    'isshow' => (int)$isshow,
                    'sales' => $sales,
                    'displayorder' => (int)$displayorder,
                    'area_show' => (int)$area_show,
                    'floor_show' => (int)$floor_show,
                    'smoke_show' => (int)$smoke_show,
                    'bed_show' => (int)$bed_show,
                    'persons_show' => (int)$persons_show,
                    'bedadd_show' => (int)$bedadd_show,
                    'score' => $score,
                    'breakfast' => $breakfast,
                    'language' => (int)$language,
                    'free_cancel' => $free_cancel,
                    'checkIn_start' => $checkIn_start,
                    'checkIn_end' => $checkIn_end,
                    'cancel_start' => $cancel_start,
                    'cancel_end' => $cancel_end,
                    'out_time' => $out_time,
                    'time_length' => $time_length,
                    'time_type' => $time_type,
                    'lease_type' => LesseeEnums::ENTIRE_TENANCY  // 酒店房间都是整租
                ]);
                $_PlaceRoom->save();
                $msg = ErrorsHelper::getModelError($_PlaceRoom);
                if ($msg) {
                    return ResultHelper::json(400, $msg, []);
                }
            }
        }
        return ResultHelper::json(200, '添加房间');
    }
    /**
     * 批量添加公寓民宿房间
     * @param $nums
     * @param $prefix
     * @param $hotel_id
     * @param $type_id
     * @param $tier_ids
     * @param int $unit_id
     * @param string $thumb
     * @param string $oprice
     * @param string $cprice
     * @param string $mprice
     * @param array $thumbs
     * @param string $device
     * @param string $room_pid
     * @param int $is_suite
     * @param string $area
     * @param string $room_num
     * @param string $toilet_num
     * @param string $floor
     * @param string $bed_children
     * @param string $bed_adult
     * @param string $bed_guest
     * @param string $bed
     * @param string $cleaning_fee
     * @param string $server_fee
     * @param string $persons
     * @param string $bedadd
     * @param int $status
     * @param int $isshow
     * @param int $sales
     * @param int $displayorder
     * @param string $area_show
     * @param string $floor_show
     * @param string $smoke_show
     * @param string $bed_show
     * @param string $persons_show
     * @param string $bedadd_show
     * @param string $score
     * @param string $breakfast
     * @param string $language
     * @param string $free_cancel
     * @param string $checkIn_start
     * @param string $checkIn_end
     * @param string $cancel_start
     * @param string $cancel_end
     * @param string $out_time
     * @param string $time_length
     * @param string $time_type
     * @param int $lease_type
     * @return array
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function addsApartmentRoom($nums, $prefix, $hotel_id, $type_id, $tier_ids,$unit_id = 0, string $thumb = '', string $oprice = '', string $cprice = '', string $mprice = '', array $thumbs = [], $device = '', $room_pid = '', $is_suite = 0, $area = '', $room_num = '', $toilet_num = '', $floor = '', $bed_children = '', $bed_adult = '', $bed_guest = '', $bed = '', $cleaning_fee = '', $server_fee = '', $persons = '', $bedadd = '', $status = 0, $isshow = 0, $sales = 0, $displayorder = 0, $area_show = '', $floor_show = '', $smoke_show = '', $bed_show = '', $persons_show = '', $bedadd_show = '', $score = '', $breakfast = '', $language = '', $free_cancel = '', $checkIn_start = '', $checkIn_end = '', $cancel_start = '', $cancel_end = '', $out_time = '', $time_length = '', $time_type = '',int $lease_type = 0): array
    {
        $PlaceRoom = new PlaceRoom();
        $tier_ids = explode(',', $tier_ids);
        $msg = '';
        foreach ($tier_ids as $tier_id) {
            if ($room_pid) {
                $room_pids = explode(',', $room_pid);
                // 给楼层批量添加单位
                foreach ($room_pids as $room_pid_one) {
                    // 整租没有父级房间  合租有父级房间
                    // 楼层下是否有该单位 没有跳过
                    $isHave = PlaceRoom::find()->where([
                        'tier_id' => $tier_id,
                        'id' => $room_pid_one,
                    ])->andWhere(['<>', 'lease_type', 0])->one();
                    loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '给楼层批量添加单位', [
                        'sql' => PlaceRoom::find()->where([
                            'tier_id' => $tier_id,
                            'id' => $room_pid_one,
                        ])->andWhere(['<>', 'lease_type', 0])->createCommand()->getRawSql(),
                        'ishave' => $isHave,
                        'tier_ids' => $tier_ids,
                        'room_pids' => $room_pids,
                        'lease_type' => $lease_type
                    ]);
                    if (!$isHave) continue;
                    $hotelTierLastRoomTotal = $PlaceRoom->find()->where(['hotel_id' => $hotel_id, 'tier_id' => $tier_id])
                        ->findBloc()->count();
                    loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '单位添加1', [
                        'sql' => $PlaceRoom->find()->where(['hotel_id' => $hotel_id, 'tier_id' => $tier_id, 'type_id' => $type_id, 'room_pid' => $room_pid_one, 'lease_type' => $lease_type])
                            ->findBloc()->createCommand()->getRawSql(),
                        'count' => $hotelTierLastRoomTotal,
                        'nums' => $nums,
                    ]);
                    // 房间批量开始编号初始化
                    $beginRoomTitle = $hotelTierLastRoomTotal;
                    for ($i = 0; $i < $nums; $i++) {
                        $_PlaceRoom = clone $PlaceRoom;
                        $beginRoomTitle += 1;
                        $beginRoomTitle = sprintf('%02d', $beginRoomTitle);
                        loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '单位添加1-数据', [
                            'hotel_id' => (int)$hotel_id,
                            'type_id' => (int)$type_id,
                            'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                            'tier_id' => (int)$tier_id,
                            'unit_id' => $unit_id,
                            'thumb' => $thumb,
                            'oprice' => number_format($oprice, 2, '.', ''),
                            'cprice' => number_format($cprice, 2, '.', ''),
                            'mprice' => number_format($mprice, 2, '.', ''),
                            'thumbs' => serialize($thumbs),
                            'device' => $device,
                            'room_pid' => (int)$room_pid_one,
                            'is_suite' => (int)$is_suite,
                            'area' => $area,
                            'room_num' => $room_num,
                            'toilet_num' => $toilet_num,
                            'floor' => $floor,
                            'bed_children' => $bed_children,
                            'bed_adult' => $bed_adult,
                            'bed_guest' => $bed_guest,
                            'bed' => $bed,
                            'cleaning_fee' => $cleaning_fee,
                            'server_fee' => $server_fee,
                            'persons' => $persons,
                            'bedadd' => $bedadd,
                            'status' => (int)($status ?? RoomStatusEnums::LEAVE_UNUSED),
                            'isshow' => (int)$isshow,
                            'sales' => $sales,
                            'displayorder' => (int)$displayorder,
                            'area_show' => (int)$area_show,
                            'floor_show' => (int)$floor_show,
                            'smoke_show' => (int)$smoke_show,
                            'bed_show' => (int)$bed_show,
                            'persons_show' => (int)$persons_show,
                            'bedadd_show' => (int)$bedadd_show,
                            'score' => $score,
                            'breakfast' => $breakfast,
                            'language' => (int)$language,
                            'free_cancel' => $free_cancel,
                            'checkIn_start' => $checkIn_start,
                            'checkIn_end' => $checkIn_end,
                            'cancel_start' => $cancel_start,
                            'cancel_end' => $cancel_end,
                            'out_time' => $out_time,
                            'time_length' => $time_length,
                            'time_type' => $time_type,
                            'lease_type' => (int)$lease_type
                        ]);
                        $_PlaceRoom->setAttributes([
                            'hotel_id' => (int)$hotel_id,
                            'type_id' => (int)$type_id,
                            'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                            'tier_id' => (int)$tier_id,
                            'thumb' => $thumb,
                            'oprice' => number_format($oprice, 2, '.', ''),
                            'cprice' => number_format($cprice, 2, '.', ''),
                            'mprice' => number_format($mprice, 2, '.', ''),
                            'thumbs' => serialize($thumbs),
                            'device' => $device,
                            'room_pid' => (int)$room_pid_one,
                            'is_suite' => (int)$is_suite,
                            'area' => $area,
                            'room_num' => $room_num,
                            'toilet_num' => $toilet_num,
                            'floor' => $floor,
                            'bed_children' => $bed_children,
                            'bed_adult' => $bed_adult,
                            'bed_guest' => $bed_guest,
                            'bed' => $bed,
                            'cleaning_fee' => $cleaning_fee,
                            'server_fee' => $server_fee,
                            'persons' => $persons,
                            'bedadd' => $bedadd,
                            'status' => (int)($status ?? RoomStatusEnums::LEAVE_UNUSED),
                            'isshow' => (int)$isshow,
                            'sales' => $sales,
                            'displayorder' => (int)$displayorder,
                            'area_show' => (int)$area_show,
                            'floor_show' => (int)$floor_show,
                            'smoke_show' => (int)$smoke_show,
                            'bed_show' => (int)$bed_show,
                            'persons_show' => (int)$persons_show,
                            'bedadd_show' => (int)$bedadd_show,
                            'score' => $score,
                            'breakfast' => $breakfast,
                            'language' => (int)$language,
                            'free_cancel' => $free_cancel,
                            'checkIn_start' => $checkIn_start,
                            'checkIn_end' => $checkIn_end,
                            'cancel_start' => $cancel_start,
                            'cancel_end' => $cancel_end,
                            'out_time' => $out_time,
                            'time_length' => $time_length,
                            'time_type' => $time_type,
                            'lease_type' => $lease_type //$isHave->lease_type // 跟随父级单位的类型
                        ]);
                        if (!$_PlaceRoom->save()) {
                            $msg = ErrorsHelper::getModelError($_PlaceRoom);
                            loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '单位添加1-错误', [
                                'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                                'msg' => $msg
                            ]);
                        } else {
                            loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '单位添加1-成功', [
                                'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                                'msg' => $msg
                            ]);
                        }
                    }
                }
            } else {
                loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '独立添加单位', [
                    'sql' => PlaceRoom::find()->where([
                        'tier_id' => $tier_id,
                        'id' => $room_pid,
                    ])->andWhere(['<>', 'lease_type', 0])->createCommand()->getRawSql(),
                    'tier_ids' => $tier_ids,
                    'lease_type' => $lease_type
                ]);
                // 独立添加单位
                $hotelTierLastRoomTotal = $PlaceRoom->find()->where(['hotel_id' => $hotel_id, 'tier_id' => $tier_id])
                    ->findBloc()->count();
                // 房间批量开始编号初始化
                $beginRoomTitle = $hotelTierLastRoomTotal;
                $_PlaceRoom = null;
                for ($i = 0; $i < $nums; $i++) {
                    $_PlaceRoom = clone $PlaceRoom;
                    $beginRoomTitle += 1;
                    $beginRoomTitle = sprintf('%02d', $beginRoomTitle);
                    $_PlaceRoom->setAttributes([
                        'hotel_id' => (int)$hotel_id,
                        'type_id' => (int)$type_id,
                        'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                        'tier_id' => (int)$tier_id,
                        'thumb' => $thumb,
                        'oprice' => number_format($oprice, 2, '.', ''),
                        'cprice' => number_format($cprice, 2, '.', ''),
                        'mprice' => number_format($mprice, 2, '.', ''),
                        'thumbs' => serialize($thumbs),
                        'device' => $device,
                        'room_pid' => (int)$room_pid,
                        'is_suite' => (int)$is_suite,
                        'area' => $area,
                        'room_num' => $room_num,
                        'toilet_num' => $toilet_num,
                        'floor' => $floor,
                        'bed_children' => $bed_children,
                        'bed_adult' => $bed_adult,
                        'bed_guest' => $bed_guest,
                        'bed' => $bed,
                        'cleaning_fee' => $cleaning_fee,
                        'server_fee' => $server_fee,
                        'persons' => $persons,
                        'bedadd' => $bedadd,
                        'status' => (int)($status ?? RoomStatusEnums::LEAVE_UNUSED),
                        'isshow' => (int)$isshow,
                        'sales' => $sales,
                        'displayorder' => (int)$displayorder,
                        'area_show' => (int)$area_show,
                        'floor_show' => (int)$floor_show,
                        'smoke_show' => (int)$smoke_show,
                        'bed_show' => (int)$bed_show,
                        'persons_show' => (int)$persons_show,
                        'bedadd_show' => (int)$bedadd_show,
                        'score' => $score,
                        'breakfast' => $breakfast,
                        'language' => (int)$language,
                        'free_cancel' => $free_cancel,
                        'checkIn_start' => $checkIn_start,
                        'checkIn_end' => $checkIn_end,
                        'cancel_start' => $cancel_start,
                        'cancel_end' => $cancel_end,
                        'out_time' => $out_time,
                        'time_length' => $time_length,
                        'time_type' => $time_type,
                        'lease_type' => $lease_type, // LesseeEnums::ENTIRE_TENANCY  // 酒店房间都是整租
                    ]);
                    if (!$_PlaceRoom->save()) {
                        $msg = ErrorsHelper::getModelError($_PlaceRoom);
                        loggingHelper::writeLog('diandi_place', 'addsApartmentRoom', '单位添加2-错误', [
                            'title' => $prefix ? $prefix . $beginRoomTitle : $beginRoomTitle,
                            'msg' => $msg
                        ]);
                    }
                }
            }
        }
        if ($msg) {
            return ResultHelper::json(400, $msg);
        }
        return [];
    }
    /**
     * 修改房间信息
     * @param $id
     * @param $title
     * @param $hotel_id
     * @param $type_id
     * @param $tier_id
     * @param int $unit_id
     * @param string $thumb
     * @param string $oprice
     * @param string $cprice
     * @param string $mprice
     * @param string $thumbs
     * @param string $device
     * @param string $room_pid
     * @param int $is_suite
     * @param string $area
     * @param string $room_num
     * @param string $toilet_num
     * @param string $floor
     * @param string $bed_children
     * @param string $bed_adult
     * @param string $bed_guest
     * @param string $bed
     * @param string $cleaning_fee
     * @param string $server_fee
     * @param string $persons
     * @param string $bedadd
     * @param int $status
     * @param int $isshow
     * @param int $sales
     * @param int $displayorder
     * @param string $area_show
     * @param string $floor_show
     * @param string $smoke_show
     * @param string $bed_show
     * @param string $persons_show
     * @param string $bedadd_show
     * @param string $score
     * @param string $breakfast
     * @param string $language
     * @param string $free_cancel
     * @param string $checkIn_start
     * @param string $checkIn_end
     * @param string $cancel_start
     * @param string $cancel_end
     * @param string $out_time
     * @param string $time_length
     * @param string $time_type
     * @param int $lease_type
     * @param string $remark
     * @param array $server
     * @param int $room_type_id
     * @param array $titles
     * @param array $slides
     * @return array|object[]|string[]
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function edit($id, $title, $hotel_id, $type_id, $tier_id,$unit_id = 0, $thumb = '', $oprice = '', $cprice = '', $mprice = '', $thumbs = '', $device = '', $room_pid = '', $is_suite = 0, $area = '', $room_num = '', $toilet_num = '', $floor = '', $bed_children = '', $bed_adult = '', $bed_guest = '', $bed = '', $cleaning_fee = '', $server_fee = '', $persons = '', $bedadd = '', $status = 0, $isshow = 0, $sales = 0, $displayorder = 0, $area_show = '', $floor_show = '', $smoke_show = '', $bed_show = '', $persons_show = '', $bedadd_show = '', $score = '', $breakfast = '', $language = '', $free_cancel = '', $checkIn_start = '', $checkIn_end = '', $cancel_start = '', $cancel_end = '', $out_time = '', $time_length = '', $time_type = '',int $lease_type = 0, $remark = '', array $server = [], $room_type_id = 0, array $titles = [], array $slides = [])
    {
        $room = PlaceRoom::findOne($id);
        if (empty($room)) {
            return ResultHelper::json(400, '房间不存在');
        }
        // 不允许编辑
        // $room->type_id       = (int)$type_id;    房源
        // $room->hotel_id      = (int)$hotel_id;   楼栋
        // $room->tier_id       = (int)$tier_id;    楼层
        // $room->room_pid      = (int)$room_pid;   单位(合租房间时才会有)
        if ($thumbs) {
            $thumbs = is_string($thumbs)? explode(',', $thumbs):$thumbs;
            $thumbs = serialize($thumbs);
            $room->thumbs = $thumbs;
        }
        $room->lease_type    = $lease_type; //0整租 1合租
        $room->title = $title;
        $room->thumb = $thumb;
        $room->oprice = number_format($oprice, 2, '.', '');
        $room->cprice = number_format($cprice, 2, '.', '');
        $room->mprice = number_format($mprice, 2, '.', '');
        $room->device = $device;
        $room->is_suite = (int)$is_suite;
        $room->area = $area;
        $room->unit_id = $unit_id;
        $room->room_num = (int)$room_num;
        $room->toilet_num = $toilet_num;
        $room->floor = $floor;
        $room->bed_children = $bed_children;
        $room->bed_adult = $bed_adult;
        $room->bed_guest = $bed_guest;
        $room->bed = $bed;
        $room->cleaning_fee = $cleaning_fee;
        $room->server_fee = $server_fee;
        $room->persons = $persons;
        $room->bedadd = $bedadd;
        $room->status = (int)$status;
        $room->isshow = (int)$isshow;
        $room->sales = $sales;
        $room->displayorder = (int)$displayorder;
        $room->area_show = (int)$area_show;
        $room->floor_show = (int)$floor_show;
        $room->smoke_show = (int)$smoke_show;
        $room->bed_show = (int)$bed_show;
        $room->persons_show = (int)$persons_show;
        $room->bedadd_show = (int)$bedadd_show;
        $room->score = $score;
        $room->breakfast = $breakfast;
        $room->language = (int)$language;
        $room->free_cancel = $free_cancel;
        $room->checkin_start = $checkIn_start;
        $room->checkin_end = $checkIn_end;
        $room->cancel_start = $cancel_start;
        $room->cancel_end = $cancel_end;
        $room->out_time = $out_time;
        $room->time_length = $time_length;
        $room->time_type = $time_type;
        $room->remark = $remark;
        $room->room_type_id = $room_type_id;
        if (!$room->save()) {
            $msg = ErrorsHelper::getModelError($room);
            return ResultHelper::json(400, $msg);
        } else {
            $room_id = $room->id;
            RoomDataServer::addOrDel($server, 0, $room->id);
            self::editSlide($room_id, $titles, $slides);
        }
        return $room->toArray();
    }
    public static function IndexStatistics()
    {
        $enum = RoomStatusEnums::listData();
        $stat = PlaceRoom::find()->findBloc()
            ->select(['count(*) room_num', 'status'])
            ->andWhere([
                'OR',
                ['AND', ['room_pid' => 0], ['lease_type' => 0]],    // 酒店房间
                ['AND', ['room_pid' => 0], ['lease_type' => LesseeEnums::ENTIRE_TENANCY]], // 公寓民宿整租
                ['AND', ['<>', 'room_pid', 0], ['lease_type' => LesseeEnums::JOINT_RENT]]  // 公寓民宿合租
            ])
            ->groupBy('status')->indexBy('status')->asArray()->all();
        loggingHelper::writeLog('diandi_place', 'IndexStatistics', '酒店首页统计', [
            'stat' => $stat,
            'sql' => PlaceRoom::find()->findBloc()
                ->select(['count(*) room_num', 'status'])
                ->andWhere([
                    'OR',
                    ['AND', ['room_pid' => 0], ['lease_type' => 0]],    // 酒店房间
                    ['AND', ['room_pid' => 0], ['lease_type' => LesseeEnums::ENTIRE_TENANCY]], // 公寓民宿整租
                    ['AND', ['<>', 'room_pid', 0], ['lease_type' => LesseeEnums::JOINT_RENT]]  // 公寓民宿合租
                ])
                ->groupBy('status')->indexBy('status')->createCommand()->getRawSql(),
        ]);
        $list = [];
        foreach ($enum as $k => $v) {
            $list[$k] = [
                'status' => $k,
                'desc' => $v,
                'room_num' => $stat[$k]['room_num'] ?? 0,
            ];
        }
        return $list;
    }
    /**
     * 删除房间
     * @param int $id
     * @return array
     * @throws StaleObjectException
     * @throws Throwable
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function del(int $id): array
    {
        if (empty($id)) {
            return ResultHelper::json(400, '房间不存在', []);
        }
        $isHave = PlaceRoom::findOne($id);
        if (!$isHave) {
            return ResultHelper::json(400, '房间不存在', []);
        }
        if ($isHave->status != RoomStatusEnums::LEAVE_UNUSED) {
            return ResultHelper::json(400, '房间未闲置,不可删除', []);
        }
        if ($isHave->delete()){
            return ResultHelper::json(200, '删除成功');
        }else{
            return ResultHelper::json(400, '删除失败');
        }
    }
    public static function situation(int $room_type_id, int $status, int $page, int $pageSize)
    {
        $roomStatusEnum = RoomStatusEnums::listData();
        if (!isset($roomStatusEnum[$status])) {
            return ['total' => 0, 'list' => []];
        }
        $query = PlaceRoom::find()->alias('hr')
            ->select([
                'hr.id room_id', 'hr.title AS room_title', 'hr.status', 'hr.time_type',
                'ty.title as type_title', 'hl.name AS hotel_title', 'ht.title AS tier_title', 'phr.title AS p_room_title',
            ])
            ->leftJoin(HotelType::tableName() . 'as ty', 'hr.type_id = ty.id')
            ->leftJoin(PlaceTier::tableName() . 'as ht', 'hr.tier_id = ht.id')
            ->leftJoin(PlaceList::tableName() . 'as hl', 'hr.hotel_id = hl.id')
            ->leftJoin(PlaceRoom::tableName() . 'as phr', 'hr.room_pid = phr.id')
            ->findBloc('hr')->andWhere(['hr.status' => $status])
            ->andWhere(['hr.type_id' => $room_type_id])
            ->andWhere([
                'OR',
                ['AND', ['hr.room_pid' => 0], ['hr.lease_type' => 0]],    // 酒店房间
                ['AND', ['hr.room_pid' => 0], ['hr.lease_type' => LesseeEnums::ENTIRE_TENANCY]], // 公寓民宿整租
                ['AND', ['<>', 'hr.room_pid', 0], ['hr.lease_type' => LesseeEnums::JOINT_RENT]]  // 公寓民宿合租
            ]);
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $timeTypeEnum = TimeTypeEnums::listData();
        foreach ($list as &$v) {
            $v['time_type'] = $timeTypeEnum[$v['time_type']];
            $v['status'] = $roomStatusEnum[$v['status']];
        }
        return [
            'total' => $count,
            'list' => $list,
            // 'sql'  => $query->createCommand()->getRawSql(),
        ];
    }
    /**
     * 房间详情
     * @param int $id
     * @return array|\common\components\ActiveRecord\YiiActiveRecord
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function detail(int $id)
    {
        $detail = PlaceRoom::find()->alias('hr')
            ->with(['server'])
            ->select(['hr.*', 'hl.name hotel_title', 'ht.title tier_title', 'rt.title room_type_title'])
            ->leftJoin(PlaceTier::tableName() . 'as ht', 'hr.tier_id = ht.id')
            ->leftJoin(PlaceList::tableName() . 'as hl', 'hr.hotel_id = hl.id')
            ->leftJoin(PlaceRoom::tableName() . 'as phr', 'hr.room_pid = phr.id')
            ->leftJoin(PlaceRoomType::tableName() . 'as rt', 'hr.room_type_id = rt.id')
            ->where(['hr.id' => $id])
            ->asArray()
            ->one();
        $slides = PlaceRoomSlide::find()->where(['room_id' => $id])->findBloc()->select(['slide', 'title'])->asArray()->all();
        $listSlides = [];
        foreach ($slides as $key => $value) {
            $listSlides[$value['title']]['title'] = $value['title'];
            $listSlides[$value['title']]['slide'][] = ImageHelper::tomedia($value['slide']);
        }
        $detail['slides'] = array_values($listSlides);
        $detail['thumb'] = ImageHelper::tomedia($detail['thumb']);
        if ($detail['thumbs']) {
            $thumbs = unserialize($detail['thumbs']) ?: [];
            $detail['thumbs'] = ImageHelper::tomedia($thumbs);
        }
        /**
         * 获取默认的门锁
         */
        $detail['lockMac'] = PlaceRoomDevice::find()->where(['room_id' => $id,'is_default'=>1])->select('mac')->scalar();
        return $detail;
    }
    /**
     * 房间相册新增
     * @param int $room_id
     * @param string $title
     * @param array $slides
     * @return void
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function addSlide(int $room_id, array $titles, array $slides)
    {
        $PlaceRoomSlide = new PlaceRoomSlide();
        $PlaceRoomSlide->deleteAll([
            'room_id' => $room_id
        ]);
        foreach ($slides as $key => $slideItems) {
            $slide = explode(',', $slideItems);
            foreach ($slide as $k => $value) {
                $_PlaceRoomSlide = clone $PlaceRoomSlide;
                $_PlaceRoomSlide->setAttributes([
                    'slide' => $value,
                    'room_id' => $room_id,
                    'title' => $titles[$key]
                ]);
                $_PlaceRoomSlide->save();
            }
        }
    }
    /**
     * 房间相册修改
     * @param int $room_id
     * @param array $titles
     * @param array $slides
     * @return void
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function editSlide(int $room_id, array $titles, array $slides)
    {
        $PlaceRoomSlide = new PlaceRoomSlide();
        $PlaceRoomSlide->deleteAll([
            'room_id' => $room_id
        ]);
        foreach ($slides as $key => $slideItems) {
            $slide = explode(',', $slideItems);
            foreach ($slide as $k => $value) {
                $_PlaceRoomSlide = clone $PlaceRoomSlide;
                $_PlaceRoomSlide->setAttributes([
                    'slide' => $value,
                    'room_id' => $room_id,
                    'title' => $titles[$key]
                ]);
                $_PlaceRoomSlide->save();
            }
        }
    }
    /**
     * 获取用户是否收藏当前房间
     * @param $room_id
     * @param $member_id
     * @return bool
     * @date 2023-06-02
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function memberLike($room_id, $member_id): bool
    {
        return PlaceMemberLike::find()->where(['room_id' => $room_id, 'member_id' => $member_id])->exists();
    }
    public static function roomStatusInit(int $hotel_id, $start_time = ''): array
    {
        // 获取近一周日期
        $xAxis = $start_time ? strtotime($start_time) : strtotime("-1 day");
        $times = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $xAxis + $i * 24 * 60 * 60;
            $day = DateHelper::intToDate($date, 'd');
            $times[] = [
                'day' => $day,
                'week' => DateHelper::getWeekName($date)
            ];
        }
        $roomStatusEnum = RoomStatusEnums::listData();
        $query = PlaceRoom::find()
            ->alias('r')
            ->joinWith(['roomStatus as orm'])
            ->select([
                'r.title',
                'DAY(orm.roomdate) AS day',
                'r.id as rid',
                'orm.room_status'
            ])
            ->where(['between', 'orm.roomdate', date('Y-m-d', $xAxis), date('Y-m-d', $xAxis + 7 * 24 * 60 * 60)])
            ->andWhere(['orm.hotel_id' => $hotel_id])
            ->groupBy(['rid', 'day']);
        $list = $query
            ->asArray()
            ->all();
        $lists = [];
        $color = [
            '#E4F7F3',
            '#FCE5E0',
            '#FCC545',
            '#64D6BA',
            '#FC785D',
            '#999'
        ];
        foreach ($list as $item) {
            $lists[$item['rid']]['room_title'] = $item['title'];
            foreach ($times as $time) {
                if ((int)$item['day'] === (int)$time['day']) {
                    $lists[$item['rid']]['status'][$time['day']] = $color[$item['room_status']];
                } else {
                    $lists[$item['rid']]['status'][$time['day']] = '';
                }
            }
        }
        $labelList = [];
        foreach ($roomStatusEnum as $key => $item) {
            $labelList[] = [
                'title' => $item,
                'color' => $color[$key]
            ];
        }
        return [
            'times' => $times,
            'sql' => $query->createCommand()->getRawSql(),
            'roomStatusEnum' => $roomStatusEnum,
            'labelList' => $labelList,
            'list' => array_values($lists)
        ];
    }
    public static function CheckInInstructions($hotel_id)
    {
        $Instructions = [
            [
                'title' => '入住时间',
                'content' => '下午3:00-下午6:00',
            ],
            [
                'title' => '退房时间',
                'content' => '上午11:00',
            ],
            [
                'title' => '独立时间',
                'content' => '有自己独立的房间，与其他人共享其它空间',
            ],
            [
                'title' => '自助入住',
                'content' => '通过大楼工作人员自助入住',
            ],
            [
                'title' => '吸烟：',
                'content' => '禁止吸烟',
            ],
            [
                'title' => '取消政策',
                'content' => '30分钟内免费取消',
            ],
        ];
        return $Instructions;
    }
}
