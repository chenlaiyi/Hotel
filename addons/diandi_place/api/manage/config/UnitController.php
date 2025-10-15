<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:34:14
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-08 15:05:47
 */
namespace addons\diandi_place\api\manage\config;
use addons\diandi_place\services\bloc\ConfigService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 单元管理
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class UnitController extends AController
{
    use LandlordTrait;
    public function actionList(): array
    {
        $type_id = Yii::$app->request->input('type_id');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $REs = ConfigService::unitList($type_id,$hotel_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    public function actionInfo(): array
    {
        $id = Yii::$app->request->input('id');
        $REs = ConfigService::unitInfo($id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 单个添加
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdd(): array
    {
        $title = Yii::$app->request->input('title');
        $type_id = Yii::$app->request->input('type_id');
        $lease_type = Yii::$app->request->input('lease_type');
        $time_type = Yii::$app->request->input('time_type');
        $time_length = Yii::$app->request->input('time_length');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $tier_id = Yii::$app->request->input('tier_id');
        $room_num = Yii::$app->request->input('room_num');
        $toilet_num = Yii::$app->request->input('toilet_num');
        $area = Yii::$app->request->input('area');
        $REs = ConfigService::unitadd($title, $type_id,$room_num,$toilet_num,$area, $lease_type, $time_type, $time_length, $hotel_id, $tier_id);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionEdit(): array
    {
        $id = Yii::$app->request->input('id');
        $title = Yii::$app->request->input('title');
        $type = Yii::$app->request->input('type');
        $lease_type = Yii::$app->request->input('lease_type');
        $time_type = Yii::$app->request->input('time_type');
        $time_length = Yii::$app->request->input('time_length');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $tier_id = Yii::$app->request->input('tier_id');
        $REs = ConfigService::unitedit($id, $title, $type, $lease_type, $time_type, $time_length, $hotel_id, $tier_id);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    /**
     * 批量添加
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdds(): array
    {
        $nums = Yii::$app->request->input('nums');
        $title = Yii::$app->request->input('title');
        $type = Yii::$app->request->input('type');
        $lease_type = Yii::$app->request->input('lease_type');
        $time_type = Yii::$app->request->input('time_type');
        $time_length = Yii::$app->request->input('time_length');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $tier_id = Yii::$app->request->input('tier_id');
        $REs = ConfigService::unitadds($nums, $title, $type, $lease_type, $time_type, $time_length, $hotel_id, $tier_id);
        return ResultHelper::json(200, '添加成功', $REs);
    }
}
