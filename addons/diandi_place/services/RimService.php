<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-31 17:01:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 14:21:12
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\place\PlaceRim;
use common\helpers\ImageHelper;
use common\services\BaseService;
use yii\data\Pagination;
class RimService extends BaseService
{
    public static function listAll(int $page=1,int $pageSize=20,int $rim_type=0):array
    {
        $where = [];
        if(!empty($rim_type)){
            $where['rim_type'] = $rim_type;
        }
        $query = PlaceRim::find()->where($where)->findBloc();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam'=>'page'
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        foreach ($list as &$item) {
            $item['thumb'] = ImageHelper::tomedia($item['thumb']);
            $item['thumbs'] = ImageHelper::tomedia(unserialize($item['thumbs']));
        }
        return $list;
    }
    public static function list(int $hotel_id, int $room_id = 0): array
    {
        $list = PlaceRim::find()->where(['hotel_id' => $hotel_id, 'room_id' => $room_id])->asArray()->all();
        foreach ($list as &$v) {
            $v['thumb'] = ImageHelper::tomedia($v['thumb']);
        }
        return $list;
    }
    /**
     * 更新房间数量
     * @param $rim_id
     * @param $room_num
     * @return int
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function upRoomNum($rim_id, $room_num): int
    {
        return PlaceRim::updateAllCounters([
            'room_num' => $room_num
        ], ['id' => $rim_id]);
    }
}
