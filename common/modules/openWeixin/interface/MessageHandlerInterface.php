<?php

// YourNamespace/Interfaces/MessageHandlerInterface.php
namespace common\modules\openWeixin\interface;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\OpenPlatform\Server\Guard;

interface MessageHandlerInterface  extends  EventHandlerInterface
{
    public function handle(array $message, Guard $server): void;
}
