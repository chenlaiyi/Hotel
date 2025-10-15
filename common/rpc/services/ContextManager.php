<?php

namespace common\rpc\services;

use EasySwoole\Component\Context\Exception\ModifyError;

class ContextManager
{
    public static function get($key)
    {
        return \EasySwoole\Component\Context\ContextManager::getInstance()->get($key);
    }

    /**
     * @throws ModifyError
     */
    public static function set($key, $value): \EasySwoole\Component\Context\ContextManager
    {
        return \EasySwoole\Component\Context\ContextManager::getInstance()->set($key, $value);
    }
}