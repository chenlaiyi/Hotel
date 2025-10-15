<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:34:07
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 20:30:12
 */
namespace addons\diandi_place\admin\manage\config;
use addons\diandi_place\services\bloc\ConfigService;
use addons\diandi_place\Traits\LandlordTrait;
use admin\controllers\AController;
use common\helpers\ResultHelper;
/**
 * 楼栋管理
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class BuildingController extends AController
{
    use LandlordTrait;
    public string $modelSearchName = 'PlaceCountry';
    public function actionInfo(): array
   {
        $REs = ConfigService::buildingInfo();
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 单个添加
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdd(): array
   {
        $REs = ConfigService::buildingadd();
        return ResultHelper::json(200, '添加成功', (array)$REs);
    }
    /**
     * 批量添加
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdds(): array
   {
        $REs = ConfigService::buildingadds();
        return ResultHelper::json(200, '添加成功', $REs);
    }
    function allowAction(): array
    {
        return ['*'];
    }
}
