<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:50:48
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 14:27:38
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\advertise\PlaceAdvertise;
use addons\diandi_place\models\advertise\PlaceAdvertiseAd;
use addons\diandi_place\models\advertise\PlaceAdvertiseHotel;
use addons\diandi_place\models\advertise\PlaceAdvertiseRim;
use addons\diandi_place\models\advertise\PlaceAdvertiseRoom;
use addons\diandi_place\models\enums\AdTypeEnums;
use addons\diandi_place\models\enums\LesseeEnums;
use addons\diandi_place\models\place\PlaceServer;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Yii;
class AdvertiseService extends BaseService
{
    public static function getTypes()
    {
        return AdTypeEnums::listData();
    }
    public static function getIndexList($type, $mark = ''): array
    {
        $types = AdTypeEnums::getConstantsByName();
        if (!in_array($type, $types)) {
            return ResultHelper::json(400, '广告类型错误', $types);
        }
        $Advertise = PlaceAdvertise::find()->where([
            'mark' => $mark,
            'type' => $type,
            'is_show' => 1,
        ])->indexBy('id')->asArray()->all();
        $location_ids = array_keys($Advertise);
        $lists = [];
        $member_id = Yii::$app->user->identity->member_id??0;
        switch ($type) {
            case AdTypeEnums::HOTEL:
                $list = PlaceAdvertiseHotel::find()->where(['location_id' => $location_ids])->with(['hotel'])->asArray()->all();
                foreach ($list as $key => $value) {
                    if ($value['hotel']) {
                        $lists[$value['location_id']]['advertise'] = $Advertise[$value['location_id']];
                        $hotel = $value['hotel'];
                        $hotel['thumb'] = ImageHelper::tomedia($hotel['thumb']);
                        // 距离
                        $hotel['distance'] = PlaceService::distance($hotel['lng'], $hotel['lat'], 3, 4);
                        // 用户是否收藏
                        $hotel['is_like'] = LikeService::memberLike($hotel['id'], $member_id);
                        // 服务设施  device
                        // $hotel['device'] = !empty($hotel['device'])  ? explode(',', $hotel['device']) : [];
                        $hotel['server']   = PlaceServer::find()->where(['hotel_id' => $hotel['id']])->orderBy('displayorder')->asArray()->all();
                        $min_price = PlaceRoom::find()->where(['hotel_id' => $hotel['id']])->min('cprice');
                        $hotel['min_price'] = $min_price? number_format($min_price,2,'.',''):0.00;
                        // 是否可用优惠券
                        $hotel['is_use_coupon'] = PlaceService::isUseCoupon($hotel['id']);
                        $lists[$value['location_id']]['list'][] =  $hotel;
                    }
                }
                break;
            case AdTypeEnums::ROOM:
                $list = PlaceAdvertiseRoom::find()->where(['location_id' => $location_ids])->with(['room', 'hotel'])->asArray()->all();
                foreach ($list as $key => $value) {
                    if ($value['room']) {
                        $lists[$value['location_id']]['advertise'] = $Advertise[$value['location_id']];
                        $room = $value['room'];
                        $room['thumb'] = ImageHelper::tomedia($room['thumb']);
                        $room['address'] = $value['hotel']['address'];
                        // 服务设施
                        $room['device'] = !empty($room['device'])  ? explode(',', $room['device']) : [];
                        $room['is_like'] = RoomService::memberLike($room['id'], $member_id);
                        // 整租与合租
                        $room['lease_type_str'] =  LesseeEnums::getLabel($room['lease_type']);
                        $lists[$value['location_id']]['list'][] = $room;
                    }
                }
                break;
            case AdTypeEnums::RIM:
                $list = PlaceAdvertiseRim::find()->where(['location_id' => $location_ids])->with(['rim'])->asArray()->all();
                foreach ($list as $key => $value) {
                    if ($value['rim']) {
                        $lists[$value['location_id']]['advertise'] = $Advertise[$value['location_id']];
                        $rim = $value['rim'];
                        $rim['thumb'] = ImageHelper::tomedia($value['rim']['thumb']);
                        // 距离
                        $rim['distance'] = PlaceService::distance($rim['lng'], $rim['lat'], 3, 4);
                        $lists[$value['location_id']]['list'][] = $rim;
                    }
                }
                break;
            case AdTypeEnums::AD:
                $list = PlaceAdvertiseAd::find()->with(['advertise'])->where(['location_id' => $location_ids])->asArray()->all();
                foreach ($list as $key => $value) {
                    $lists[$value['location_id']]['advertise'] = $Advertise[$value['location_id']];
                    $lists[$value['location_id']]['list'][] = $value;
                }
                break;
        }
        return array_values($lists);
    }
}
