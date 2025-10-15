<?php

namespace common\modules\officialaccount\models;

use common\modules\openWeixin\models\BlocOpenWechatToken;
use diandi\addons\models\BlocConfWechat;
use yii\db\ActiveQuery;

class OfficialaccountWechatConf extends BlocConfWechat
{
    function getOpen(): ActiveQuery
    {
        return $this->hasOne(BlocOpenWechatToken::class,['bloc_id'=>'bloc_id'])->andOnCondition(['service_type_id'=>2]);
    }
}