<?php

namespace common\modules\officialaccount\services;

use admin\controllers\AController;
use common\modules\officialaccount\models\OfficialaccountMsgTemplate;
use yii\base\InvalidConfigException;

class TemplateService extends AController
{
    /**
     * 修改账号所属行业
     * @param $industryId1
     * @param $industryId2
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    static function setIndustry($industryId1, $industryId2)
    {
        $wechat = OfficialaccountService::getWechatApp(1);

        return $wechat->template_message->setIndustry($industryId1, $industryId2);
    }

    /**
     * 获取支持的行业列表
     * @return array
     * @throws InvalidConfigException
     */
    static function getIndustry(): array
    {
        $wechat = OfficialaccountService::getWechatApp(1);

        return $wechat->template_message->getIndustry();
    }

    /**
     * 在公众号后台获取 $shortId 并添加到账户。
     * @param $shortId
     * @return mixed
     * @throws InvalidConfigException
     */
    static function addTemplate($shortId)
    {
        $wechat = OfficialaccountService::getWechatApp(1);

         return $wechat->template_message->addTemplate($shortId);
    }

    /**
     * 获取所有模板列表
     * @return array
     * @throws InvalidConfigException
     */
    static function getPrivateTemplates(): array
    {
        $wechat = OfficialaccountService::getWechatApp(1);

        return $wechat->template_message->getPrivateTemplates();
    }

    /**
     * 删除模板
     * @param $templateId
     * @return mixed
     * @throws InvalidConfigException
     */
   static function deletePrivateTemplate($templateId)
   {
       $wechat = OfficialaccountService::getWechatApp(1);

       return $wechat->template_message->getPrivateTemplates();
   }

    /**
     * 同步模板消息
     * @return array
     * @throws InvalidConfigException
     */
    public static function SyncWxTemplate(): array
    {
        $wechat = OfficialaccountService::getWechatApp(1);

        $list = $wechat->template_message->getPrivateTemplates();
        $OfficialaccountMsgTemplate = new OfficialaccountMsgTemplate();

        foreach ($list['template_list'] as $item) {
            $_OfficialaccountMsgTemplate = clone  $OfficialaccountMsgTemplate;
            $_OfficialaccountMsgTemplate->setAttributes([
                'template_id' =>$item['template_id'],
                'title' =>$item['title'],
                'content' =>WeChatTemplateMessage::parseData($item['content']),
                'example' =>$item['example'],
                'status' =>0,

            ]);
            $_OfficialaccountMsgTemplate->save();
        }
        return $list;
    }

}