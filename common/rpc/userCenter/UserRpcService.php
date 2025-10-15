<?php


namespace common\rpc\userCenter;


use EasySwoole\Rpc\Service\AbstractService;

/**
 * 插件rpc服务
 */
class UserRpcService extends AbstractService
{
    function serviceName(): string
    {
        return 'User';
    }
}