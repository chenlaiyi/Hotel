<?php

namespace common\modules\officialaccount\jobs;

use common\components\Job;
use common\modules\officialaccount\services\WeChatTemplateMessage;


class SendWechatMessageTask extends Job
{
    public $id;

    public function execute($queue)
    {
        WeChatTemplateMessage::wechatSendAll($this->id);
    }
}

