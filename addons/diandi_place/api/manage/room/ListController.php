<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:37:34
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-27 14:11:44
 */
namespace addons\diandi_place\api\manage\room;
use addons\diandi_place\models\enums\RoomStatusEnums;
use addons\diandi_place\services\PlaceService;
use addons\diandi_place\services\RoomService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 房间管理
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class ListController extends AController
{
    use LandlordTrait;
    protected array $authOptional = [];
    /**
     * 首页统计
     * @return array|null
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionIndexStatistics(): ?array
    {
        $REs = RoomService::IndexStatistics();
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 单个添加
     * @return array|null
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdd(): ?array
    {
        $hotel_id = Yii::$app->request->input('hotel_id', 0);
        $type_id = Yii::$app->request->input('type_id') ?? 0;
        $title = Yii::$app->request->input('title') ?? '';
        $tier_id = Yii::$app->request->input('tier_id');
        $thumb = Yii::$app->request->input('thumb');
        $oprice = Yii::$app->request->input('oprice');
        $cprice = Yii::$app->request->input('cprice');
        $mprice = Yii::$app->request->input('mprice');
        $thumbs = Yii::$app->request->input('thumbs');
        $device = Yii::$app->request->input('device');
        $room_pid = Yii::$app->request->input('room_pid');
        $is_suite = Yii::$app->request->input('is_suite');
        $area = Yii::$app->request->input('area');
        $room_num = Yii::$app->request->input('room_num');
        $toilet_num = Yii::$app->request->input('toilet_num');
        $floor = Yii::$app->request->input('floor');
        $bed_children = Yii::$app->request->input('bed_children');
        $bed_adult = Yii::$app->request->input('bed_adult');
        $bed_guest = Yii::$app->request->input('bed_guest');
        $bed = Yii::$app->request->input('bed');
        $cleaning_fee = Yii::$app->request->input('cleaning_fee');
        $server_fee = Yii::$app->request->input('server_fee');
        $persons = Yii::$app->request->input('persons');
        $bedadd = Yii::$app->request->input('bedadd');
        $status = Yii::$app->request->input('status') ?? RoomStatusEnums::LEAVE_UNUSED; // 房间初始状态为闲置
        $isshow = Yii::$app->request->input('isshow');
        $sales = Yii::$app->request->input('sales');
        $displayorder = Yii::$app->request->input('displayorder');
        $area_show = Yii::$app->request->input('area_show');
        $floor_show = Yii::$app->request->input('floor_show');
        $smoke_show = Yii::$app->request->input('smoke_show');
        $bed_show = Yii::$app->request->input('bed_show');
        $persons_show = Yii::$app->request->input('persons_show');
        $bedadd_show = Yii::$app->request->input('bedadd_show');
        $score = Yii::$app->request->input('score');
        $breakfast = Yii::$app->request->input('breakfast');
        $lanuage = Yii::$app->request->input('lanuage');
        $free_cancel = Yii::$app->request->input('free_cancel');
        $checkIn_start = Yii::$app->request->input('checkIn_start');
        $checkIn_end = Yii::$app->request->input('checkIn_end');
        $cancel_start = Yii::$app->request->input('cancel_start');
        $cancel_end = Yii::$app->request->input('cancel_end');
        $out_time = Yii::$app->request->input('out_time');
        $time_length = Yii::$app->request->input('time_length');
        $time_type = Yii::$app->request->input('time_type');
        $lease_type = Yii::$app->request->input('lease_type');
        $remark = Yii::$app->request->input('remark');
        $server = Yii::$app->request->input('server',[]);
        $room_type_id = Yii::$app->request->input('room_type_id');
        $titles = (array)(Yii::$app->request->input('titles'));
        $slides = (array) (Yii::$app->request->input('slides'));
        $unit_id= Yii::$app->request->input('unit_id');
        $REs = RoomService::add($title, $hotel_id, $type_id,$room_type_id, $tier_id,$unit_id, $thumb, $oprice, $cprice, $mprice, $thumbs, $device, $room_pid, $is_suite, $area, $room_num, $toilet_num, $floor, $bed_children, $bed_adult, $bed_guest, $bed, $cleaning_fee, $server_fee, $persons, $bedadd, $status, $isshow, $sales, $displayorder, $area_show, $floor_show, $smoke_show, $bed_show, $persons_show, $bedadd_show, $score, $breakfast, $lanuage, $free_cancel, $checkIn_start, $checkIn_end, $cancel_start, $cancel_end, $out_time, $time_length, $time_type, $lease_type, $remark, $server, $slides, $titles);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    /**
     * 批量添加
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdds()
    {
        $nums = Yii::$app->request->input('nums');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $type_id = Yii::$app->request->input('type_id');
        $prefix = Yii::$app->request->input('prefix');
        $tier_id = Yii::$app->request->input('tier_id');
        $thumb = Yii::$app->request->input('thumb');
        $oprice = Yii::$app->request->input('oprice');
        $cprice = Yii::$app->request->input('cprice');
        $mprice = Yii::$app->request->input('mprice');
        $thumbs = Yii::$app->request->input('thumbs');
        $device = Yii::$app->request->input('device');
        $room_pid = Yii::$app->request->input('room_pid');
        $is_suite = Yii::$app->request->input('is_suite');
        $area = Yii::$app->request->input('area');
        $room_num = Yii::$app->request->input('room_num');
        $toilet_num = Yii::$app->request->input('toilet_num');
        $floor = Yii::$app->request->input('floor');
        $bed_children = Yii::$app->request->input('bed_children');
        $bed_adult = Yii::$app->request->input('bed_adult');
        $bed_guest = Yii::$app->request->input('bed_guest');
        $bed = Yii::$app->request->input('bed');
        $cleaning_fee = Yii::$app->request->input('cleaning_fee');
        $server_fee = Yii::$app->request->input('server_fee');
        $persons = Yii::$app->request->input('persons');
        $bedadd = Yii::$app->request->input('bedadd');
        $status = Yii::$app->request->input('status');
        $isshow = Yii::$app->request->input('isshow');
        $sales = Yii::$app->request->input('sales');
        $displayorder = Yii::$app->request->input('displayorder');
        $area_show = Yii::$app->request->input('area_show');
        $floor_show = Yii::$app->request->input('floor_show');
        $smoke_show = Yii::$app->request->input('smoke_show');
        $bed_show = Yii::$app->request->input('bed_show');
        $persons_show = Yii::$app->request->input('persons_show');
        $bedadd_show = Yii::$app->request->input('bedadd_show');
        $score = Yii::$app->request->input('score');
        $breakfast = Yii::$app->request->input('breakfast');
        $lanuage = Yii::$app->request->input('lanuage');
        $free_cancel = Yii::$app->request->input('free_cancel');
        $checkIn_start = Yii::$app->request->input('checkIn_start');
        $checkIn_end = Yii::$app->request->input('checkIn_end');
        $cancel_start = Yii::$app->request->input('cancel_start');
        $cancel_end = Yii::$app->request->input('cancel_end');
        $out_time = Yii::$app->request->input('out_time');
        $time_length = Yii::$app->request->input('time_length');
        $time_type = Yii::$app->request->input('time_type');
        $lease_type = (int)Yii::$app->request->input('lease_type');
        $unit_id= Yii::$app->request->input('unit_id');
        // 根据type_id 区分是酒店还是公寓 找的template_type  1为酒店，其他都是公寓类型
        if (PlaceService::isHotelByTypeId($type_id)) {
            // 酒店房间批量添加
            $REs = RoomService::addsPlaceRoom($nums, $prefix, $hotel_id, $type_id, $tier_id,$unit_id, $thumb, $oprice, $cprice, $mprice, $thumbs, $device, $room_pid, $is_suite, $area, $room_num, $toilet_num, $floor, $bed_children, $bed_adult, $bed_guest, $bed, $cleaning_fee, $server_fee, $persons, $bedadd, $status, $isshow, $sales, $displayorder, $area_show, $floor_show, $smoke_show, $bed_show, $persons_show, $bedadd_show, $score, $breakfast, $lanuage, $free_cancel, $checkIn_start, $checkIn_end, $cancel_start, $cancel_end, $out_time, $time_length, $time_type, $lease_type);
        } else {
            // 公寓民宿房间批量添加
            $REs = RoomService::addsApartmentRoom($nums, $prefix, $hotel_id, $type_id, $tier_id,$unit_id, $thumb, $oprice, $cprice, $mprice, $thumbs, $device, $room_pid, $is_suite, $area, $room_num, $toilet_num, $floor, $bed_children, $bed_adult, $bed_guest, $bed, $cleaning_fee, $server_fee, $persons, $bedadd, $status, $isshow, $sales, $displayorder, $area_show, $floor_show, $smoke_show, $bed_show, $persons_show, $bedadd_show, $score, $breakfast, $lanuage, $free_cancel, $checkIn_start, $checkIn_end, $cancel_start, $cancel_end, $out_time, $time_length, $time_type, $lease_type);
        }
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionEdit(): array
    {
        $id = Yii::$app->request->input('id');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $type_id = Yii::$app->request->input('type_id');
        $title = Yii::$app->request->input('title');
        $tier_id = Yii::$app->request->input('tier_id');
        $thumb = Yii::$app->request->input('thumb');
        $oprice = Yii::$app->request->input('oprice');
        $cprice = Yii::$app->request->input('cprice');
        $mprice = Yii::$app->request->input('mprice');
        $thumbs = Yii::$app->request->input('thumbs');
        $device = Yii::$app->request->input('device');
        $room_pid = Yii::$app->request->input('room_pid');
        $is_suite = Yii::$app->request->input('is_suite');
        $area = Yii::$app->request->input('area');
        $room_num = Yii::$app->request->input('room_num');
        $toilet_num = Yii::$app->request->input('toilet_num');
        $floor = Yii::$app->request->input('floor');
        $bed_children = Yii::$app->request->input('bed_children');
        $bed_adult = Yii::$app->request->input('bed_adult');
        $bed_guest = Yii::$app->request->input('bed_guest');
        $bed = Yii::$app->request->input('bed');
        $cleaning_fee = Yii::$app->request->input('cleaning_fee');
        $server_fee = Yii::$app->request->input('server_fee');
        $persons = Yii::$app->request->input('persons');
        $bedadd = Yii::$app->request->input('bedadd');
        $status = Yii::$app->request->input('status');
        $isshow = Yii::$app->request->input('isshow');
        $sales = Yii::$app->request->input('sales');
        $displayorder = Yii::$app->request->input('displayorder');
        $area_show = Yii::$app->request->input('area_show');
        $floor_show = Yii::$app->request->input('floor_show');
        $smoke_show = Yii::$app->request->input('smoke_show');
        $bed_show = Yii::$app->request->input('bed_show');
        $persons_show = Yii::$app->request->input('persons_show');
        $bedadd_show = Yii::$app->request->input('bedadd_show');
        $score = Yii::$app->request->input('score');
        $breakfast = Yii::$app->request->input('breakfast');
        $lanuage = Yii::$app->request->input('lanuage');
        $free_cancel = Yii::$app->request->input('free_cancel');
        $checkIn_start = Yii::$app->request->input('checkIn_start');
        $checkIn_end = Yii::$app->request->input('checkIn_end');
        $cancel_start = Yii::$app->request->input('cancel_start');
        $cancel_end = Yii::$app->request->input('cancel_end');
        $out_time = Yii::$app->request->input('out_time');
        $time_length = Yii::$app->request->input('time_length');
        $time_type = Yii::$app->request->input('time_type');
        $lease_type = Yii::$app->request->input('lease_type');
        $remark = Yii::$app->request->input('remark');
        $server = (array) (Yii::$app->request->input('server'));
        $room_type_id = Yii::$app->request->input('room_type_id');
        $titles = (array)(Yii::$app->request->input('titles'));
        $slides = (array)(Yii::$app->request->input('slides'));
        $unit_id= Yii::$app->request->input('unit_id');
        $REs = RoomService::edit($id, $title, $hotel_id, $type_id, $tier_id,$unit_id, $thumb, $oprice, $cprice, $mprice, $thumbs, $device, $room_pid, $is_suite, $area, $room_num, $toilet_num, $floor, $bed_children, $bed_adult, $bed_guest, $bed, $cleaning_fee, $server_fee, $persons, $bedadd, $status, $isshow, $sales, $displayorder, $area_show, $floor_show, $smoke_show, $bed_show, $persons_show, $bedadd_show, $score, $breakfast, $lanuage, $free_cancel, $checkIn_start, $checkIn_end, $cancel_start, $cancel_end, $out_time, $time_length, $time_type, $lease_type, $remark, $server, $room_type_id, $titles, $slides);
        return ResultHelper::json(200, '编辑成功', $REs);
    }
    public function actionDetail(): array
    {
        $id = (int)Yii::$app->request->input('id');
        $REs = RoomService::detail($id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 房间列表
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionList(): array
    {
        $hotel_id = Yii::$app->request->get('hotel_id');
        $tier_id = Yii::$app->request->get('tier_id');
        $room_pid = Yii::$app->request->get('room_pid', 0);
        $list = RoomService::listAll($hotel_id, $tier_id, $room_pid);
        return ResultHelper::json(200, '获取成功', $list);
    }
    public function actionDel(): array
    {
        $id = (int)Yii::$app->request->input('id');
        $REs = RoomService::del($id);
        return ResultHelper::json(200, '删除成功', $REs);
    }
    public function actionSituation(): array
    {
        $page = (int)Yii::$app->request->input('page') ?? 1;
        $pageSize = (int)Yii::$app->request->input('pageSize') ?? 10;
        $status = (int)Yii::$app->request->input('status');
        $room_type_id = (int)Yii::$app->request->input('room_type_id');
        $REs = RoomService::situation($room_type_id, $status, $page, $pageSize);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 房态查询
     * @return array
     */
    public function actionStatusInit(): array
    {
        $hotel_id = Yii::$app->request->post('hotel_id',0);
        $start_time = Yii::$app->request->post('start_time',0);
        $Res = RoomService::roomStatusInit($hotel_id,$start_time);
        return ResultHelper::json(200, '请求成功', $Res);
    }
}
