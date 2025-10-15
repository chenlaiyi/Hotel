<?php
namespace addons\diandi_place\api\manage\room;
use addons\diandi_place\services\PlaceService;
use addons\diandi_place\services\RoomDataServer;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
class ServerController extends AController
{
    use LandlordTrait;
    public function actionAdd(): array
    {
        $room_type_id = (int) Yii::$app->request->input('room_type_id');
        $room_id      = (int) Yii::$app->request->input('room_id');
        $title        = Yii::$app->request->input('title');
        $REs = RoomDataServer::add($title, $room_type_id, $room_id);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionDel(): array
    {
        $id = (int) Yii::$app->request->input('id');
        $REs = RoomDataServer::del($id);
        return ResultHelper::json(200, '删除成功', $REs);
    }
    public function actionList(): array
    {
        $bloc_id =  Yii::$app->request->input('bloc_id');
        $list = PlaceService::getServers($bloc_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
}