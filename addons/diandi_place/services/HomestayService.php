<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 10:38:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-29 16:08:05
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\LesseeEnums;
use addons\diandi_place\models\enums\RoomStatusEnums;
use addons\diandi_place\models\enums\TimeTypeEnums;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\room\PlaceRoom;
use common\services\BaseService;
use yii\data\Pagination;
class HomestayService extends BaseService
{
    /**
     * 民宿房源管理
     * @param $type_id //房源类型
     * @param $lease_type //0合租1整租
     * @param $keywords //检索关键词
     * @param int $time_type
     * @return array
     */
    public static function initIndex($type_id, $lease_type, $keywords, int $time_type = 0): array
    {
        $where = [];
        $timeWhere = [];
        // 整租
        if (isset($keywords)) {
            $where = ['like', 'title', $keywords];
        }
        if (in_array($time_type, TimeTypeEnums::getConstantsByName())) {
            $timeWhere['time_type'] = $time_type;
        }
        $list = PlaceList::find()->alias('h')
            ->where(['h.type' => $type_id])
            ->findBloc()
            ->with(['tier' => function ($query) use ($lease_type, $where, $timeWhere) {
                $query->with(['unit'=> function($query) use ($lease_type, $where, $timeWhere) {
                    $query->with(['room' => function ($query) use ($lease_type, $where, $timeWhere) {
                        $query->where(['lease_type' => $lease_type, 'room_pid' => 0])->with(['childRoom' => function ($query) use ($where, $timeWhere) {
                            $query->where($where)->andWhere($timeWhere);
                        }]);
                    }]);
                }])->orderBy([
                    'id' => SORT_ASC
                ]);
            }])->asArray()->all();
        self::unitStatus($list);
        return $list;
    }
    /**
     * 单元入住状态
     * @param $items
     * @return void
     */
    static function unitStatus(&$items): void
    {
        foreach ($items as &$item) {
            if (isset($item['tier'])) {
                self::unitStatus($item['tier']);
            }
            if (isset($item['unit'])) {
                foreach ($item['unit'] as &$subItem) {
                    $status = (int) $subItem['status'];
                    $subItem['status'] = RoomStatusEnums::getLabel($status);
                    if (isset($subItem['room'])){
                        foreach ($subItem['room'] as &$room) {
                            $room['status_str'] = RoomStatusEnums::getLabel($room['status']);
                        }
                    }
                }
            }
        }
    }
    public static function add():array
    {
        return [];
    }
    public static function del():array
    {
        return [];
    }
    public static function list():array
    {
        return [];
    }
    public static function pRoomList(int $type_id, int $hotel_id, $tier_id, int $page, int $pageSize): array
    {
        $where = [
            'type_id' => $type_id,
            'hotel_id' => $hotel_id,
            'tier_id' => explode(',', $tier_id),
            'room_pid' => 0,
            'lease_type' => LesseeEnums::JOINT_RENT,
        ];
        // 获取合租的单位
        $query = PlaceRoom::find()->where($where)->findBloc();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return [
            'total' => $count,
            'list' => $list,
        ];
    }
}
