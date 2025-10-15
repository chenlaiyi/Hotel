<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-22 08:50:36
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-08 15:43:23
 */
namespace addons\diandi_place\api\public;
use addons\diandi_place\services\RoomService;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * roomDetail    房间详情    顶部图片查看分享收藏    tabs（详情，评价，可定日期，位置，须知，房东，房源，房型 ）    右侧预定    底部特色民宿推荐    底部青年公寓     底部海岛酒店    底部你可能喜欢
 * @date 2023-03-22
 * @example
 * @author Wang Chunsheng
 * @since
 */
class RoomController extends AController
{
    protected array $authOptional = ['list'];
    /**
     * 房间列表
     * @return array
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionList(): array
   {
        $page = (int)\Yii::$app->request->input('page');
        $pageSize = (int)\Yii::$app->request->input('pageSize');
        // 城市
        $regionC_id = (int)\Yii::$app->request->input('regionC_id');
        // 开始时间
        $start_time =\Yii::$app->request->input('start_time');
        // 结束时间
        $end_time =\Yii::$app->request->input('end_time');
        // 儿童人数
        $bed_children = (int)\Yii::$app->request->input('bed_children');
        // 成人人数
        $bed_adult = (int)\Yii::$app->request->input('bed_adult');
        // 客人人数
        $bed_guest = (int)\Yii::$app->request->input('bed_guest');
        // 可免费取消
        $free_cancel = (int)\Yii::$app->request->input('free_cancel');
        //房间类型 
        $hotel_types =\Yii::$app->request->input('hotel_types');
        // 价格范围
        $min_price =\Yii::$app->request->input('min_price');
        $max_price =\Yii::$app->request->input('max_price');
        // 区域范围
        $rim_ids =\Yii::$app->request->input('rim_ids');
        // 卧室
        $room_num = (int)\Yii::$app->request->input('room_num');
        // 卫生间
        $toilet_num = (int)\Yii::$app->request->input('toilet_num');
        $list = RoomService::searchList($page, $pageSize, $regionC_id, $start_time, $end_time, $bed_children, $bed_adult, $bed_guest, $free_cancel, $hotel_types, $min_price, $max_price, $rim_ids, $room_num, $toilet_num);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * roomDetail    房间详情    顶部图片查看分享收藏    tabs（详情，评价，可定日期，位置，须知，房东，房源 ）    右侧预定    底部特色民宿推荐    底部青年公寓     底部海岛酒店    底部你可能喜欢
     * @return array
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionHomestayDetail(): array
   {
        $room_id = (int)\Yii::$app->request->input('room_id');
        $list = RoomService::RoomZzDetail($room_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    public function actionHomestayhzDetail(): array
   {
        // 区分整租详情
        // 合租详情
        // 酒店详情
        $room_id = (int)\Yii::$app->request->input('room_id');
        $list = RoomService::RoomDetail($room_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * 酒店房间详情
     * @return array
     * @date 2023-05-30
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionHoteldetail(): array
    {
        // 区分整租详情
        // 合租详情
        // 酒店详情
        $room_id = Yii::$app->request->get('room_id',0);
        $list = RoomService::RoomDetail($room_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * 房间相册
     * @return void
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionThumbs()
    {
    }
    /**
     * 房间评价列表
     * @return void
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionEvaluate()
    {
    }
    /**
     * 猜你细化
     * @return array
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionLike(): array
   {
        $room_id =\Yii::$app->request->input('room_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        $list = RoomService::LikeByMid($member_id, $room_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * 底部民宿关联推荐
     * @return array
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionHomestay(): array
   {
        $room_id =\Yii::$app->request->input('room_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        return ResultHelper::json(200, '获取成功', []);
    }
    /**
     * 底部酒店关联推荐
     * @return array
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionHotel(): array
   {
        $room_id =\Yii::$app->request->input('room_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        return ResultHelper::json(200, '获取成功', []);
    }
    /**
     * 公寓关联推荐
     * @return array
     * @date 2023-03-22
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionApartment(): array
{
        $room_id =\Yii::$app->request->input('room_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        return ResultHelper::json(200, '获取成功', []);
    }
    /**
     * 房间收藏
     * @return array
     * @date 2023-05-30
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionCollect(): array
{
        $room_id =\Yii::$app->request->input('room_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        return ResultHelper::json(200, '获取成功', []);
    }
    /**
     * 子房间，合租使用
     * @return array
     * @date 2023-05-30
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionChild(): array
{
        $room_id =\Yii::$app->request->input('room_id');
        $member_id = Yii::$app->user->identity->member_id??0;
        return ResultHelper::json(200, '获取成功', []);
    }
    /**
     * 房间卡券列表
     * @return array
     * @date 2023-05-30
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionCouponList(): array
   {
        $data = Yii::$app->request->post();
        return ResultHelper::json(200, '请求成功', []);
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
