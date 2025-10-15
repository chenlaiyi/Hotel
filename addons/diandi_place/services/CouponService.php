<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-31 17:01:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 15:25:00
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\CouponStatusEnums;
use addons\diandi_place\models\enums\CouponTypeEnums;
use addons\diandi_place\models\marketing\PlaceCouponList;
use addons\diandi_place\models\member\PlaceMemberCoupon;
use addons\diandi_place\models\place\PlaceCoupon;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use yii\data\Pagination;
class CouponService extends BaseService
{
    public static function list(int $member_id, int $hotel_id, int $room_id = 0, int $page = 1, int $pageSize = 10)
    {
        $where = [];
        $where['hotel_id'] = $hotel_id;
        if ($room_id) {
            $where['room_id'] = $room_id;
        }
        $time = date('Y-m-d H:i:s', time());
        $timeWhere = [
            'and',
            ['<', 'enable_start', $time],
            ['>', 'enable_end', $time],
        ];
        $query = PlaceCoupon::find()->where($where)->andWhere($timeWhere)->andWhere(['>', 'inventory', 0]);
        // return $query->createCommand()->getRawSql();
        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            'page'       => $page - 1,
            'pageParam'  => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return [
            'total' => $count,
            'list'  => $list
        ];
    }
    public static function Memberlist(int $member_id, int $status, int $page = 1, int $pageSize = 10)
    {
        $where = [];
        $where['status'] = $status;
        $query = PlaceMemberCoupon::find()->where($where)->andWhere(['>', 'surplus_num', 0])->with(['coupon']);
        // return $query->createCommand()->getRawSql();
        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            'page'       => $page - 1,
            'pageParam'  => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        foreach ($list as $key => &$value) {
            $value['status_str'] = CouponStatusEnums::getLabel($value['status']);
            $value['coupon_type_str'] = CouponTypeEnums::getLabel($value['coupon_type']);
        }
        return [
            'total' => $count,
            'list'  => $list
        ];
    }
    /**
     * 用户领取优惠券
     * @param int $member_id
     * @param int $coupon_id
     * @param int $receive_type
     * @return array|HotelMemberCoupon
     * @date 2023-06-08
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function addMemberCoupon(int $member_id, int $coupon_id, int $receive_type): array|HotelMemberCoupon
    {
        $coupon = PlaceCoupon::find()->where(['id' => $coupon_id])->asArray()->one();
        if (empty($coupon)) {
            return ResultHelper::json(400, '优惠券不存在');
        }
        $HotelMemberCoupon = new PlaceMemberCoupon();
        $buy_time =  date('Y-m-d H:i:s', time());
        $use_num = 0;
        $surplus_num = 1;
        $HotelMemberCoupon->load([
            'member_id' => $member_id, //会员id
            'coupon_name' => $coupon['name'], //卡券名称
            'coupon_type' => $coupon['type'], //卡券类型  1：代金券 2：时常卡  3：次卡 4：折扣券 5：体验券
            'coupon_id' => $coupon_id, //卡券id
            'buy_time' => $buy_time, //购买时间
            'end_time' => $coupon['enable_end'], //到期时间
            'use_time' => '', //使用时间
            'use_num' => $use_num, //使用次数
            'surplus_num' => $surplus_num, //剩余次数
            'receive_type' => $receive_type //领取方式：1.领取 2.购买 3.充值赠送
        ], '');
        if (!$HotelMemberCoupon->save()) {
            $msg = ErrorsHelper::getModelError($HotelMemberCoupon);
            return ResultHelper::json(400, $msg);
        }
        return $HotelMemberCoupon;
    }
}
