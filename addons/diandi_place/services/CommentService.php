<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-31 17:01:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-08 17:45:36
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\place\PlaceComment;
use api\models\DdMember;
use common\services\BaseService;
use yii\data\Pagination;
class CommentService extends BaseService
{
    /**
     * 评论列表
     * @param int $hotel_id
     * @param int $room_id
     * @param int $page
     * @param int $pageSize
     * @return array
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function list(int $hotel_id, int $room_id = 0, int $page = 1, int $pageSize = 10): array
    {
        $query = PlaceComment::find()->alias('c')->where(['hotel_id' => $hotel_id, 'room_id' => $room_id])->with(['member']);
        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            'page'       => $page - 1,
            'pageParam'  => 'page',
        ]);
        $list = $query->select(['c.*', 'm.nickName', 'm.avatar'])
            ->leftJoin(DdMember::tableName() . 'm', 'c.member_id = m.member_id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        // 获取不同的label评论数量
        $labels = PlaceComment::find()->where(['hotel_id' => $hotel_id, 'room_id' => $room_id])->select(['labels', 'count(*) as num'])->groupBy('labels')->asArray()->all();
        // 获取评价
        $star_num = PlaceComment::find()->where(['hotel_id' => $hotel_id, 'room_id' => $room_id])->average('star_num');
        return [
            'total' => $count,
            'star_num'  => self::startFloat($star_num),
            'labels'  => $labels,
            'list'  => $list
        ];
    }
    public static function startFloat($start): float
    {
        if(empty($start)){
            return 0;
        }
        list($one, $two) =  explode(',', $start);
        $startNum = 5.0;
        if ($two && $two > 0 && $two < 5) {
            $startNum = (int) ($one . '.5');
        } else if ($two && $two > 5) {
            $startNum = (int) ceil($one);
        }
        return number_format($startNum, 2, '.', '');
    }
}
