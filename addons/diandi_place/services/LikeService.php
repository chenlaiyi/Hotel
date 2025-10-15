<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-04 11:52:51
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-30 15:28:03
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\member\PlaceMemberLike;
use addons\diandi_place\models\member\PlaceMemberLikeCate;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceType;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use yii\data\Pagination;
use yii\db\StaleObjectException;
/**
 * 心愿单服务
 *
 * @date 2023-06-04
 *
 * @example
 *
 * @author wang chunSheng
 *
 * @since
 */
class LikeService extends BaseService
{
    public static function listCateByMid($member_id): array
    {
        $HotelMemberLikeCate = new PlaceMemberLikeCate();
        $list = $HotelMemberLikeCate->find()->where(['member_id' => $member_id])->asArray()->all();
        $HotelMemberLike = new PlaceMemberLike();
        $cate_ids = array_column($list,'id');
        $totalList = $HotelMemberLike->find()->where(['member_id' => $member_id, 'cate_id' => $cate_ids])->select(['count(*) as total','cate_id'])->groupBy('cate_id')->indexBy('cate_id')->column();
        foreach ($list as  &$value) {
            $value['total'] = $totalList[$value['id']]??0;
        }
        return $list;
    }
    /**
     * 添加心愿单
     * @param $member_id
     * @param $title
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function addCate($member_id, $title): array
    {
        if (empty($title)) {
            return ResultHelper::json(200, '心愿名称不能为空');
        }
        $HotelMemberLikeCate = new PlaceMemberLikeCate();
        $ishave = $HotelMemberLikeCate->find()->where(['member_id' => $member_id, 'title' => $title])->select('id')->scalar();
        if ($ishave) {
            return ResultHelper::json(200, '心愿名称不能重复');
        }
        $HotelMemberLikeCate->load([
            'member_id' => $member_id,
            'title' => $title,
        ], '');
        if (!$HotelMemberLikeCate->save()) {
            $msg = ErrorsHelper::getModelError($HotelMemberLikeCate);
            return ResultHelper::json(200, $msg);
        }
        $res = ArrayHelper::toArray($HotelMemberLikeCate);
        return ResultHelper::json(200, '添加成功',$res);
    }
    /**
     * 删除心愿单类型
     * @param $member_id
     * @param $cate_id
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function delCate($member_id, $cate_id): array
    {
        $HotelMemberLikeCate = new PlaceMemberLikeCate();
        if (empty($cate_id)) {
            return ResultHelper::json(400, 'cate_id 不能为空');
        }
        $ishave = $HotelMemberLikeCate->findOne(['member_id' => $member_id, 'id' => $cate_id]);
        if (empty($ishave)) {
            return ResultHelper::json(400, '心愿单不存在');
        }
        try {
            $ishave->delete();
            return ResultHelper::json(200, '删除成功');
        } catch (StaleObjectException $e) {
            return ResultHelper::json(500, $e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(500, $e->getMessage());
        }
    }
    /**
     * 添加心愿
     *
     * @param int $member_id
     * @param int $hotel_id
     * @param int $room_id
     * @param int $cate_id
     * @return array
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function addLike(int $member_id, int $hotel_id, int $room_id = 0, int $cate_id = 0): array
    {
        if (empty($member_id)) {
            return ResultHelper::json(400, 'member_id 不能为空');
        }
        if (empty($hotel_id)) {
            return ResultHelper::json(400, 'hotel_id 不能为空');
        }
        if (empty($cate_id)) {
            return ResultHelper::json(400, 'cate_id 不能为空');
        }
        $ishave = PlaceMemberLikeCate::findOne(['member_id' => $member_id, 'id' => $cate_id]);
        if (empty($ishave)) {
            return ResultHelper::json(400, '心愿单不存在');
        }
        $HotelMemberLike = new PlaceMemberLike();
        $isHave = $HotelMemberLike->find()
            ->where(['member_id' => $member_id, 'hotel_id' => $hotel_id, 'room_id' => $room_id])
            ->findBloc()->one();
        if ($isHave) {
            ++$isHave->num;
            $isHave->save();
        } else {
            $HotelMemberLike->load([
                'cate_id' => $cate_id,
                'member_id' => $member_id,
                'hotel_id' => $hotel_id,
                'room_id' => $room_id,
                'num' => 1,
            ], '');
            $HotelMemberLike->save();
        }
        return ResultHelper::json(200, '添加成功');
    }
    /**
     * 删除心愿
     *
     * @param int $member_id
     * @param int $hotel_id
     * @param int $room_id
     * @return array
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function delLike(int $member_id, int $hotel_id, int $room_id = 0): array
    {
        $HotelMemberLike = new PlaceMemberLike();
        if (empty($hotel_id)) {
            return ResultHelper::json(400, 'hotel_id 不能为空');
        }
        // if (empty($room_id)) {
        //     return ResultHelper::json(400, 'room_id 不能为空');
        // }
        $ishave = PlaceMemberLike::findOne(['member_id' => $member_id, 'hotel_id' => $hotel_id, 'room_id' => $room_id]);
        if (empty($ishave)) {
            return ResultHelper::json(400, '心愿不存在');
        }
        try {
            $Res = $ishave->delete();
            return ResultHelper::json(200, '删除成功');
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
    }
    /**
     * 获取用户收藏的酒店.
     *
     * @param int $member_id
     * @param int $page
     * @param int $pageSize
     * @param int $hotel_id
     * @return array
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function LikeByMid(int $member_id, int $page = 1, int $pageSize = 10, int $hotel_id = 0): array
    {
        $query = PlaceMemberLike::find()->alias('m')->where(['member_id' => $member_id])
            ->select(['l.*'])
            ->leftJoin(PlaceList::tableName() . 'l', 'm.hotel_id = l.id')
            ->with(['hotel', 'room'])
            ->orderBy('num desc');
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page'
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $template_type = PlaceType::find()->findBloc()->indexBy('id')->select('template_type')->column();
        foreach ($list as &$v) {
            // 查看房型 告知跳转模版
            $v['template_type'] = $template_type[$v['hotel']['type']];
            $v['thumb'] = ImageHelper::tomedia($v['hotel']['thumb']);
        }
        return [
            'list' => $list,
            'count' => $count,
        ];
    }
    /**
     * 获取用户是否收藏当前酒店.
     *
     * @param $hotel_id
     * @param $member_id
     * @return bool
     * @date 2023-06-02
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function memberLike($hotel_id, $member_id): bool
    {
        return PlaceMemberLike::find()->where(['hotel_id' => $hotel_id, 'member_id' => $member_id])->exists();
    }
}
