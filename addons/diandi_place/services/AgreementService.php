<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 10:10:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-24 10:13:28
 */
namespace addons\diandi_place\services;
use common\helpers\ResultHelper;
use common\services\BaseService;
/**
 * 协议服务
 * @date 2023-04-24
 * @example
 * @author Wang Chunsheng
 * @since
 */
class AgreementService extends BaseService
{
    public static function protocolAdd(): array
    {
        # code...
        return ResultHelper::json(200, '获取成功');
    }
    public static function protocolList(): array
    {
        # code...
        return ResultHelper::json(200, '获取成功');
    }
    public static function protocolDel(): array
    {
        return ResultHelper::json(200, '获取成功');
    }
}
