<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-02 14:00:22
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 14:25:34
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\PlaceTypeEnums;
use addons\diandi_place\models\place\PlaceConfig;
use addons\diandi_place\models\place\PlaceType;
use common\helpers\ImageHelper;
use common\services\BaseService;
class ConfigService extends BaseService
{
    public static function getConfig()
    {
        $conf =  PlaceConfig::find()->findBloc()->asArray()->one();
        $conf['index_thumb'] = ImageHelper::tomedia($conf['index_thumb']);
        return $conf;
    }
    public static function getHotelType(): array
    {
        $list = PlaceType::find()->findBloc()->select(['template_type', 'title'])->asArray()->all();
        foreach ($list as $key => &$value) {
            if ($value['template_type'] === PlaceTypeEnums::status2 || $value['template_type'] === PlaceTypeEnums::status3) {
                $value['path'] = 'apartment';
            } else {
                $value['path'] = 'hotelquery';
            }
        }
        return $list;
    }
}
