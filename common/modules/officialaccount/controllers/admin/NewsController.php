<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\officialaccount\services\WechatMaterialService;
use yii\base\InvalidConfigException;

/**
 * 微信服务器素材数据
 */
class NewsController extends AController
{
    public string $modelSearchName = "OfficialaccountMsgTemplateSearch";

    public $modelClass = '';

    /**
     * 永久素材列表
     * @return array
     * @throws InvalidConfigException
     */
    public function actionList(): array
    {
//        $type 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
        $type = \Yii::$app->request->input('type','news'); //
        $page = \Yii::$app->request->input('page',1);
        $pageSize = \Yii::$app->request->input('pageSize',20);
        $Res = WechatMaterialService::getMaterial($type,$page, $pageSize);
        return ResultHelper::json(200, '获取成功',$Res);
    }

    /**
     * @param $mediaId
     * @return array
     * @throws InvalidConfigException
     */
    public function actionDeleteNew($mediaId): array
    {
        $Res = WechatMaterialService::deleteMaterial($mediaId);

        return ResultHelper::json(200, '获取成功');
    }

    /**
     * @throws InvalidConfigException
     */
    function actionSynchronous()
    {
        $type = \Yii::$app->request->input('type','image');
        $Res = WechatMaterialService::synchronous($type);

        return ResultHelper::json(200, '同步成功',$Res);
    }

    function actionUpWx()
    {
        $id = \Yii::$app->request->input('id');
        $Res = WechatMaterialService::upWxMaterial($id);
        return ResultHelper::json(200, '同步成功',$Res);
    }

    /**
     * @throws InvalidConfigException
     */
    function actionCount()
    {
        $Res = WechatMaterialService::MaterialCount();
        return ResultHelper::json(200, '同步成功',$Res);

    }

}