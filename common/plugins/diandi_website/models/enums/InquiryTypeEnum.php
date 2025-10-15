<?php

namespace common\plugins\diandi_website\models\enums;


use common\components\BaseEnum;

class InquiryTypeEnum extends BaseEnum
{
    /**
     * 采购需求
     * 维修保养
     * 产品资讯
     */
    const TECHNICAL_CONSULTING = 1;
    const BUSINESS_COOPERATION = 2;
    const PRODUCT_INFORMATION = 3;

    public static $messageCategory = 'App';

    public static $list = [
        self::TECHNICAL_CONSULTING => "技术咨询",
        self::BUSINESS_COOPERATION => "商务合作",
        self::PRODUCT_INFORMATION => "产品资讯"
    ];



}