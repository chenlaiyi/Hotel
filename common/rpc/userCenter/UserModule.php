<?php

namespace common\rpc\userCenter;

use common\helpers\ErrorsHelper;
use common\rpc\BaseAbstractServiceModule;
use common\rpc\pdo\PdoQuery;
use common\rpc\pdo\PdoYii;
use common\rpc\services\JwtService;
use common\rpc\utility\DebugHelper;
use EasySwoole\Rpc\Protocol\Request;

class UserModule extends BaseAbstractServiceModule
{

    function authOptional(): array
    {
        return ['signup', 'login'];
    }

    function moduleName(): string
    {
        return 'UserModule';
    }

    function ceshi()
    {
        try {
            DebugHelper::consoleWrite('ceshi');
//            $result = PdoQuery::getInstance()->findOneBy('dd_user',['or',['mobile'=>17778984690],['id'=>11]]);
//            $result = PdoQuery::getInstance()->findOneBy('dd_user',['and',['id'=>11],['mobile'=>17778984690],['store_id'=>153]]);
//            $result = PdoQuery::getInstance()->findOneBy('dd_user',['or',['id'=>11],['mobile'=>17778984690],['store_id'=>153]]);
//            $result = PdoQuery::getInstance()->findOneBy('dd_user',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>17778984690],['store_id'=>153]]]);
//            $result = PdoQuery::getInstance()->findOneBy('dd_user',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>17778984690]],['store_id'=>153]]);
            $PdoQuery = new PdoYii();
            $result = $PdoQuery->table('dd_user')->where(['id'=>11])->orWhere(['mobile'=>17778984690])->orWhere(['store_id'=>153,'username'=>'admin'])->one();
            $LastSql = $PdoQuery->getLastSql();
        }catch (\Exception $e){
            return $this->writeJson(401, '测试失败', [
                'result'=>ErrorsHelper::throwMsg($e)
            ]);
        }

        $userInfo = $this->getAttribute('userInfo');
        return $this->writeJson(200, '测试成功', ['result'=>$result, 'LastSql'=>$LastSql, 'userInfo'=>$userInfo]);
    }

    /**
     * @throws \Exception
     */
    public function signup()
    {
        DebugHelper::consoleWrite('signup');
//        $ccc=  PdoQuery::getInstance()->findOneBy('dd_user',['username'=>'admin']);
//        var_dump($ccc);
        $result = PdoQuery::getInstance()->findPaginated('dd_user', 1, 11);

        $username = $this->request()->input('username');
        $mobile = $this->request()->input('mobile');
        $email = $this->request()->input('email');
        $password = $this->request()->input('password');
        $invitation_code = $this->request()->input('invitation_code', '');

        if (empty($username)) {
            return $this->writeJson(401, '用户名不能为空');
        }
        if (empty($mobile)) {

            return $this->writeJson(401, '手机号不能为空');

        }
        if (empty($email)) {

            return $this->writeJson(401, 'email不能为空');

        }
        if (empty($password)) {
            return $this->writeJson(401, '密码不能为空');
        }
//        try {
            $JwtService = new JwtService();
            $res = $JwtService->signup($username, $mobile, $email, $password, 1, $invitation_code);

            DebugHelper::consoleWrite('signup',$res);
//        }catch (\Exception $e){
//            return $this->writeJson(401,'注册失败', [
//                'msg'=>$e->getMessage(),
//                'file'=>$e->getFile(),
//                'line'=>$e->getLine()
//            ]);
//        }


        return $this->writeJson(200, '注册成功', $res);


    }


    /**
     * @throws \Exception
     */
    public function login(): bool
    {
        $mobile = $this->request()->input('mobile');
        $password = $this->request()->input('password');
        $JwtService = new JwtService();

        $res = $JwtService->createTokenByUsernameAndPassword($mobile, $password);

        return $this->writeJson(200, '获取成功l', $res);
    }

    public function repassword()
    {
        $data = $this->request()->getArg();
        $mobile = $data['mobile'];
        $code = $data['code'];
        $password = $data['password'];
        $JwtService = new JwtService();
        $res = $JwtService->repassword($mobile, $code, $password);
        $this->response()->setMsg($res);
    }


    /**
     * @return void
     * @throws \Exception
     */
    public function userinfo()
    {
        $data = $this->request()->getArg();
        $token = $this->request()->getHeader();
        $JwtService = new JwtService();
        $res = $JwtService->getUser($token['access-token']);
        $this->response()->setMsg($res);
    }

}