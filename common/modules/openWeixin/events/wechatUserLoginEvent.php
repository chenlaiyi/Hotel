<?php

namespace common\modules\openWeixin\events;

class wechatUserLoginEvent extends \yii\base\Event
{
    const WECHAT_USER_LOGIN_EVENT = 'wechatUserLoginEvent';
    public $member_id;
    public $openid;
    public $unionid;
}