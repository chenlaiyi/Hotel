<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 10:38:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-15 16:57:00
 */

namespace addons\diandi_place\services;

use addons\diandi_place\models\enums\BreakfastEnums;
use addons\diandi_place\models\enums\HotelRoomTypeEnums;
use addons\diandi_place\models\enums\LanguageEnums;
use addons\diandi_place\models\enums\PlaceOrderByEnums;
use addons\diandi_place\models\enums\PlaceTypeDefaultEnums;
use addons\diandi_place\models\enums\RimEnums;
use addons\diandi_place\models\enums\TemplateType;
use addons\diandi_place\models\place\PlaceBrand;
use addons\diandi_place\models\place\PlaceComment;
use addons\diandi_place\models\place\PlaceCustomer;
use addons\diandi_place\models\place\PlaceLandlord;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceRim;
use addons\diandi_place\models\place\PlaceServer as PlaceServerModel;
use addons\diandi_place\models\place\PlaceTier;
use addons\diandi_place\models\place\PlaceType;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Throwable;
use Yii;
use yii\data\Pagination;
use yii\db\StaleObjectException;

class PlaceService extends BaseService
{
    public static function getServers($bloc_id): array
    {
        $list = PlaceServerModel::find()->where(['bloc_id' => $bloc_id])->select(['id', 'title as label'])->asArray()->all();
        foreach ($list as &$item) {
            $item['check'] = false;
        }
        return $list;
    }

    /**
     * 酒店数据读取
     * @param $type_id
     * @param $time_type
     * @param string $keywords
     * @return array
     */
    public static function initIndex($type_id, $time_type, string $keywords = ''): array
    {
        $where = [];
        // $where['time_length'] = $time_length;
        if (isset($keywords)) {
            $where = ['like', 'title', $keywords];
        }
        loggingHelper::writeLog('diandi_place', 'PlaceService/initIndex', '酒店数据读取', [
            'sql' => PlaceList::find()->alias('h')->where(['h.type' => $type_id])->findBloc()
                ->with(['tier' => function ($query) use ($time_type, $where) {
                    $query->with(['room' => function ($query) use ($time_type, $where) {
                        $query->where(['time_type' => $time_type])->andWhere($where)->orderBy([
                            'id' => SORT_ASC
                        ]);
                    }])->orderBy([
                        'id' => SORT_ASC
                    ])->findBloc();
                }])->createCommand()->getRawSql()
        ]);
        // 获取楼栋
        return PlaceList::find()->alias('h')->where(['h.type' => $type_id])->findBloc()
            ->with(['tier' => function ($query) use ($time_type, $where) {
                $query->with(['room' => function ($query) use ($time_type, $where) {
                    $query->where(['time_type' => $time_type])->andWhere($where)->orderBy([
                        'id' => SORT_ASC
                    ]);
                    loggingHelper::writeLog('diandi_place', 'PlaceService/initIndex', '酒店数据读取-时长', [
                        'sql' => $query->createCommand()->getRawSql()
                    ]);
                }])->orderBy([
                    'id' => SORT_ASC
                ])->findBloc();
                loggingHelper::writeLog('diandi_place', 'PlaceService/initIndex', '酒店数据读取-房间', [
                    'sql' => $query->createCommand()->getRawSql()
                ]);
            }])->asArray()->all();
    }

