<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 20:24:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-27 16:25:15
 */
namespace addons\diandi_place\services\bloc;
use addons\diandi_hotel\models\hotel\HotelUnit;
use addons\diandi_hotel\models\member\HotelMemberConfig;
use addons\diandi_place\models\enums\RoomStatusEnums;
use addons\diandi_place\models\member\PlaceUserConfig;
use addons\diandi_place\models\place\PlaceTier;
use addons\diandi_place\models\place\PlaceUnit;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
class ConfigService extends BaseService
{
    /**
     * 获取公共配置
     * @param $user_id
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function baseConf($user_id): array
    {
        $HotelMemberConfig = new PlaceUserConfig();
        $conf = $HotelMemberConfig->find()->where(['user_id'=>$user_id])->findBloc()->asArray()->one();
        return  $conf??[];
    }
    /**
     * @param $bloc_id
     * @param $store_id
     * @param $user_id
     * @param $lead_time
     * @param $delay_time
     * @param $maintain_time
     * @param $electrovalence
     * @param int $is_open
     * @return array|HotelMemberConfig|ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function baseSet($bloc_id, $store_id, $user_id, $lead_time, $delay_time, $maintain_time, $electrovalence, int $is_open = 0): array|HotelMemberConfig|ActiveRecord
    {
        $HotelMemberConfig = new PlaceUserConfig();
        $isHave = $HotelMemberConfig->find()->findBloc()->one();
        if ($isHave) {
            $isHave->electrovalence     = number_format($electrovalence, 2, '.', '');
            $isHave->lead_time     = number_format($lead_time, 2, '.', '');
            $isHave->delay_time    = number_format($delay_time, 2, '.', '');
            $isHave->maintain_time = number_format($maintain_time, 2, '.', '');
            $isHave->is_open       = $is_open;
            try {
                $Res = $isHave->update();
                if (!$Res) {
                    $msg = ErrorsHelper::getModelError($HotelMemberConfig);
                    return ResultHelper::json(400, $msg);
                }else{
                    return ResultHelper::json(200, '编辑成功');
                }
            } catch (StaleObjectException $e) {
                $msg = $e->getMessage();
                return ResultHelper::json(400, $msg);
            } catch (\Throwable $e) {
                $msg = $e->getMessage();
                return ResultHelper::json(400, $msg);
            }
        } else {
            $data = [
                'bloc_id'       => $bloc_id,
                'store_id'      => $store_id,
                'user_id'     => $user_id,
                'electrovalence'     => number_format($electrovalence, 2, '.', ''),
                'lead_time'     => number_format($lead_time, 2, '.', ''),
                'delay_time'    => number_format($delay_time, 2, '.', ''),
                'maintain_time' => number_format($maintain_time, 2, '.', ''),
                'is_open'       => $is_open
            ];
            $HotelMemberConfig->load($data, '');
            if (!$HotelMemberConfig->save()) {
                $msg = ErrorsHelper::getModelError($HotelMemberConfig);
                return ResultHelper::json(400, $msg);
            }
        }
        return $isHave ?: $HotelMemberConfig;
    }
    /**
     * 单元信息
     * @param $id
     * @return array|ActiveRecord
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function unitInfo($id): array|ActiveRecord
    {
        $info = PlaceUnit::find()->where(['id' => $id])->asArray()->one();
        if ($info) {
            return $info;
        } else {
            return ResultHelper::json(400, '楼层不存在');
        }
    }
    /**
     * 楼栋单位获取
     * @param $type_id
     * @param $hotel_id
     * @return array
     */
    static function unitList($type_id,$hotel_id): array
    {
        $list = PlaceUnit::find()->where([
            'type_id' => $type_id,
            'hotel_id' => $hotel_id,
        ])->asArray()->all();
        foreach ($list as &$item) {
            $item['status'] = RoomStatusEnums::getLabel($item['status']);
        }
        return $list;
    }
    /**
     * 添加单元
     * @param $title
     * @param $type
     * @param $lease_type
     * @param $time_type
     * @param $time_length
     * @param $hotel_id
     * @param $tier_id
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function unitadd($title, $type,$room_num,$toilet_num,$area, $lease_type, $time_type, $time_length, $hotel_id, $tier_id): array
    {
        $HotelUnit = new PlaceUnit();
        $HotelUnit->load([
            'title'       => $title,
            'room_num'        => $room_num,
            'area'        => $area,
            'toilet_num'        => $toilet_num,
            'type_id'        => $type,
            'lease_type'  => $lease_type,
            'time_type'   => $time_type,
            'time_length' => $time_length,
            'hotel_id'    => $hotel_id,
            'tier_id'     => $tier_id,
            'status' =>RoomStatusEnums::LEAVE_UNUSED
        ], '');
        if (!$HotelUnit->save()) {
            $msg = ErrorsHelper::getModelError($HotelUnit);
            return ResultHelper::json(400, $msg);
        }
        return $HotelUnit->toArray();
    }
    /**
     * 编辑套房
     * @param $id
     * @param $title
     * @param $type
     * @param $lease_type
     * @param $time_type
     * @param $time_length
     * @param $hotel_id
     * @param $tier_id
     * @return array
     */
    public static function unitedit($id, $title, $type, $lease_type, $time_type, $time_length, $hotel_id, $tier_id): array
    {
        $HotelUnit = new PlaceUnit();
        $model     = $HotelUnit->findOne($id);
        if ($model) {
            $model->title       = $title;
            $model->type_id        = $type;
            $model->lease_type  = $lease_type;
            $model->time_type   = $time_type;
            $model->time_length = $time_length;
            $model->hotel_id    = $hotel_id;
            $model->tier_id     = $tier_id;
            try {
                if ($model->update()){
                    return ResultHelper::json(200, '单位更新成功');
                }else{
                    $msg = ErrorsHelper::getModelError($model);
                    return ResultHelper::json(400, $msg);
                }
            } catch (StaleObjectException $e) {
                return ResultHelper::json(400, $e->getMessage());
            } catch (\Throwable $e) {
                return ResultHelper::json(400, $e->getMessage());
            }
        } else {
            return ResultHelper::json(400, '单位不存在');
        }
    }
    /**
     * 批量添加单元
     * @param $nums
     * @param $title
     * @param $type
     * @param $lease_type
     * @param $time_type
     * @param $time_length
     * @param $hotel_id
     * @param $tier_id
     * @return array|HotelUnit|null
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function unitadds($nums, $title, $type, $lease_type, $time_type, $time_length, $hotel_id, $tier_id): array|HotelUnit|null
    {
        $HotelUnit  = new PlaceUnit();
        $_HotelUnit = null;
        for ($i = 0; $i < $nums; $i++) {
            $_HotelUnit = clone $HotelUnit;
            $_HotelUnit->setAttributes([
                'title'       => $title . '-' . $i,
                'type_id'        => $type,
                'lease_type'  => $lease_type,
                'time_type'   => $time_type,
                'time_length' => $time_length,
                'hotel_id'    => $hotel_id,
                'tier_id'     => $tier_id
            ]);
            $_HotelUnit->save();
        }
        $msg = ErrorsHelper::getModelError($_HotelUnit);
        if ($msg) {
            return ResultHelper::json(400, $msg);
        } else {
            return $_HotelUnit->toArray();
        }
    }
    /**
     * 楼层列表
     * @param $type_id //房源类型
     * @param $hotel_id //楼栋
     * @param string $keywords
     * @return array
     * @date 2023-05-08
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function tierlist($type_id, $hotel_id, string $keywords = ''): array
    {
        $list = PlaceTier::find()->where([
            'type_id'  => $type_id,
            'hotel_id' => $hotel_id
        ])->andFilterWhere(['like', 'title', $keywords])->with(['devices'])->asArray()->all();
        if (empty($list)){
            $title = '1F';
            $Res = self::tieradd($type_id, $hotel_id, $title);
            $list = PlaceTier::find()->where([
                'type_id'  => $type_id,
                'hotel_id' => $hotel_id
            ])->andFilterWhere(['like', 'title', $keywords])->asArray()->all();
        }

        array_walk($list, function (&$item) {
            if (key_exists('devices', $item) && $item['devices']){
                array_walk($item['devices'], function (&$item) {
                    $item['thumb'] = $item['goods']? ImageHelper::tomedia($item['goods']['thumb']):'';
                    $item['template_detail'] =  $item['goods']?$item['goods']['template_detail']:'';
                });
            }else{
                $item['devices'] = [];
            }
        });
        return $list;
    }
    /**
     * 楼层信息
     * @param $id
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function tierInfo($id): array
    {
        $info = PlaceTier::find()->where(['id' => $id])->asArray()->one();
        if ($info) {
            return $info;
        } else {
            return ResultHelper::json(400, '楼层不存在');
        }
    }
    /**
     * 添加楼层
     * @param $type_id
     * @param $hotel_id
     * @param $title
     * @param string $prefix
     * @return array
     *
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function tieradd($type_id, $hotel_id, $title, string $prefix = ''): array
    {
        $PlaceTier = new PlaceTier();
        $PlaceTier->load([
            'type_id'  => (int)$type_id,
            'hotel_id' => (int)$hotel_id,
            'title'    => $title,
            'prefix'   => $prefix
        ], '');
        if (!$PlaceTier->save()) {
            $msg = ErrorsHelper::getModelError($PlaceTier);
            return ResultHelper::json(400, $msg);
        }else{
            return ResultHelper::json(200,'保存成功', (array)$PlaceTier);
        }
    }
    /**
     * 编辑楼层
     * @param $id
     * @param $type_id
     * @param $hotel_id
     * @param $title
     * @param $prefix
     * @return array|bool|int
     * @throws StaleObjectException
     * @throws \Throwable
     * @date 2023-05-08
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function tieredit($id, $type_id, $hotel_id, $title, $prefix): array|bool|int
    {
        $PlaceTier = new PlaceTier();
        $model     = $PlaceTier->findOne($id);
        if ($model) {
            $model->type_id  = (int)$type_id;
            $model->hotel_id = (int)$hotel_id;
            $model->title    = $title;
            $model->prefix   = $prefix;
            return $model->update();
        } else {
            return ResultHelper::json(400, '楼层不存在');
        }
    }
    /**
     * 批量添加楼层
     * @param $nums
     * @param $type_id
     * @param $hotel_id
     * @param $prefix
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function tieradds($nums, $type_id, $hotel_id, $prefix): array
    {
        $PlaceTier     = new PlaceTier();
//        $hotelLastTier = $PlaceTier->find()->where(['hotel_id' => $hotel_id, 'type_id' => $type_id])
//            ->findBloc()->orderBy('id desc')->one();
        $batchBeginNum = $PlaceTier->find()->where(['hotel_id' => $hotel_id, 'type_id' => $type_id])
            ->findBloc()->count();
        for ($i = 0; $i < $nums; $i++) {
            $_PlaceTier    = clone $PlaceTier;
            $batchBeginNum += 1;
            $_PlaceTier->setAttributes([
                'type_id'  => (int)$type_id,
                'hotel_id' => (int)$hotel_id,
                'title'    => $prefix ? $prefix . '-' . $batchBeginNum : (string)$batchBeginNum,
                'prefix'   => $prefix
            ]);
            $_PlaceTier->save();
            $msg = ErrorsHelper::getModelError($_PlaceTier);
            if ($msg) {
                return ResultHelper::json(400, $msg);
            }
        }
        return ResultHelper::json(200, '添加成功');
    }
    /**
     * 删除楼层
     * @param int $id
     * @return array|bool|int
     * @throws StaleObjectException
     * @throws \Throwable
     * @date 2023-04-23
     * @example
     * @author YuH
     * @since
     */
    public static function del(int $id): array|bool|int
    {
        if (empty($id)) {
            return ResultHelper::json(400, '楼层不存在');
        }
        $PlaceTier = new PlaceTier();
        $isHave = $PlaceTier->findOne($id);
        if (!$isHave) {
            return ResultHelper::json(400, '楼层不存在');
        }
        // 楼层下是否还有房间处于出租中||维护中
        $isHaveUse = PlaceRoom::find()->findBloc()
            ->andWhere(['tier_id' => $id])
            ->andWhere(['<>', 'status', RoomStatusEnums::LEAVE_UNUSED])
            ->exists();
        if ($isHaveUse) {
            return ResultHelper::json(400, '楼层下存在未闲置房间,不可删除');
        }
        // 删除楼层下的所有房间
        PlaceRoom::deleteAll([
            'bloc_id'  => $isHave->bloc_id,
            'hotel_id' => $isHave->hotel_id,
            'tier_id'  => $id
        ]);
        return $isHave->delete();
    }
    /**
     * 楼栋信息
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function buildingInfo(): array
    {
        return ResultHelper::json(200, '获取成功');
    }
    /**
     * 添加楼栋
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function buildingadd(): array
    {
        return ResultHelper::json(200, '获取成功');
    }
    /**
     * 批量添加楼栋
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function buildingadds(): array
    {
        # code...
        return ResultHelper::json(200, '获取成功');
    }
}
