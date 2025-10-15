#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';

use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\rpc\AddonsRpcService;
use common\rpc\pdo\PdoPoolContainer;
use common\rpc\pdo\SwoolePDOActiveRecord;
use common\rpc\redis\RedisPoolContainer;
use common\rpc\userCenter\UserModule;
use common\rpc\userCenter\UserRpcService;
use common\rpc\utility\DebugHelper;
use EasySwoole\FastDb\FastDb;
use EasySwoole\Rpc\Config;
use EasySwoole\Rpc\Protocol\Response;
use EasySwoole\Rpc\Rpc;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;
use Swoole\Database\RedisConfig;
use Swoole\Database\RedisPool;
use Swoole\Http\Server;
use yii\db\ActiveRecord;

$isWin = StringHelper::isWindowsOS();
if ($isWin){
    DebugHelper::consoleWrite('暂时不支持windows环境');
    die;
}
defined('YII_RPC') or define('YII_RPC', true);
// 使用环境变量或配置文件获取配置
$serverIp = env('RPC_SERVER_IP') ?: '127.0.0.1';
$serverPort = env('RPC_SERVER_PORT') ?: 9508;

$config = new Config();
$serverConfig =$config->getServer();
$serverConfig->setServerIp($serverIp);
// 设置服务端最大接受包大小
$serverConfig->setMaxPackageSize(1024 * 1024 * 2);
// 设置接收客户端数据时间
$serverConfig->setNetworkReadTimeout(3);

$rpc = new Rpc($config);

$addons = ['diandi_hotel'];

/**
 * 插件rpc
 */
foreach ($addons as $addon) {
    $rpcFilePath = __DIR__ .'/addons/'.$addon.'/rpc.php';
    echo "RPC server file at {$rpcFilePath}\n";
    include $rpcFilePath;
    $class_name = "addons\\$addon\\Rpc";
    $service = new $class_name();
    $RpcModules = FileHelper::file_tree(__DIR__ .'/addons',['*/config/rpc.php']);
    $serviceName = $service->serviceName();
    echo "RPC serviceName  {$serviceName}\n";
    if (!preg_match('/^[A-Z]/u', $serviceName)) {
        throw new InvalidArgumentException("Invalid serviceName name: '$serviceName'. The first character must be a letter.");
    }
    foreach ($RpcModules as $rpcModule) {
        $config = require_once $rpcModule;
        foreach ($config as $moduleName => $item) {
            $service->addModule(new $item);
        }
    }
}
$rpc->serviceManager()->addService($service);

/**
 * 用户中心
 */
$UserService = new UserRpcService();
$UserService->addModule(new UserModule());
$rpc->serviceManager()->addService($UserService);



echo "RPC server started at {$serverIp}:{$serverPort}\n";
$http = new Server($serverIp, $serverPort);
$pdoPool = new PDOPool((new PDOConfig())
    ->withHost(env('DB_HOST','127.0.0.1'))
    ->withPort(env('DB_PORT','3306'))
    ->withUnixSocket('/tmp/mysql.sock')
    ->withDbName(env('DB_NAME','test'))
    ->withCharset('utf8mb4')
    ->withUsername(env('DB_USER','root'))
    ->withPassword(env('DB_PASS','root'))
//    ->withOptions()
);
PdoPoolContainer::getInstance()->set('pdoPool',$pdoPool);

//class_alias(SwoolePDOActiveRecord::class, ActiveRecord::class);

