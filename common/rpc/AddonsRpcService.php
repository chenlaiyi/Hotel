<?php


namespace common\rpc;


use EasySwoole\Rpc\Service\AbstractService;

/**
 * 插件rpc服务
 */
class AddonsRpcService extends AbstractService
{
    function serviceName(): string
    {
        return 'Addons';
    }
}