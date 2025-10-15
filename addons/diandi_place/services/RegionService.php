<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:50:48
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-26 13:25:26
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\place\PlaceRegion;
use common\services\BaseService;
class RegionService extends BaseService
{
    public static function getList($level = 1, $pid = 0)
    {
        $list = PlaceRegion::find()->where([
            'level' => $level,
            'pid' => $pid,
        ])->asArray()->all();
        return $list;
    }
}
