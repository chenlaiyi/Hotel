<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\officialaccount\services\FansService;
use Yii;
use yii\base\InvalidConfigException;

/**
 * 用户标签
 */
class UsertagController extends AController
{
    public string $modelSearchName = "DdWechatFansSearch";

    public $modelClass = '';

    /**
     * @throws InvalidConfigException
     */
    public function actionList(): array
    {
        $Res = FansService::getTagAll();

        return ResultHelper::json(200, '获取成功',$Res['tags']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionCreateTag(): array
    {
        $title = Yii::$app->request->input('title');

        $Res = FansService::createTag($title);

        return ResultHelper::json(200, '获取成功',$Res);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionUpTag(): array
    {

        $tagId = Yii::$app->request->input('tagId');
        $name = Yii::$app->request->input('title');
        $Res = FansService::UpTag($tagId, $name);

        return ResultHelper::json(200, '获取成功',$Res);
    }

    public function actionDelTag(): array
    {
        $tagId = Yii::$app->request->input('tagId');

        $Res = FansService::delTag($tagId);

        return ResultHelper::json(200, '获取成功',$Res);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionUserTags(): array
    {
        $openId = Yii::$app->request->input('openId');

        $Res = FansService::userTags($openId);

        return ResultHelper::json(200, '获取成功',$Res);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionTagUsers(): array
    {
        $tagId = Yii::$app->request->input('tagId');
        $openIds = Yii::$app->request->input('openIds');
        $Res = FansService::tagUsers($tagId,$openIds);

        return ResultHelper::json(200, '获取成功',$Res);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionUntagUsers(): array
    {
        $tagId = Yii::$app->request->input('tagId');
        $openIds = Yii::$app->request->input('openIds');
        $Res = FansService::untagUsers($tagId,$openIds);

        return ResultHelper::json(200, '获取成功',$Res);
    }

}