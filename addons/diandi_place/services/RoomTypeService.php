<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 16:52:04
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\room\PlaceRoomType;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
class RoomTypeService extends BaseService
{
    public static function list(int $type_id): array
    {
        $list = PlaceRoomType::find()
            ->findBloc()
            ->with(['server'])
            ->andWhere(['type_id' => $type_id])
            ->asArray()
            ->all();
        if ($list) {
            foreach ($list as &$v) {
                $v['thumb']  = ImageHelper::tomedia($v['thumb']);
                $thumbs      = unserialize($v['thumbs']) ?: [];
                $v['thumbs'] = ImageHelper::tomedia($thumbs);
            }
        }
        return $list;
    }
    public static function detail(int $id): array|ActiveRecord
    {
        $detail = PlaceRoomType::find()->with(['server'])->where(['id' => $id])->asArray()->one();
        if (empty($detail)) {
            return ResultHelper::json(400, '房型不存在', []);
        }
        $detail['thumbOld']  = $detail['thumb'];
        $detail['thumb']  = ImageHelper::tomedia($detail['thumb']);
        $thumbs           = unserialize($detail['thumbs']) ?: [];
        $detail['thumbsOld']  = $thumbs;
        $detail['thumbs'] = ImageHelper::tomedia($thumbs);
        return $detail;
    }
    public static function edit($id, $title, $room_type, $type_id,string $thumb = '', $oprice = '', $cprice = '', $mprice = '',array $thumbs = [], $device = '', $is_suite = 0, $area = '', $room_num = '', $toilet_num = '', $floor = '', $bed_children = '', $bed_adult = '', $bed_guest = '', $bed = '', $cleaning_fee = '', $server_fee = '', $persons = '', $bedadd = '', $isshow = 0, $sales = 0, $displayorder = 0, $area_show = '', $floor_show = '', $smoke_show = '', $bed_show = '', $persons_show = '', $bedadd_show = '', $score = '', $breakfast = '', $language = '', $free_cancel = '', $checkIn_start = '', $checkIn_end = '', $cancel_start = '', $cancel_end = '', $remark = '', $server = ''): array
    {
        $roomType = PlaceRoomType::findOne($id);
        if (empty($roomType)) {
            return ResultHelper::json(400, '房型不存在', []);
        }
        if ($thumbs) {
            $thumbs           = serialize($thumbs);
            $roomType->thumbs = $thumbs;
        } else {
            $roomType->thumbs = '';
        }
        $roomType->type_id         = $type_id;
        $roomType->room_type         = $room_type ?? 0;
        $roomType->title         = $title;
        $roomType->thumb         = $thumb;
        $roomType->oprice        = number_format($oprice, 2, '.', '');
        $roomType->cprice        = number_format($cprice, 2, '.', '');
        $roomType->mprice        = number_format($mprice, 2, '.', '');
        $roomType->device        = $device;
        $roomType->is_suite      = (int)$is_suite;
        $roomType->area          = $area;
        $roomType->room_num      = (int)$room_num;
        $roomType->toilet_num    = $toilet_num;
        $roomType->floor         = $floor;
        $roomType->bed_children  = $bed_children;
        $roomType->bed_adult     = $bed_adult;
        $roomType->bed_guest     = $bed_guest;
        $roomType->bed           = $bed;
        $roomType->cleaning_fee  = $cleaning_fee;
        $roomType->server_fee    = $server_fee;
        $roomType->persons       = $persons;
        $roomType->bedadd        = $bedadd;
        $roomType->isshow        = (int)$isshow;
        $roomType->sales         = $sales;
        $roomType->displayorder  = (int)$displayorder;
        $roomType->area_show     = (int)$area_show;
        $roomType->floor_show    = (int)$floor_show;
        $roomType->smoke_show    = (int)$smoke_show;
        $roomType->persons_show  = (int)$persons_show;
        $roomType->bedadd_show   = (int)$bedadd_show;
        $roomType->score         = $score;
        $roomType->breakfast     = $breakfast;
        $roomType->language       = (int) $language;
        $roomType->free_cancel   = $free_cancel;
        $roomType->checkIn_start = $checkIn_start;
        $roomType->checkIn_end   = $checkIn_end;
        $roomType->cancel_start  = $cancel_start;
        $roomType->cancel_end    = $cancel_end;
        $roomType->remark        = $remark;
        $roomType->bed_show      = (int)$bed_show;
        RoomDataServer::addOrDel($server, $roomType->id);
        if (!$roomType->save()) {
            $msg = ErrorsHelper::getModelError($roomType);
            return ResultHelper::json(400, $msg);
        }
        return $roomType->toArray();
    }
    public static function add($title, $room_type, $type_id, string $thumb = '',float $oprice = 0, $cprice = '', $mprice = '',array $thumbs = [], $device = '', $is_suite = 0, $area = '', $room_num = '', $toilet_num = '', $floor = '', $bed_children = '', $bed_adult = '', $bed_guest = '', $bed = '', $cleaning_fee = '', $server_fee = '', $persons = '', $bedadd = '', $isshow = 0, $sales = 0, $displayorder = 0, $area_show = '', $floor_show = '', $smoke_show = '', $bed_show = '', $persons_show = '', $bedadd_show = '', $score = '', $breakfast = '', $lanuage = '', $free_cancel = '', $checkIn_start = '', $checkIn_end = '', $cancel_start = '', $cancel_end = '', $remark = '', $server = ''): array
    {
        $thumbs = serialize($thumbs);
        $PlaceRoomType = new PlaceRoomType();
        $PlaceRoomType->load([
            'title'         => $title,
            'room_type'         => $room_type ?? 0,
            'type_id'       => (int)$type_id,
            'thumb'         => $thumb,
            'oprice'        => number_format($oprice, 2, '.', ''),
            'cprice'        => number_format($cprice, 2, '.', ''),
            'mprice'        => number_format($mprice, 2, '.', ''),
            'thumbs'        => $thumbs,
            'device'        => $device,
            'is_suite'      => (int)$is_suite,
            'area'          => $area,
            'room_num'      => $room_num,
            'toilet_num'    => $toilet_num,
            'floor'         => $floor,
            'bed_children'  => $bed_children,
            'bed_adult'     => $bed_adult,
            'bed_guest'     => $bed_guest,
            'bed'           => $bed,
            'cleaning_fee'  => $cleaning_fee,
            'server_fee'    => $server_fee,
            'persons'       => $persons,
            'bedadd'        => $bedadd,
            'isshow'        => (int)$isshow,
            'sales'         => $sales,
            'displayorder'  => (int)$displayorder,
            'area_show'     => (int)$area_show,
            'floor_show'    => (int)$floor_show,
            'smoke_show'    => (int)$smoke_show,
            'bed_show'      => (int)$bed_show,
            'persons_show'  => (int)$persons_show,
            'bedadd_show'   => (int)$bedadd_show,
            'score'         => $score,
            'breakfast'     => $breakfast,
            'lanuage'       => (int)$lanuage,
            'free_cancel'   => $free_cancel,
            'checkIn_start' => $checkIn_start,
            'checkIn_end'   => $checkIn_end,
            'cancel_start'  => $cancel_start,
            'cancel_end'    => $cancel_end,
            'remark'        => $remark
        ], '');
        if (!$PlaceRoomType->save()) {
            $msg = ErrorsHelper::getModelError($PlaceRoomType);
            return ResultHelper::json(400, $msg);
        }
        RoomDataServer::adds($server, 0, $PlaceRoomType->id);
        return $PlaceRoomType->toArray();
    }
    public static function del(int $id)
    {
        if (empty($id)) {
            return ResultHelper::json(400, '房型不存在', []);
        }
        $isHave = PlaceRoomType::findOne($id);
        if (!$isHave) {
            return ResultHelper::json(400, '房型不存在', []);
        }
        return $isHave->delete();
    }
}
