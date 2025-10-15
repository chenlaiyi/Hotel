<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-02 13:43:33
 */
namespace addons\diandi_place\api\manage\room;
use addons\diandi_place\services\RoomTempService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 房间临时入住管理
 * @date 2023-05-24
 * @example
 * @author YuH
 * @since
 */
class TempController extends AController
{
    use LandlordTrait;
    /**
     * 临时入住 (临时入住用户member_id为0)
     * @return array
     */
    public function actionOccupancy(): array
   {
        $hotel_id   =\Yii::$app->request->input('hotel_id');
        $room_id    =\Yii::$app->request->input('room_id');
        $start_time =\Yii::$app->request->input('start_time');
        $end_time   =\Yii::$app->request->input('end_time');
        $persons    = json_decode(Yii::$app->request->input('persons'), true);
        $REs = RoomTempService::occupancy($hotel_id, $room_id, $start_time, $end_time, $persons);
        return ResultHelper::json(200, '办理成功', $REs);
    }
    /**
     * 临时入住用户列表
     * @return array
     */
    public function actionPersonList(): array
    {
        $room_id = Yii::$app->request->input('room_id',0);
        $realname = Yii::$app->request->input('realname','');
        $REs = RoomTempService::personList($room_id,$realname);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 冻结房客
     * @return array
     */
    public function actionFrozen(): array
   {
        $id =\Yii::$app->request->input('id');
        $REs = RoomTempService::frozen($id);
        return ResultHelper::json(200, '操作成功', $REs);
    }
    /**
     * 退房
     * @return array
     */
    public function actionOutRoom(): array
   {
        $id =\Yii::$app->request->input('id');
        $REs = RoomTempService::outRoom($id);
        return ResultHelper::json(200, '操作成功', $REs);
    }
    /**
     * 退房
     * @return array
     */
    public function actionOutRoomAll(): array
   {
        $room_id = Yii::$app->request->get('room_id');
        $REs = RoomTempService::outRoomAll($room_id);
        return ResultHelper::json(200, '操作成功', $REs);
    }
}
