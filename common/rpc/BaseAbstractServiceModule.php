<?php

namespace common\rpc;

use common\rpc\jwt\Exception;
use common\rpc\jwt\Jwt;
use common\rpc\services\ContextManager;
use common\rpc\services\JwtService;
use common\rpc\utility\DebugHelper;
use EasySwoole\Rpc\Protocol\Request;
use EasySwoole\Rpc\Service\AbstractServiceModule;

abstract class BaseAbstractServiceModule extends AbstractServiceModule
{
    abstract function authOptional(): array;



    /**
     * 获取上下文数据
     * @param $key
     * @return mixed|null
     */
    public function getAttribute($key): mixed
    {
        return ContextManager::get($key);
    }


    /**
     * 请求前jwt校验
     * @param Request $request
     * @return bool
     */
    protected function onRequest(Request $request): bool
    {
        if (parent::onRequest($request) && !in_array($this->request()->getAction(), $this->authOptional())){
            return $this->checkAccessToken($this->request()->getArg());
        }
        return true;
    }

    /**
     * 校验token
     * @param $data
     * @return bool
     */
    public function checkAccessToken($data): bool
    {
        DebugHelper::consoleWrite('checkAccessToken');
        try {
            $action = $this->request()->getAction();
            if (empty($this->authOptional) || in_array('*', $this->authOptional) || !in_array($action, $this->authOptional)) {
                //token验证
                $header = $this->request()->getHeader();
                $token = $header['authorization'] ?? '';
                if (empty($token)) {
                    throw new Exception('Token header is empty!');
                }
                $config = JwtService::getConfig();
                // 验证 JWT 是否合法
                $secret = $config['SecretKey'];
                $tokenStr = JwtService::decrypt_ecb($token);
                $jwtObject = Jwt::getInstance()->setSecretKey($secret)->setResponse($this->response())->decode($tokenStr);

                $status = $jwtObject->getStatus();

                $Iss = $jwtObject->getIss();

                if ($Iss != $config['Iss']) {
                    $data = [
                        'code' => 401,
                        'type' => 'error',
                        'message' => '非法请求',
                        'data' => []
                    ];
                    $this->response()->setMsg(json_encode($data, JSON_UNESCAPED_UNICODE));
                    return false;
                }

                // 如果encode设置了秘钥,decode 的时候要指定
                switch ($status) {
                    case  1:
                        $userInfo = JwtService::getUser($token);
                        DebugHelper::consoleWrite('get-userInfo',$userInfo);
                        ContextManager::set('userInfo',$userInfo);
                        return true;
                    case  -1:
                        throw new Exception('token已失效');
                    case  -2:
                        throw new Exception('token已过期');
                }
            }

        } catch (\Exception $e) {
            $this->response()->setStatus(400);
            $this->response()->setMsg($e->getMessage());
            return false;
        }

        return true;
    }


    function exception()
    {
        throw new \Exception('the module exception');

    }

    protected function onException(\Throwable $throwable)
    {
        return $this->writeJson(401,'注册失败', [
            'msg'=>$throwable->getMessage(),
            'file'=>$throwable->getFile(),
            'line'=>$throwable->getLine(),
            'trace'=>$throwable->getTrace()
        ]);
    }

    public function writeJson(int $statusCode = 200, string $msg = '获取成功', array $result = []): bool
    {

        if (key_exists('code',$result)){
            $statusCode = $result['code'];
        }
        if (key_exists('message',$result)){
            $msg = $result['message'];
        }
        if (key_exists('data',$result)){
            $result = $result['data'];
        }
        $this->response()->setCode($statusCode);
        $this->response()->setResult($result);
        $this->response()->setMsg($msg);
        $this->response()->setStatus(0);
        return true;
    }

}