<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-04 11:52:51
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-09 10:59:19
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\member\PlaceMemberView;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\ImageHelper;
use common\services\BaseService;
/**
 * 浏览记录服务
 *
 * @date 2023-06-04
 *
 * @example
 *
 * @author wang chunSheng
 *
 * @since
 */
class ViewService extends BaseService
{
    /**
     * 新增用户浏览记录.
     *
     * @param int $member_id
     * @param int $hotel_id
     * @param int $room_id
     * @return bool
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function addView(int $member_id, int $hotel_id, int $room_id = 0): bool
    {
        $HotelMemberView = new PlaceMemberView();
        $isHave = $HotelMemberView->find()
            ->where(['member_id' => $member_id, 'hotel_id' => $hotel_id, 'room_id' => $room_id])
            ->findBloc()->one();
        if ($isHave) {
            ++$isHave->num;
            $isHave->save();
        } else {
            $HotelMemberView->load([
                'member_id' => $member_id,
                'hotel_id' => $hotel_id,
                'room_id' => $room_id,
                'num' => 1,
            ], '');
            $HotelMemberView->save();
        }
        return true;
    }
    /**
     * 获取用户酒店浏览的记录.
     *
     * @param int $member_id
     * @param int $hotel_id
     * @param string $userLng
     * @param string $userLat
     * @return array
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function viewByMid(int $member_id, int $hotel_id = 0, string $userLng = '', string $userLat = ''): array
    {
        $list = PlaceMemberView::find()->alias('m')->where(['member_id' => $member_id])
            ->select(['l.*', 'm.hotel_id'])
            ->andWhere(['!=', 'hotel_id', $hotel_id])
            ->andWhere(['room_id' => 0])
            ->rightJoin(PlaceList::tableName() . 'l', 'm.hotel_id = l.id')
            ->orderBy('num desc')
            ->limit(5)->asArray()->all();
        $hotel_ids = array_column($list, 'hotel_id');
        $prices = PlaceRoom::find()->where(['hotel_id' => $hotel_ids])->select(['hotel_id', 'MIN(cprice) as min_price'])->indexBy('hotel_id')->column();
        foreach ($list as &$v) {
            // 距离
            $v['distance'] = PlaceService::distance($v['lng'], $v['lat'], $userLng, $userLat);
            // 最低价
            $v['min_price'] = $prices[$v['hotel_id']]??0;
            $v['thumb'] = ImageHelper::tomedia($v['thumb']);
        }
        return $list;
    }
    /**
     * 获取用户房间
     * @param int $member_id
     * @param int $room_id
     * @param string $userLng
     * @param string $userLat
     * @return array
     * @date 2023-06-09
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function viewRoomByMid(int $member_id, int $room_id = 0, $userLng = '', $userLat = ''): array
    {
        $list = PlaceMemberView::find()->alias('m')->where(['member_id' => $member_id])
            ->select(['l.*'])
            ->andWhere(['!=', 'room_id', $room_id])
            ->leftJoin(PlaceRoom::tableName() . 'l', 'm.room_id = l.id')
            ->orderBy('num desc')
            ->limit(5)->asArray()->all();
        foreach ($list as &$v) {
            // 距离
            $v['distance'] = PlaceService::distance($v['lng'], $v['lat'], $userLng, $userLat);
            // 最低价
            $v['thumb'] = ImageHelper::tomedia($v['thumb']);
        }
        return $list;
    }
}
