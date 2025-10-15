<?php

namespace common\modules\wechat\models;

use common\modules\openWeixin\models\BlocOpenWechatToken;
use diandi\addons\models\BlocConfWxapp;
use yii\db\ActiveQuery;

class WxappWechatConf extends BlocConfWxapp
{
    function getOpen(): ActiveQuery
    {
        return $this->hasOne(BlocOpenWechatToken::class,['bloc_id'=>'bloc_id'])->andOnCondition(['service_type_id'=>0]);
    }
}