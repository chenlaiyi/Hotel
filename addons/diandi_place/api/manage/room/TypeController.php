<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 16:52:21
 */
namespace addons\diandi_place\api\manage\room;
use addons\diandi_place\services\RoomTypeService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\DateHelper;
use common\helpers\ResultHelper;
use Yii;
class TypeController extends AController
{
    use LandlordTrait;
    public function actionList(): array
    {
        $type_id = (int)(Yii::$app->request->input('type_id'));
        if (empty($type_id)){
            return ResultHelper::json(200, '业务类型type_id 不能为空');
        }
        $REs = RoomTypeService::list($type_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    public function actionDetail(): array
    {
        $id  = Yii::$app->request->input('id');
        if (empty($id)){
            return ResultHelper::json(400, 'id不能为空');
        }
        $REs = RoomTypeService::detail($id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    public function actionEdit(): array
    {
        $id            = Yii::$app->request->input('id');
        $room_type       = Yii::$app->request->input('room_type');
//        if (empty($cate_id)){
//            return ResultHelper::json(400,'房型不能为空');
//        }
        $title         = Yii::$app->request->input('title');
        $thumb         = Yii::$app->request->input('thumb');
        $oprice        = Yii::$app->request->input('oprice');
        $cprice        = Yii::$app->request->input('cprice');
        $mprice        = Yii::$app->request->input('mprice');
        $thumbs        = Yii::$app->request->input('thumbs');
        $device        = Yii::$app->request->input('device');
        $is_suite      = Yii::$app->request->input('is_suite');
        $area          = Yii::$app->request->input('area');
        $room_num      = Yii::$app->request->input('room_num');
        $toilet_num    = Yii::$app->request->input('toilet_num');
        $floor         = Yii::$app->request->input('floor');
        $bed_children  = Yii::$app->request->input('bed_children');
        $bed_adult     = Yii::$app->request->input('bed_adult');
        $bed_guest     = Yii::$app->request->input('bed_guest');
        $bed           = Yii::$app->request->input('bed');
        $cleaning_fee  = Yii::$app->request->input('cleaning_fee');
        $server_fee    = Yii::$app->request->input('server_fee');
        $persons       = Yii::$app->request->input('persons');
        $bedadd        = Yii::$app->request->input('bedadd');
        $isshow        = Yii::$app->request->input('isshow');
        $sales         = Yii::$app->request->input('sales');
        $displayorder  = Yii::$app->request->input('displayorder');
        $area_show     = Yii::$app->request->input('area_show');
        $floor_show    = Yii::$app->request->input('floor_show');
        $smoke_show    = Yii::$app->request->input('smoke_show');
        $bed_show      = Yii::$app->request->input('bed_show');
        $persons_show  = Yii::$app->request->input('persons_show');
        $bedadd_show   = Yii::$app->request->input('bedadd_show');
        $score         = Yii::$app->request->input('score');
        $breakfast     = Yii::$app->request->input('breakfast');
        $lanuage       = Yii::$app->request->input('lanuage');
        $type_id   = Yii::$app->request->input('type_id');
        $free_cancel   = Yii::$app->request->input('free_cancel');
        $checkIn_start_input = Yii::$app->request->input('checkIn_start');
        $checkIn_start = $checkIn_start_input?DateHelper::intToDate(strtotime($checkIn_start_input),'H:i:s'):'';
        $checkIn_end_input = Yii::$app->request->input('checkIn_end');
        $checkIn_end   = $checkIn_end_input?DateHelper::intToDate(strtotime($checkIn_end_input),'H:i:s'):'';
        $cancel_start_input = Yii::$app->request->input('cancel_start');
        $cancel_start  = $cancel_start_input?DateHelper::intToDate(strtotime($cancel_start_input),'H:i:s'):'';
        $cancel_end_input = Yii::$app->request->input('cancel_end');
        $cancel_end    = $cancel_end_input?DateHelper::intToDate(strtotime($cancel_end_input),'H:i:s'):'';
        $remark        = Yii::$app->request->input('remark');
        $server        = Yii::$app->request->input('server');
        $REs = RoomTypeService::edit($id, $title, $room_type,$type_id, $thumb, $oprice, $cprice, $mprice, $thumbs, $device, $is_suite, $area, $room_num, $toilet_num, $floor, $bed_children, $bed_adult, $bed_guest, $bed, $cleaning_fee, $server_fee, $persons, $bedadd, $isshow, $sales, $displayorder, $area_show, $floor_show, $smoke_show, $bed_show, $persons_show, $bedadd_show, $score, $breakfast, $lanuage, $free_cancel, $checkIn_start, $checkIn_end, $cancel_start, $cancel_end, $remark, $server);
        return ResultHelper::json(200, '编辑成功', $REs);
    }
    public function actionAdd(): array
    {
        $type_id       = Yii::$app->request->input('type_id');
        $room_type       = Yii::$app->request->input('room_type');
        $title         = Yii::$app->request->input('title');
        $thumb         = Yii::$app->request->input('thumb','');
        $oprice        = Yii::$app->request->input('oprice');
        $cprice        = Yii::$app->request->input('cprice');
        $mprice        = Yii::$app->request->input('mprice');
        $thumbs        = Yii::$app->request->input('thumbs');
        $device        = Yii::$app->request->input('device');
        $is_suite      = Yii::$app->request->input('is_suite');
        $area          = Yii::$app->request->input('area');
        $room_num      = Yii::$app->request->input('room_num');
        $toilet_num    = Yii::$app->request->input('toilet_num');
        $floor         = Yii::$app->request->input('floor');
        $bed_children  = Yii::$app->request->input('bed_children');
        $bed_adult     = Yii::$app->request->input('bed_adult');
        $bed_guest     = Yii::$app->request->input('bed_guest');
        $bed           = Yii::$app->request->input('bed');
        $cleaning_fee  = Yii::$app->request->input('cleaning_fee');
        $server_fee    = Yii::$app->request->input('server_fee');
        $persons       = Yii::$app->request->input('persons');
        $bedadd        = Yii::$app->request->input('bedadd');
        $isshow        = Yii::$app->request->input('isshow');
        $sales         = Yii::$app->request->input('sales');
        $displayorder  = Yii::$app->request->input('displayorder');
        $area_show     = Yii::$app->request->input('area_show');
        $floor_show    = Yii::$app->request->input('floor_show');
        $smoke_show    = Yii::$app->request->input('smoke_show');
        $bed_show      = Yii::$app->request->input('bed_show');
        $persons_show  = Yii::$app->request->input('persons_show');
        $bedadd_show   = Yii::$app->request->input('bedadd_show');
        $score         = Yii::$app->request->input('score');
        $breakfast     = Yii::$app->request->input('breakfast');
        $lanuage       = Yii::$app->request->input('lanuage');
        $free_cancel   = Yii::$app->request->input('free_cancel');
        $checkIn_start_input = Yii::$app->request->input('checkIn_start');
        $checkIn_start = $checkIn_start_input?DateHelper::intToDate(strtotime($checkIn_start_input),'H:i:s'):'';
        $checkIn_end_input = Yii::$app->request->input('checkIn_end');
        $checkIn_end   = $checkIn_end_input?DateHelper::intToDate(strtotime($checkIn_end_input),'H:i:s'):'';
        $cancel_start_input = Yii::$app->request->input('cancel_start');
        $cancel_start  = $cancel_start_input?DateHelper::intToDate(strtotime($cancel_start_input),'H:i:s'):'';
        $cancel_end_input = Yii::$app->request->input('cancel_end');
        $cancel_end    = $cancel_end_input?DateHelper::intToDate(strtotime($cancel_end_input),'H:i:s'):'';
        $remark        = Yii::$app->request->input('remark');
        $server        = Yii::$app->request->input('server');
        if (empty($oprice)){
            return ResultHelper::json(400,'原价不能为空');
        }
        if (empty($cprice)){
            return ResultHelper::json(400,'现价不能为空');
        }
        if (empty($mprice)){
            return ResultHelper::json(400,'会员价不能为空');
        }
        $REs = RoomTypeService::add($title, $room_type, $type_id, $thumb, $oprice, $cprice, $mprice, $thumbs, $device, $is_suite, $area, $room_num, $toilet_num, $floor, $bed_children, $bed_adult, $bed_guest, $bed, $cleaning_fee, $server_fee, $persons, $bedadd, $isshow, $sales, $displayorder, $area_show, $floor_show, $smoke_show, $bed_show, $persons_show, $bedadd_show, $score, $breakfast, $lanuage, $free_cancel, $checkIn_start, $checkIn_end, $cancel_start, $cancel_end, $remark, $server);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionDel(): array
    {
        $id = (int)Yii::$app->request->input('id');
        $REs = RoomTypeService::del($id);
        return ResultHelper::json(200, '删除成功', $REs);
    }
}
