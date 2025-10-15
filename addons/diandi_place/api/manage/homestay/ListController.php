<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:37:34
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-16 16:41:37
 */
namespace addons\diandi_place\api\manage\homestay;
use addons\diandi_place\services\ApartmentService;
use addons\diandi_place\services\HomestayService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 公寓
 */
class ListController extends AController
{
    use LandlordTrait;
    public function actionIndex(): array
    {
        $type_id = Yii::$app->request->input('type_id');
        $keywords = Yii::$app->request->input('keywords');
        $lease_type = (int)Yii::$app->request->input('lease_type');
        // 长租与短租
        $time_type = (int)Yii::$app->request->input('time_type');
        $list = HomestayService::initIndex($type_id, $lease_type, $keywords, $time_type);
        return ResultHelper::json(200, '获取成功', $list);
    }
    public function actionAdd(): array
    {
        $REs = HomestayService::add();
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionDel(): array
    {
        $REs = HomestayService::del();
        return ResultHelper::json(200, '删除成功', $REs);
    }
    public function actionList(): array
    {
        $REs = HomestayService::list();
        return ResultHelper::json(200, '获取成功', $REs);
    }
    public function actionPRoomList(): array
    {
        $type_id = (int)Yii::$app->request->input('type_id');
        $hotel_id = (int)Yii::$app->request->input('hotel_id');
        $tier_id = Yii::$app->request->input('tier_id');
        $page = Yii::$app->request->input('page') ?? 1;
        $pageSize = Yii::$app->request->input('pageSize') ?? 10;
        $REs = HomestayService::pRoomList($type_id, $hotel_id, $tier_id, $page, $pageSize);
        return ResultHelper::json(200, '获取成功', $REs);
    }
}
