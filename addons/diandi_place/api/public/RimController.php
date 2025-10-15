<?php
namespace addons\diandi_place\api\public;
use addons\diandi_place\services\RimService;
use api\controllers\AController;
use common\helpers\ResultHelper;
/**
 * 周边数据
 */
class RimController extends AController
{
    public function actionList(): array
   {
        $page = \Yii::$app->request->input('page',1);
        $pageSize = \Yii::$app->request->input('$pageSize')??20;
        $rim_type = \Yii::$app->request->input('$rim_type')??0;
        $list = RimService::listAll($page,$pageSize,$rim_type);
        return ResultHelper::json(200, '获取成功',$list);
    }
}