    /**
     * 综合检索.
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
    public static function searchList(): array
    {
        // 评分 4.8  4.5 4
        $commentStatus = [
            1 => '4.8分以上',
            2 => '4.5分以上',
            3 => '4分以上',
        ];
        // 点评数量 500 200 100
        $commentNums = [
            1 => '500条以上',
            2 => '200条以上',
            3 => '100条以上',
        ];
        // 早餐 含早餐 单份早餐  双份早餐
        $Breakfast = BreakfastEnums::listData();
        // 酒店设施
        $HotelDevices = PlaceList::find()->findBloc()->select('device')->column();
        $HotelDevice = [];
        foreach ($HotelDevices as $device) {
            if (!empty($device)) {
                $devices = explode(',', $device);
                if (!empty($devices) && is_array($devices)) {
                    foreach ($devices as $val) {
                        $HotelDevice[$val] = $val;
                    }
                }
            }
        }
        // 品牌
        $brand = PlaceBrand::find()->select('title')->indexBy('id')->column();
        // 房东语言
        $lanuage = LanguageEnums::listData();
        $list = [
            'commentStatus' => self::enumsArray($commentStatus),
            'commentNums' => self::enumsArray($commentNums),
            'Breakfast' => self::enumsArray($Breakfast),
            'brand' => self::enumsArray($brand),
            'lanuage' => self::enumsArray($lanuage),
            'HotelDevice' => self::enumsArray(array_values($HotelDevice)),
        ];
        $searchCate = [
            'commentStatus' => '评价星级',
            'commentNums' => '评价数量',
            'Breakfast' => '有无早餐',
            'brand' => '酒店品牌',
            'lanuage' => '房东语言',
            'HotelDevice' => '酒店设施',
        ];
        return [
            'searchCate' => $searchCate,
            'list' => $list
        ];
    }

    public static function enumsArray(array $array): array
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[] = [
                'key' => $key,
                'value' => $value,
            ];
        }
        return $newArray;
    }

    public static function createSearchWhere(int $location_p, int $location_c, int $location_a, string $keywords, string $start_time, string $end_time, int $rim_type, int $rim_id, float $min_price, float $max_price, int $room_type, float $commentStatus, int $commentNums, int $Breakfast, int $brand, int $lanuage, string $HotelDevice): array
    {
        $where = [];
        if (!empty($location_p)) {
            $where['location_p'] = $location_p;
        }
        if (!empty($location_c)) {
            $where['location_c'] = $location_c;
        }
        if (!empty($location_a)) {
            $where['location_a'] = $location_a;
        }
        // $rim_type
        // $rim_id
        // $min_price
        // $max_price
        // $room_type
        // $commentStatus
        // $commentNums
        // $Breakfast
        // $brand
        // $lanuage
        // $HotelDevice
        return $where;
    }

    public static function createSearchOrderBy($sort_type): array
    {
        return match ($sort_type) {
            PlaceOrderByEnums::orderStatus2, PlaceOrderByEnums::orderStatus3, PlaceOrderByEnums::orderStatus4, PlaceOrderByEnums::orderStatus5 => [
                'comment_start' => SORT_DESC, //评价星级
            ],
            default => [
                'create_time' => SORT_DESC, //创建时间
                'level' => SORT_DESC, //星级
                'comment_start' => SORT_DESC, //评价星级
                'comment_num' => SORT_DESC, //评价数量
            ],
        };
    }

    /**
     * 根据经纬度查询周边类型级列表信息.
     *
     * @param [type] $lng
     * @param [type] $lat
     *
     * @return array[]
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function RimList($lng, $lat): array
    {
        // 根据经纬度计算区域
        $location = LotionService::getAreaInfo($lng, $lat);
        $location_p = $location['location_p'];
        $location_c = $location['location_c'];
        $location_a = $location['location_a'];
        $RimType = RimEnums::listData();
        $list = PlaceRim::find()->where([
            'location_p' => $location_p,
            'location_c' => $location_c,
            'location_a' => $location_a,
        ])->asArray()->all();
        $listGroup = ArrayHelper::arrayKeys($list, 'rim_type');
        loggingHelper::writeLog('diandi_place', 'RimList', '获取周边', $listGroup);
        $lists = [];
        // 过滤没有值的
        $RimTypes = [];
        foreach ($RimType as $rim_type => $rim_title) {
            if (!empty($listGroup[$rim_type])) {
                $RimTypes[$rim_type] = $rim_title;
                $lists[$rim_type] = $listGroup[$rim_type];
            }
        }
        return [
            'RimType' => $RimTypes,
            'list' => $lists,
        ];
    }

    public static function addType($title, $template_type, $is_default): array
    {
        $HotelType = new PlaceType();
        if ($is_default == PlaceTypeDefaultEnums::DEFAULT) {
            $hotelType = $HotelType->find()->where(['is_default' => $is_default])->findBloc()->one();
            if ($hotelType) {
                $hotelType->is_default = PlaceTypeDefaultEnums::NORMAL;
                $hotelType->save();
            }
        }
        $HotelType->load([
            'title' => $title,
            'template_type' => $template_type,
            'is_default' => (int)$is_default,
        ], '');
        if (!$HotelType->save()) {
            $msg = ErrorsHelper::getModelError($HotelType);
            return ResultHelper::json(400, $msg);
        }
        return $HotelType->toArray();
    }

    public static function editType($id, $title, $template_type, $is_default): array
    {
        $HotelType = new PlaceType();
        if ($is_default == PlaceTypeDefaultEnums::DEFAULT) {
            $hotelType = $HotelType->find()->where(['is_default' => $is_default])->findBloc()->one();
            if ($hotelType) {
                $hotelType->is_default = PlaceTypeDefaultEnums::NORMAL;
                $hotelType->save();
            }
        }
        $_HotelType = clone $HotelType;
        $hotel = $_HotelType->findOne($id);
        if ($hotel) {
            $hotel->title = $title;
            $hotel->template_type = $template_type;
            $hotel->is_default = (int)$is_default;
            try {
                $hotel->update();
            } catch (StaleObjectException $e) {
                return ResultHelper::json(400, $e->getMessage());
            } catch (\Throwable $e) {
                return ResultHelper::json(400, $e->getMessage());
            }
            return $hotel->toArray();
        } else {
            return ResultHelper::json(400, '类型不存在');
        }
    }

    public static function delType($id): array|bool|int
    {
        if (empty($id)) {
            return ResultHelper::json(400, '房源不存在');
        }
        $HotelType = new PlaceType();
        $isHave = $HotelType->findOne($id);
        if (!$isHave) {
            return ResultHelper::json(400, '房源不存在');
        }
        // 房源下面是否还有楼栋 存在楼栋的不可删除
        $isHaveHotel = PlaceList::find()->findBloc()->andWhere(['type' => $isHave->id])->exists();
        if ($isHaveHotel) {
            return ResultHelper::json(400, '房源下存在楼栋,不可删除');
        }
        try {
            return $isHave->delete();
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
    }

    public static function mobileListType(): array
    {
        $HotelType = new PlaceType();
        $list = $HotelType->find()->asArray()->all();
        $types = TemplateType::getConstantsByValue();
        foreach ($list as &$value) {
            if (array_key_exists($value['template_type'], $types)) {
                $value['pathName'] = $types[$value['template_type']];
            }
        }
        return $list;
    }

    /**
     * 根据类型确定页面跳转
     * @param $user_id
     * @return array
     */
    public static function listType($user_id): array
    {
        $placeType = new PlaceType();
        $query = $placeType->find();
        $list = $query->findBloc()->with(['landlordType' => function ($query) use ($user_id) {
            return $query->where(['user_id' => $user_id]);
        }, 'roomType' => function ($query) {
            return $query->select(['*', 'title as text', 'id as value']);
        }])->asArray()->all();
        $types = TemplateType::getConstantsByValue();
        $checkboxVal = [];
        foreach ($list as &$value) {
            $value['pathName'] = $types[$value['template_name']] ?? $types[1];
            if ($value['landlordType'] && $value['landlordType']['type_status'] === 1) {
                $checkboxVal[] = $value['id'];
            }
        }
        return [
            'list' => $list,
            'checkboxVal' => $checkboxVal
        ];
    }

