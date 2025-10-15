<?php
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\AdTypeEnums;
use addons\diandi_place\models\enums\ApartmentRoomTypeEnums;
use addons\diandi_place\models\enums\BedaddShowEnums;
use addons\diandi_place\models\enums\BreakfastEnums;
use addons\diandi_place\models\enums\CommonStartNum;
use addons\diandi_place\models\enums\CouponStatusEnums;
use addons\diandi_place\models\enums\CouponTypeEnums;
use addons\diandi_place\models\enums\DeviceStatusEnum;
use addons\diandi_place\models\enums\DeviceTypeEnum;
use addons\diandi_place\models\enums\FreeCancelEnums;
use addons\diandi_place\models\enums\HomestayRoomTypeEnums;
use addons\diandi_place\models\enums\InvoiceStatusEnums;
use addons\diandi_place\models\enums\IsShowEnums;
use addons\diandi_place\models\enums\IsVipEnums;
use addons\diandi_place\models\enums\LanguageEnums;
use addons\diandi_place\models\enums\LesseeEnums;
use addons\diandi_place\models\enums\OrderStatusEnums;
use addons\diandi_place\models\enums\OrderTypeEnums;
use addons\diandi_place\models\enums\PayTypeEnums;
use addons\diandi_place\models\enums\PersionEnums;
use addons\diandi_place\models\enums\PersonsShowEnums;
use addons\diandi_place\models\enums\PlaceMemberAuthStatusEnums;
use addons\diandi_place\models\enums\PlaceRoomTypeEnums;
use addons\diandi_place\models\enums\PlaceStatusEnums;
use addons\diandi_place\models\enums\PlaceTypeEnums;
use addons\diandi_place\models\enums\ReceiveTypeEnums;
use addons\diandi_place\models\enums\RechangeOrderEnums;
use addons\diandi_place\models\enums\RimEnums;
use addons\diandi_place\models\enums\RoomStatusEnums;
use addons\diandi_place\models\enums\SlideEnums;
use addons\diandi_place\models\enums\TeaRoomTypeEnums;
use addons\diandi_place\models\enums\TimelengthEnums;
use addons\diandi_place\models\enums\TimeTypeEnums;
use addons\diandi_place\models\place\PlaceList;
use common\services\BaseService;
use Yii;
class EnumService extends BaseService
{
    public static function getEnums(): array
    {
        $f_store_id = Yii::$app->request->input('f_store_id', 1);
        $AdTypeEnums = AdTypeEnums::listData();
        $BedaddShowEnums = BedaddShowEnums::listData();
        $BreakfastEnums = BreakfastEnums::listData();
        $CommonStartNu = CommonStartNum::listData();
        $CouponStatusEnums = CouponStatusEnums::listData();
        $CouponTypeEnums = CouponTypeEnums::listData();
        $FreeCancelEnums = FreeCancelEnums::listData();
        $HotelStatusEnums = PlaceStatusEnums::listData();
        $PlaceTypeEnums = PlaceTypeEnums::listData();
        $InvoiceStatusEnums = InvoiceStatusEnums::listData();
        $IsShowEnums = IsShowEnums::listData();
        $IsVipEnums = IsVipEnums::listData();
        $LanguageEnums = LanguageEnums::listData();
        $OrderStatusEnums = OrderStatusEnums::listData();
        $OrderTypeEnums = OrderTypeEnums::listData();
        $PayTypeEnums = PayTypeEnums::listData();
        $PersionEnums = PersionEnums::listData();
        $PersonsShowEnums = PersonsShowEnums::listData();
        $ReceiveTypeEnums = ReceiveTypeEnums::listData();
        $RechangeOrderEnums = RechangeOrderEnums::listData();
        $RimEnums = RimEnums::listData();
        $RoomStatusEnums = RoomStatusEnums::listData();
        $SlideEnums = SlideEnums::listData();
        $TimelengthEnums = TimelengthEnums::listData();
        $TimeTypeEnums = TimeTypeEnums::listData();
        $LesseeEnums = LesseeEnums::listData();
        $HotelMemberAuthStatusEnum = PlaceMemberAuthStatusEnums::listData();
        $DeviceStatusEnum = DeviceStatusEnum::listData();
        $DeviceTypeEnum = DeviceTypeEnum::listData();
        return [
            'RoomTypeEnums' => self::getRoomTypeByHotelId($f_store_id),
            'AdTypeEnums' => self::initEnums($AdTypeEnums),
            'BedaddShowEnums' => self::initEnums($BedaddShowEnums),
            'BreakfastEnums' => self::initEnums($BreakfastEnums),
            'CommonStartNu' => self::initEnums($CommonStartNu),
            'CouponStatusEnums' => self::initEnums($CouponStatusEnums),
            'CouponTypeEnums' => self::initEnums($CouponTypeEnums),
            'FreeCancelEnums' => self::initEnums($FreeCancelEnums),
            'HotelStatusEnums' => self::initEnums($HotelStatusEnums),
            'PlaceTypeEnums' => self::initEnums($PlaceTypeEnums),
            'InvoiceStatusEnums' => self::initEnums($InvoiceStatusEnums),
            'IsShowEnums' => self::initEnums($IsShowEnums),
            'IsVipEnums' => self::initEnums($IsVipEnums),
            'LanguageEnums' => self::initEnums($LanguageEnums),
            'OrderStatusEnums' => self::initEnums($OrderStatusEnums),
            'OrderTypeEnums' => self::initEnums($OrderTypeEnums),
            'PayTypeEnums' => self::initEnums($PayTypeEnums),
            'PersionEnums' => self::initEnums($PersionEnums),
            'PersonsShowEnums' => self::initEnums($PersonsShowEnums),
            'ReceiveTypeEnums' => self::initEnums($ReceiveTypeEnums),
            'RechangeOrderEnums' => self::initEnums($RechangeOrderEnums),
            'RimEnums' => self::initEnums($RimEnums),
            'RoomStatusEnums' => self::initEnums($RoomStatusEnums),
            'SlideEnums' => self::initEnums($SlideEnums),
            'TimelengthEnums' => self::initEnums($TimelengthEnums),
            'TimeTypeEnums' => self::initEnums($TimeTypeEnums),
            'LesseeEnums' => self::initEnums($LesseeEnums),
            'HotelMemberAuthStatusEnum' => self::initEnums($HotelMemberAuthStatusEnum),
            'DeviceStatusEnum' => self::initEnums($DeviceStatusEnum),
            'DeviceTypeEnum' => self::initEnums($DeviceTypeEnum),
        ];
    }
    public static function initEnums($data): array
    {
        $list = [];
        foreach ($data as $key => $datum) {
            $list[] = [
                'id' => $key,
                'text' => $datum,
                'value' => $key,
                'label' => $datum
            ];
        }
        return $list;
    }
    public static function getRoomTypeByHotelId($f_store_id): array
    {
        $hotelType = PlaceList::find()->where(['store_id' => $f_store_id])->select('type')->scalar();
        $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
//        var_dump($hotelType);
        switch ($hotelType) {
            case PlaceTypeEnums::status1:
                //        status1 => '�Ƶ�',
                $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status2:
                //        status2 => '����',
                $RoomTypeEnums = HomestayRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status3:
                //        status3 => '��Ԣ',
                $RoomTypeEnums = ApartmentRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status4:
                //        status4 => '�칫��',
                $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status5:
                //        status5 => '����',
                $RoomTypeEnums = TeaRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status6:
                //        status6 => '��ջ',
                $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status7:
                //        status7 => '¶Ӫ��',
                $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status8:
                //        status8 => 'ũ����',
                $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
                break;
            case PlaceTypeEnums::status9:
                //        status9 => '��������'
                $RoomTypeEnums = PlaceRoomTypeEnums::listOptions();
                break;
        }
        return $RoomTypeEnums;
    }
    /**
     * ����¥�����ͻ�ȡģ������
     * @return array
     */
    function getTemplateNameByType(): array
    {
        $list = PlaceTypeEnums::listData();
        $names = [];
        foreach ($list as $key => $item) {
            switch ($item) {
                case PlaceTypeEnums::status1:
                    //        status1 => '�Ƶ�',
                    $names[$key] = 'hotelIndex';
                    break;
                case PlaceTypeEnums::status2:
                    //        status2 => '����',
                    $names[$key] = 'hotelIndex';
                    break;
                case PlaceTypeEnums::status3:
                    //        status3 => '��Ԣ',
                    $names[$key] = 'apartmentIndex';
                    break;
                case PlaceTypeEnums::status4:
                    //        status4 => '�칫��',
                    $names[$key] = 'hotelIndex';
                    break;
                case PlaceTypeEnums::status5:
                    //        status5 => '����',
                    $names[$key] = 'teaIndex';
                    break;
                case PlaceTypeEnums::status6:
                    //        status6 => '��ջ',
                    $names[$key] = 'apartmentIndex';
                    break;
                case PlaceTypeEnums::status7:
                    //        status7 => '¶Ӫ��',
                    $names[$key] = 'apartmentIndex';
                    break;
                case PlaceTypeEnums::status8:
                    //        status8 => 'ũ����',
                    $names[$key] = 'apartmentIndex';
                    break;
                case PlaceTypeEnums::status9:
                    //        status9 => '��������'
                    $names[$key] = 'apartmentIndex';
                    break;
            }
        }
        return $names;
    }
}