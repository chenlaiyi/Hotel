<?php

namespace common\modules\officialaccount\controllers\admin;

use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\officialaccount\services\OfficialaccountService;
use common\modules\officialaccount\services\TemplateService;
use yii\base\InvalidConfigException;

class TemplateController extends AController
{
    public string $modelSearchName = "OfficialaccountQrcodeSearch";

    public $modelClass = '';

    /**
     * 设置行业
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionSetIndustry(): array
    {
        $industryId1 = \Yii::$app->request->input('industryId1');
        $industryId2 = \Yii::$app->request->input('industryId2');

        $result = TemplateService::setIndustry($industryId1, $industryId2);
        return ResultHelper::json(200, '设置成功', $result);
    }

    /**
     * 获取支持的行业列表
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionGetIndustry(): array
    {
        $result = TemplateService::getIndustry();
        return ResultHelper::json(200, '获取成功', $result);
    }

    /**
     * 添加模板
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionAddTemplate(): array
    {
        $shortId = \Yii::$app->request->input('shortId');
        $result = TemplateService::addTemplate($shortId);
        return ResultHelper::json(200, '添加成功', $result);
    }

    /**
     * 获取所有模板列表
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionGetPrivateTemplates(): array
    {
        $result = TemplateService::GetPrivateTemplates();
        return ResultHelper::json(200, '获取成功', $result);
    }

    /**
     * 删除模板
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionDeletePrivateTemplate(): array
    {
        $templateId = \Yii::$app->request->input('templateId');
        $result = TemplateService::deletePrivateTemplate($templateId);
        return ResultHelper::json(200, '删除成功', $result);
    }

    /**
     * 同步微信模板
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function actionSyncWxTemplate(): array
    {
        $result = TemplateService::SyncWxTemplate();
        return ResultHelper::json(200, '同步成功', $result);
    }

}