    public static function isHotelByTypeId($type_id): bool
    {
        $HotelType = new PlaceType();
        $template_type = $HotelType->find()->where(['id' => $type_id])->findBloc()->select('template_type')->scalar();
        return $template_type === 1;
    }

    public static function getLandlordBymemId(): int
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $landlord_id = PlaceLandlord::find()->where(['member_id' => $member_id])->select('id')->scalar();
        return (int)$landlord_id;
    }

    public static function add($title, $type, $is_show = 0, $address_show = 0, $lng = 0, $lat = 0, $address = '', $location_p = 0, $location_c = 0, $location_a = 0, $roomcount = 0, $status = 0, $phone = '', $mail = '', $thumb = '', $description = '', $content = '', $traffic = '', $thumbs = '', $sales = 0, $displayorder = 0, $level = 5, $device = 0, $brandid = 0, $language = 0, $apartment_type = 0): array|PlaceList
    {
        $PlaceList = new PlaceList();
        $landlord_id = self::getLandlordBymemId();
        $PlaceList->load([
            'name' => trim($title),
            'is_show' => (int)$is_show,
            'address_show' => (int)$address_show,
            'lng' => $lng,
            'lat' => $lat,
            'type' => (int)$type,
            'address' => trim($address),
            'location_p' => $location_p,
            'location_c' => $location_c,
            'location_a' => $location_a,
            'roomcount' => $roomcount,
            'status' => (int)$status,
            'phone' => $phone,
            'mail' => $mail,
            'thumb' => $thumb,
            'description' => $description,
            'content' => $content,
            'traffic' => $traffic,
            'thumbs' => $thumbs,
            'sales' => $sales,
            'landlord_id' => $landlord_id,
            'displayorder' => (int)$displayorder,
            'level' => (int)$level,
            'device' => $device,
            'brandid' => (int)$brandid,
            'language' => $language ?? 0,
            'apartment_type' => (int)$apartment_type,
            'lease_type' => (int)$apartment_type ?? 1
        ], '');
        if (!$PlaceList->save()) {
            $msg = ErrorsHelper::getModelError($PlaceList);
            return ResultHelper::json(400, $msg);
        }
        return $PlaceList;
    }

    public static function edit($id, $title, $type, $is_show = 0, $address_show = 0, $lng = 0, $lat = '', $address = '', $location_p = 0, $location_c = 0, $location_a = 0, $roomcount = 0, $status = 0, $phone = '', $mail = '', $thumb = '', $description = '', $content = '', $traffic = '', $thumbs = '', $sales = 0, $displayorder = 0, $level = 5, $device = 0, $brandid = 0, $language = 0, $apartment_type = 0): array
    {
        $PlaceList = new PlaceList();
        $hotel = $PlaceList->findOne($id);
        if ($hotel) {
            $hotel->name = $title;
            $hotel->is_show = (int)$is_show;
            $hotel->address_show = (int)$address_show;
            $hotel->lng = number_format($lng, 2, '.', '');
            $hotel->lat = number_format($lat, 2, '.', '');
            $hotel->type = (int)$type;
            $hotel->address = $address;
            $hotel->location_p = (int)$location_p;
            $hotel->location_c = (int)$location_c;
            $hotel->location_a = (int)$location_a;
            $hotel->roomcount = (int)$roomcount;
            $hotel->status = (int)$status;
            $hotel->phone = $phone;
            $hotel->mail = $mail;
            $hotel->thumb = $thumb;
            $hotel->description = $description;
            $hotel->content = $content;
            $hotel->traffic = $traffic;
            $hotel->thumbs = $thumbs;
            $hotel->sales = $sales;
            $hotel->displayorder = (int)$displayorder;
            $hotel->level = (int)$level;
            $hotel->device = $device;
            $hotel->brandid = (int)$brandid;
            $hotel->language = $language;
            $hotel->apartment_type = (int)$apartment_type;
            try {
                $hotel->update();
                return ResultHelper::json(200, '更新成功');
            } catch (StaleObjectException $e) {
                return ResultHelper::json(500, $e->getMessage());
            } catch (\Throwable $e) {
                return ResultHelper::json(500, $e->getMessage());
            }
        } else {
            return ResultHelper::json(400, '楼栋不存在');
        }
    }

    public static function del($id): array
    {
        if (empty($id)) {
            return ResultHelper::json(400, '楼栋不存在');
        }
        $PlaceList = new PlaceList();
        $isHave = $PlaceList->findOne($id);
        if (!$isHave) {
            return ResultHelper::json(400, '楼栋不存在');
        }
        // 楼栋下是否存在楼层
        $isHaveTier = PlaceTier::find()->findBloc()->andWhere(['hotel_id' => $id])->exists();
        if ($isHaveTier) {
            return ResultHelper::json(400, '楼栋下存在楼层,不可删除');
        }
        try {
            $isHave->delete();
            return ResultHelper::json(200, '删除成功');
        } catch (StaleObjectException $e) {
            return ResultHelper::json(500, $e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(500, $e->getMessage());
        }
    }

    /**
     * 电脑端列表.
     *
     * @param int $page
     * @param int $pageSize
     * @param array $where
     * @param array $orderBy
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
    public static function pcList(int $page = 1, int $pageSize = 10, array $where = [], array $orderBy = []): array
    {
        $query = PlaceList::find()->where($where)->findBloc();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->orderBy($orderBy)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return [
            'total' => $count,
            'list' => $list,
        ];
    }

    /**
     * 楼栋列表.
     *
     * @param int $page
     * @param int $pageSize
     * @param $type
     * @param $keywords
     * @param array $orderBy
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
    public static function blocList(int $page = 1, int $pageSize = 10, int $type = 0, string $keywords = '', array $orderBy = []): array
    {
        $where = [];
        if ($type) {
            $where['type'] = $type;
        }
        $whereLike = [];
        if ($keywords) {
            $whereLike = ['like', 'name', $keywords];
        }
        $query = PlaceList::find()->where($where)->andWhere($whereLike)->findBloc();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->orderBy($orderBy)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return [
            'total' => $count,
            'list' => $list,
            'sql' => $query->createCommand()->getRawSql()
        ];
    }

    /**
     * 手机端列表.
     *
     * @param $userLng
     * @param $userlat
     * @param int $page
     * @param int $pageSize
     * @param array $where
     * @param array $orderBy
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
    public static function mobileList($userLng, $userlat, int $page = 1, int $pageSize = 10, array $where = [], array $orderBy = []): array
    {
        $query = PlaceList::find()->where($where)->findBloc();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'pageParam' => 'page',
        ]);
        $list = $query->offset($pagination->offset)
            ->orderBy($orderBy)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $hotel_ids = array_column($list, 'id');
        $commentNum = PlaceComment::find()->where(['hotel_id' => $hotel_ids])->indexBy('hotel_id')->count();
        foreach ($list as &$value) {
            // 图片
            $value['thumb'] = ImageHelper::tomedia($value['thumb']);
            // 评论数量
            $value['comment_num'] = $commentNum[$value['id']] ?? 0;
            // 距离
            $value['distance'] = self::distance($value['lng'], $value['lat'], $userLng, $userlat);
            // 服务
            $value['device'] = $value['device'] ? explode(',', $value['device']) : [];
            // 是否可用优惠券
            //            $value['is_coupon'] = self::isUseCoupon($value['id']);
            // 起步价格
            $value['minPrice'] = self::minPrice($value['id']);
        }
        return [
            'total' => $count,
            'list' => $list,
        ];
    }

    public static function minPrice(int $hotel_id)
    {
        return PlaceRoom::find()->where(['hotel_id' => $hotel_id])->min('cprice');
    }

    /**
     * 距离计算.
     *
     * @param [type] $hotelLng
     * @param [type] $hotellat
     * @param [type] $userLng
     * @param [type] $userlat
     *
     * @return int
     * @date 2023-06-02
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function distance($hotelLng, $hotellat, $userLng, $userlat): int
    {
        return 10;
    }

    /**
     * 是否可用优惠券.
     *
     * @param $hotel_id
     * @return bool
     * @date 2023-06-02
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function isUseCoupon($hotel_id): bool
    {
        return true;
    }

    /**
     * 酒店详情.
     *
     * @param int $hotel_id
     * @param int $member_id
     * @return array
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function PlaceDetail(int $hotel_id, int $member_id): array
    {
        $hotel = PlaceList::find()->where(['id' => $hotel_id])->with(['store', 'service', 'room', 'roomType', 'landlord', 'comment', 'coupon'])->asArray()->one();
        $hotel['device'] = !empty($hotel['device']) ? explode(',', $hotel['device']) : [];
        // 优惠券处理
        $coupon_ids = PlaceMemberCoupon::find()->where(['member_id' => $member_id])->select('coupon_id')->column();
        if ($hotel['coupon']) {
            foreach ($hotel['coupon'] as &$val) {
                $val['is_get'] = $coupon_ids ? in_array($val['id'], $coupon_ids) : 0;
            }
        }
        if (!$hotel) {
            return ResultHelper::json(200, '酒店不存在');
        }
        $hotel['thumb'] = ImageHelper::tomedia($hotel['thumb']);
        $thumbs = $hotel['thumbs'] ? unserialize($hotel['thumbs']) : [];
        $hotel['thumbs'] = ImageHelper::tomedia($thumbs);
        $comment = PlaceComment::find()->select(['count(*) count', 'avg(star_num) avg_star'])->where(['hotel_id' => $hotel_id, 'room_id' => 0])->asArray()->one();
        $hotel['comment_num'] = $comment ? $comment['count'] : 0;
        $hotel['avg_star'] = $comment ? CommentService::startFloat($comment['avg_star']) : 0;
        if ($hotel['service']) {
            foreach ($hotel['service'] as &$v) {
                $v['thumb'] = ImageHelper::tomedia($v['thumb']);
            }
        }
        $roomList = [];
        //        全局房型
        $roomType = HotelRoomTypeEnums::listData();
        if ($hotel['roomType']) {
            foreach ($hotel['roomType'] as &$v) {
                $v['thumb'] = ImageHelper::tomedia($v['thumb']);
            }
            if ($hotel['room']) {
                foreach ($hotel['room'] as &$item) {
                    $item['thumb'] = ImageHelper::tomedia($item['thumb']);
                }
            }
            unset($v, $item);
            $roomList = ArrayHelper::arrayKeys($hotel['room'], 'room_type');
            loggingHelper::writeLog('diandi_place', 'HotelService', '房间信息', $roomList);
            foreach ($roomType as $cate_id => $value) {
                if (empty($roomList[$cate_id])) {
                    unset($roomList[$cate_id], $roomType[$cate_id]);
                }
            }
            unset($hotel['roomType']);
        }
        $hotel['room_type'] = [];
        foreach ($roomType as $key => $value) {
            $hotel['room_type'][] = [
                'key' => $key,
                'value' => $value
            ];
        }
        $hotel['room_list'] = $roomList;
        // 房东
        if ($hotel['landlord'] && $hotel['landlord']['member']) {
            $hotel['landlord']['avatar'] = ImageHelper::tomedia($hotel['landlord']['member']['avatar']);
        }
        // 评论处理
        // 获取评论内容最多的5个标签
        $hotel['comment_labels'] = PlaceComment::find()->where(['hotel_id' => $hotel_id])->select('labels')->limit('5')->column();
        // 入住须知
        // 周边景点
        $hotel['rimList'] = PlaceRim::find()->where(['hotel_id' => $hotel_id, 'rim_type' => RimEnums::status1])->asArray()->all();
        // 猜你喜欢
        $hotel['memberView'] = ViewService::viewByMid($member_id, $hotel_id);
        // 入住须知
        $hotel['instructions'] = self::CheckInInstructions($hotel_id);
        // 增加用户浏览数据，用于推荐
        ViewService::addView($member_id, $hotel_id);
        return $hotel;
    }

    public static function CheckInInstructions($hotel_id): array
    {
        return [
            [
                'title' => '入住时间',
                'content' => '下午3:00-下午6:00',
            ],
            [
                'title' => '退房时间',
                'content' => '上午11:00',
            ],
            [
                'title' => '独立时间',
                'content' => '有自己独立的房间，与其他人共享其它空间',
            ],
            [
                'title' => '自助入住',
                'content' => '通过大楼工作人员自助入住',
            ],
            [
                'title' => '吸烟：',
                'content' => '禁止吸烟',
            ],
            [
                'title' => '取消政策',
                'content' => '30分钟内免费取消',
            ],
        ];
    }

    public static function hotelByType($user_id, $page = 1, $pageSize = 20): array
    {
        $list = PlaceService::listType($user_id);
        $query = PlaceList::find()->findBloc();
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            // 'pageParam'=>'page'
        ]);
        $hotels = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        $hotels_list = [];
        foreach ($hotels as $hotel) {
            $hotels_list[$hotel['type']][] = $hotel;
        }
        $typeFieds = 'id';
        //        var_dump($list['checkboxVal'], $hotels_list);
        //        return $hotels_list;exit;
        //        foreach ($list['list'] as &$item) {
        //            $item['is_show'] = in_array($item['id'], $list['checkboxVal']);
        //            if (!empty($hotels_list[$item[$typeFieds]])) {
        //                $item['child_hotel'] = $hotels_list[$item[$typeFieds]];
        //            }
        //        }
        foreach ($list['list'] as $k => $item) {
            $list['list'][$k]['is_show'] = in_array($item['id'], $list['checkboxVal']);
            if (!empty($hotels_list[$item[$typeFieds]])) {
                $list['list'][$k]['child_hotel'] = $hotels_list[$item[$typeFieds]];
            }
        }
        // 要根据哪个字段进行排序
        $types = array_values($list['list']);
        $list['list'] = self::sortMultiDimensionalArray($types, 'is_show');
        return $list;
    }

    static function sortMultiDimensionalArray($array, $sortByField, $sortOrder = SORT_DESC)
    {
        if ($array) {
            // Get the size of the first sub-array
            $size = count($array[0]);
            // Check and remove inconsistent elements
            //        foreach ($array as $key => $item) {
            //            if (count($item) !== $size) {
            //                unset($array[$key]);
            //            }
            //        }
            // Sort the array
            usort($array, function ($a, $b) use ($sortByField, $sortOrder) {
                return ($sortOrder === SORT_ASC) ? ((int)$a[$sortByField] <=> (int)$b[$sortByField]) : ((int)$b[$sortByField] <=> (int)$a[$sortByField]);
            });
        }
        return $array;
    }

    /**
     * 设置默认业务类型
     * @throws \Throwable
     */
    public static function hotelByTypeDefault(int $id): array
    {
        $placeType = PlaceType::find()->where(['id' => $id])->findBloc()->asArray()->one();
        $bloc_id = Yii::$app->request->input('bloc_id');
        if ($placeType) {
            PlaceType::updateAll(['is_default' => 0], ['bloc_id' => $bloc_id]);
            if (!PlaceType::updateAll(['is_default' => 1], ['id' => $id])) {
                $msg = ErrorsHelper::getModelError($placeType);
                return ResultHelper::json(400, '设置失败:' . $msg, [
                    'sql' => PlaceType::find()->where(['id' => $id])->findBloc()->createCommand()->getRawSql(),
                ]);
            }
        } else {
            return ResultHelper::json(400, '业务类型不存在');
        }

        return ResultHelper::json(200, '设置成功', $placeType);
    }

    public static function customerList(mixed $page, mixed $pageSize, mixed $title)
    {
        //        if (!LandlordService::isSuperLandlord($user_id)){
        //            return ResultHelper::json(400, '非超级房东，无法查看全部房东', [
        //                'list' => [],
        //                'total' => 0
        //            ]);
        //        }
        $where = [];
        $PlaceCustomer = new PlaceCustomer();
        if ($title) {
            $where = ['like', 'customer_name', $title];
        }
        $query = $PlaceCustomer::find()->findBloc()->where($where)->with(['building']);

        $count = $query->count();
        // 使用总数来创建一个分页对象
        $query->offset(($page - 1) * $pageSize)->limit($pageSize);
        $list = $query->asArray()->all();
        foreach ($list as &$item) {
            if (empty($item['building']) && $item['customer_name']) {
                $item['building'] = PlaceService::initBuilding($item['id'], $item['customer_name'], $item['phone'] ?? '', $item['bloc_id'], $item['store_id']);
            }
        }
        return [
            'list' => $list,
            'sql' => $query->createCommand()->getRawSql(),
            'count' => $count,
        ];
    }


    /**
     * 给房东初始化楼栋
     * @throws \Exception|Throwable
     */
    public static function initBuilding($customer_id, string $realname, string $mobile, int $bloc_id, int $store_id): array
    {
        $key = 'initBuilding:' . $customer_id;
        if (Yii::$app->cache->get($key)) {
            return PlaceList::find()->where(['customer_id' => $customer_id])->asArray()->all();
        }
        $have = PlaceList::find()->where(['customer_id' => $customer_id])->exists();
        if (!$have) {
            $PlaceList = new PlaceList();
            $PlaceList->load([
                'name' => $realname,
                'bloc_id' => $bloc_id,
                'store_id' => $store_id,
                'address' => '',
                'phone' => $mobile,
                'customer_id' => $customer_id,
                'province' => 0,
                'city' => 0,
                'county' => 0,
                'status' => 1,
                'type' => 1,
            ], '');

            if (!$PlaceList->save(false)) {
                $msg = ErrorsHelper::getModelError($PlaceList);
                throw new \Exception('创建楼栋:' . $msg);
            }
            Yii::$app->cache->set($key, 1, 60);
        }

        return PlaceList::find()->where(['customer_id' => $customer_id])->asArray()->all();
    }
}
