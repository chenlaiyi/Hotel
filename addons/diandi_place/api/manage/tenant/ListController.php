<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 09:14:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-24 09:21:17
 */
namespace addons\diandi_place\api\manage\tenant;
use addons\diandi_place\services\bloc\TenantService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
class ListController extends AController
{
    use LandlordTrait;
    public function actionAdd()
    {
        $REs = TenantService::add();
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionList()
    {
        $REs = TenantService::list();
        return ResultHelper::json(200, '获取成功', $REs);
    }
    public function actionDetail()
    {
        $REs = TenantService::detail();
        return ResultHelper::json(200, '获取成功', $REs);
    }
}
