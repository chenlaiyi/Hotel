<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-31 17:01:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 08:44:06
 */
namespace addons\diandi_place\api\public;
use addons\diandi_place\models\enums\HotelRoomTypeEnums;
use addons\diandi_place\models\enums\PersionEnums;
use addons\diandi_place\models\enums\PlaceOrderByEnums;
use addons\diandi_place\services\CommentService;
use addons\diandi_place\services\LikeService;
use addons\diandi_place\services\PlaceService;
use addons\diandi_place\services\RimService;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 楼栋接口
 */
class PlaceController extends AController
{
    protected array $authOptional = ['listinit'];
    /**
     * 列表页面检索初始化.
     *
     * @return array
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public function actionListInit(): array
    {
        $lng = Yii::$app->request->input('lng') ?? '';
        $lat = Yii::$app->request->input('lat') ?? '';
        // 获取排序
        $HotelOrderBy = PlaceOrderByEnums::listData();
        // 房型
        $RoomType = HotelRoomTypeEnums::listData();
        // 获取人员类型
        $PersionType = PersionEnums::listData();
        // 周边/获取周边信息
        $rimList = PlaceService::RimList($lat, $lng);
        // 综合检索内容
        $searchList = PlaceService::searchList();
        return ResultHelper::json(200, '获取成功', [
            'HotelOrderBy' => $HotelOrderBy,
            'RoomType' => $RoomType,
            'PersionType' => $PersionType,
            'rimList' => $rimList,
            'searchList' => $searchList,
        ]);
    }
    /**
     * 酒店列表
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionList(): array
    {
        // 去掉人数
        $lng = Yii::$app->request->input('lng') ?? '';
        $lat = Yii::$app->request->input('lat') ?? '';
        $keywords = (string)Yii::$app->request->input('keywords') ?? '';
        $start_time = (string)Yii::$app->request->input('start_time') ?? '';
        $end_time = (string)Yii::$app->request->input('end_time') ?? '';
        $location_p = Yii::$app->request->input('location_p',0);
        $location_c = Yii::$app->request->input('location_c',0);
        $location_a = Yii::$app->request->input('location_a',0);
        $page = Yii::$app->request->input('page',0);
        $pageSize = Yii::$app->request->input('pageSize',0);
        $sort_type = Yii::$app->request->input('sort_type',0);
        $rim_type = Yii::$app->request->input('rim_type',0);
        $rim_id = Yii::$app->request->input('rim_id',0);
        $min_price = Yii::$app->request->input('min_price',0);
        $max_price = Yii::$app->request->input('max_price',0);
        $room_type = Yii::$app->request->input('room_type',0);
        $commentStatus = Yii::$app->request->input('commentStatus',0);
        $commentNums = Yii::$app->request->input('commentNums',0);
        $Breakfast = Yii::$app->request->input('Breakfast',0);
        $brand = Yii::$app->request->input('brand',0);
        $lanuage = Yii::$app->request->input('lanuage',0);
        $HotelDevice = Yii::$app->request->input('HotelDevice','');
        $where = PlaceService::createSearchWhere($location_p, $location_c, $location_a, $keywords, $start_time, $end_time, $rim_type, $rim_id, $min_price, $max_price, $room_type, $commentStatus, $commentNums, $Breakfast, $brand, $lanuage, $HotelDevice);
        $orderBy = PlaceService::createSearchOrderBy($sort_type);
        $REs = PlaceService::mobileList($lng, $lat, $page, $pageSize, $where, $orderBy);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 酒店详情
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionDetail(): array
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $hotel_id = (int)Yii::$app->request->input('hotel_id') ?? 0;
        $REs = PlaceService::HotelDetail($hotel_id, $member_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 用户收藏的酒店
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionLike(): array
    {
        $hotel_id = (int)Yii::$app->request->input('hotel_id') ?? 0;
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $list = LikeService::LikeByMid($member_id, $hotel_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * 评论
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionEvaluate(): array
    {
        $hotel_id = (int)Yii::$app->request->input('hotel_id') ?? 0;
        $page = Yii::$app->request->input('page') ?? 1;
        $pageSize = Yii::$app->request->input('pageSize') ?? 10;
        $REs = CommentService::list($hotel_id, 0, $page, $pageSize);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    public function actionRim(): array
    {
        $hotel_id = (int)Yii::$app->request->input('hotel_id') ?? 0;
        $REs = RimService::list($hotel_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
}
