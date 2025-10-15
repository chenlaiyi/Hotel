<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-06-05 10:04:24
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-25 16:31:01
 */
namespace addons\diandi_place\console;
use common\helpers\ResultHelper;
use console\controllers\BaseController;
use EasySwoole\Rpc\Config;
use EasySwoole\Rpc\Protocol\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\Tests\Service\ModuleOne;
use EasySwoole\Rpc\Tests\Service\ServiceOne;
use Swoole\Http\Server;
use function Swoole\Coroutine\go;
/**
 * Undocumented class.
 *
 * @date 2022-06-05
 *
 * @example
 *
 * @author Wang Chunsheng
 *
 * @since
 * php ./yii diandi_switch/tcp/run --bloc_id=1 --store_id=1  建议使用
 * nohup php ./yii rpc/ceshi --bloc_id=1 --store_id=1 --addons=diandi_place > /home/nohub/diandi_switch.log  2>&1 &
 * ps -ef|grep php|grep -v grep
 */
class RpcController extends BaseController
{
    public function actionCeshi()
    {
        go(function () {
            # code...
            // 如果在同server中 直接用保存的rpc实例调用即可
            // 如果不是需要重新new一个rpc 注意config的配置 节点管理器 以及所在ip是否能被其他服务广播到 如果不能请调整其他服务的广播地址
            $config = new \EasySwoole\Rpc\Config();
            $rpc = new \EasySwoole\Rpc\Rpc($config);
            $ret = [];
            $client = $rpc->client();
            // client 全局参数
            $client->setClientArg([1, 2, 3]);
            /**
             * 调用商品列表
             */
            $ctx1 = $client->addRequest('Goods.GoodsModule.list');
            // 设置请求参数
            $ctx1->setArg(['a', 'b', 'c']);
            // 设置调用成功执行回调
            $ctx1->setOnSuccess(function (Response $response) use (&$ret) {
                $ret[] = [
                    'list' => [
                        'msg' => $response->getMsg(),
                        'result' => $response->getResult()
                    ]
                ];
            });
            /**
             * 调用信箱公共
             */
            $ctx2 = $client->addRequest('common.CommonModule.mailBox');
            // 设置调用成功执行回调
            $ctx2->setOnSuccess(function (Response $response) use (&$ret) {
                $ret[] = [
                    'mailBox' => [
                        'msg' => $response->getMsg(),
                        'result' => $response->getResult()
                    ]
                ];
            });
            /**
             * 获取系统时间
             */
            $ctx2 = $client->addRequest('common.CommonModule.serverTime');
            // 设置调用成功执行回调
            $ctx2->setOnSuccess(function (Response $response) use (&$ret) {
                $ret[] = [
                    'serverTime' => [
                        'msg' => $response->getMsg(),
                        'result' => $response->getResult()
                    ]
                ];
            });
            // 执行调用
            $client->exec();
            print_r($ret);
        });
    }
}
