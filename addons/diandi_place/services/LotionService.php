<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-04 11:52:51
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-04 11:54:33
 */
namespace addons\diandi_place\services;
use common\services\BaseService;
/**
 * 位置服务
 *
 * @date 2023-06-04
 *
 * @example
 *
 * @author wang chunSheng
 *
 * @since
 */
class LotionService extends BaseService
{
    /**
     * 根据经纬度返回区域信息.
     *
     * @param [type] $lng
     * @param [type] $lat
     *
     * @return int[]
     * @date 2023-06-04
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public static function getAreaInfo($lng, $lat): array
    {
        return [
            'location_p' => 1,
            'location_c' => 1,
            'location_a' => 1,
        ];
    }
}