$config = new \EasySwoole\FastDb\Config([
    'name'              => 'default',    // 设置 连接池名称，默认为 default
    'host'              => env('DB_HOST','127.0.0.1'),  // 设置 数据库 host
    'user'              => env('DB_USER','root'), // 设置 数据库 用户名
    'password'          => env('DB_PASS','root'), // 设置 数据库 用户密码
    'database'          => env('DB_NAME','test'), // 设置 数据库库名
    'prefix'            => env('DB_PREFIX','dd_'),
    'port'              => env('DB_PORT','3306'),         // 设置 数据库 端口
    'timeout'           => 5,            // 设置 数据库连接超时时间
    'charset'           => 'utf8',       // 设置 数据库字符编码，默认为 utf8
    'autoPing'          => 5,            // 设置 自动 ping 客户端链接的间隔
    'useMysqli'         => true,        // 设置 不使用 php mysqli 扩展连接数据库
    // 配置 数据库 连接池配置，配置详细说明请看连接池组件 https://www.easyswoole.com/Components/Pool/introduction.html
    // 下面的参数可使用组件提供的默认值
    'intervalCheckTime' => 15 * 1000,    // 设置 连接池定时器执行频率
    'maxIdleTime'       => 10,           // 设置 连接池对象最大闲置时间 (秒)
    'maxObjectNum'      => 20,           // 设置 连接池最大数量
    'minObjectNum'      => 5,            // 设置 连接池最小数量
    'getObjectTimeout'  => 3.0,          // 设置 获取连接池的超时时间
    'loadAverageTime'   => 0.001,        // 设置 负载阈值
]);
FastDb::getInstance()->addDb($config);
echo "RPC server started\n";

$redisPool = new RedisPool((new RedisConfig())
    ->withHost(env('REDIS_HOST','127.0.0.1'))
    ->withPort(env('REDIS_PORT',6379))
    ->withAuth('')
    ->withDbIndex(env('REDIS_DB',0))
    ->withTimeout(1)
);
RedisPoolContainer::getInstance()->set('redisPool',$redisPool);
echo '容器中获取内容';
$http->on('workerStart', function ($server) use($rpc,$pdoPool){
    // 连接预热
    FastDb::getInstance()->preConnect();
});

try {
    $rpc->attachServer($http);
} catch (\EasySwoole\Rpc\Exception\Exception $e) {
    echo $e->getMessage();
}
swoole_set_process_name('ddiotHttp');
$http->on('request', function ($request, $response) use($rpc){
    try {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico' || $request->server['request_uri'] === '/') {
            $response->end();
            return;
        }
        $response->header('Content-Type', 'application/json');
        echo "RPC client started\n";
        echo "RPC client request: {$request->server['request_uri']}\n";

        list($service,$module, $action) = explode('/', trim($request->server['request_uri'], '/'));
        $route = [ucwords($service),ucwords($module),$action];
        $routePath = implode('.',$route);
        $client = $rpc->client();
        echo "RPC client request: $routePath\n";
        $ctx = $client->addRequest($routePath);
        $Arg = array_merge((array)($request->get),(array)($request->post));
        $Header = $request->header;
        $ctx->setArg($Arg);
        $ctx->setHeader($Header);
        // 使用匿名函数处理成功响应
        $onSuccess = function (Response $Response) use ($response) {
            // 使用日志记录而不是直接输出
            $data = $Response->getResult();
            $code = $Response->getCode();
            $response->end(json_encode(['data'=>$data,'message'=>$Response->getMsg(),'code'=>$code]));
        };
        $ctx->setOnSuccess($onSuccess);

        $onFail = function (Response $Response) use ($response) {
            // 使用日志记录而不是直接输出
            DebugHelper::consoleWrite('onFail',[
                    'msg'=>$Response->getMsg()
            ]);
            $data = $Response->getResult();
            $code = $Response->getCode();
            $response->end(json_encode(['data'=>$data,'message'=>$Response->getMsg(),'code'=>$code]));
        };

        $ctx->setOnFail($onFail);
        $client->exec();
    } catch (\Exception $e) {
        // 异常处理，记录日志或返回错误响应
        $err = \common\helpers\ErrorsHelper::throwMsg($e);
        var_dump($err);
        var_dump("RPC call failed: " . $e->getMessage());
    } catch (\Throwable $e) {
        // 异常处理，记录日志或返回错误响应
        $err = \common\helpers\ErrorsHelper::throwMsg($e);
        var_dump($err);
        var_dump("RPC call failed: " . $e->getMessage());
    }
});

// 启动服务器前进行一些必要的日志记录或配置检查
// ...
$http->start();
