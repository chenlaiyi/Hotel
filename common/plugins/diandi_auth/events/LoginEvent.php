<?php

namespace common\plugins\diandi_auth\events;

use common\plugins\diandi_auth\services\MemberService;
use common\helpers\loggingHelper;

class LoginEvent
{
    public function wechatUserLoginEvent($event) {
        loggingHelper::writeLog('diandi_auth', 'wechatUserLoginEvent', '微信扫码用户登录事件', [
            'member_id' => $event->member_id,
            'openid' => $event->openid
        ]);
        $member_id = $event->member_id;
        $data = [
            'openid' => $event->openid
        ];
        MemberService::editInfo($member_id, $data);
    }

}