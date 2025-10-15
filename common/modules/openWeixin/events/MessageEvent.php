<?php

namespace common\modules\openWeixin\events;

use EasySwoole\Component\Event;
use yii\base\Component;

class MessageEvent extends Event
{
    public $message;
}