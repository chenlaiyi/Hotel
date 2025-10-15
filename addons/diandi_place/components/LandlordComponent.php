<?php
namespace addons\diandi_place\components;
use addons\diandi_place\services\LandlordService;
use yii\base\BaseObject;
class LandlordComponent extends BaseObject
{
    /**
     * ��ȡ������Ϣ
     */
    public static function getLandlordInfo($member_id)
    {
        return LandlordService::initAuth($member_id);
    }
}