<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:06:53
 */


namespace common\modules\officialaccount\services;

use admin\models\User;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\modules\officialaccount\models\OfficialaccountQrcode;
use common\modules\officialaccount\models\OfficialaccountWechatConf;
use common\modules\openWeixin\models\enums\OpenWeixinAuthCode;
use common\services\admin\AccessTokenService;
use common\services\BaseService;
use diandi\addons\models\form\Wechat;
use diandi\addons\services\addonsService;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\helpers\Json;

class OfficialaccountService extends BaseService
{
    /**
     * 获取操作实例，0 公众号 1第三方平台
     * @param int $type
     * @return mixed
     * @throws InvalidConfigException
     */
    static function getWechatApp(int $type = 0): mixed
    {
        if ($type === 0) {
            $wechat = Yii::$app->wechat->app;
        } else {
            $wechat = Yii::$app->getModule('openWeixin')->get('OpenApp')->getApp();
        }
        return $wechat;
    }

    static function getBlocId(): int
    {
        $bloc_id =  Yii::$app->request->input('bloc_id', 0);
        if (!$bloc_id){
            $user_id = Yii::$app->user->identity->user_id ?? 0;
            return User::find()->where(['id' => $user_id])->select('parent_bloc_id')->scalar();
        }
        return $bloc_id;
    }

    /**
     * 获取当前公众号配置信息
     * @return array
     */
    static function getWechatConf(): array
    {
        $bloc_id = self::getBlocId();
        $model = new Wechat();
        $conf = OfficialaccountWechatConf::find()->where(['bloc_id' => $bloc_id])->with(['open'])->asArray()->one();

        if (empty($conf)){
            return ResultHelper::json(400, '公众号配置信息不存在');
        }

        $conf['app_id'] = $model->decodeConf($conf['app_id']);
        $conf['token'] = $model->decodeConf($conf['token']);
        $conf['aes_key'] = $model->decodeConf($conf['aes_key']);
        $conf['secret'] = $model->decodeConf($conf['secret']);
        $conf['headimg'] = $model->decodeConf($conf['headimg']);

        if ($conf['open']) {
            $conf['open']['authorizer_appid'] = addonsService::hideStr($conf['open']['authorizer_appid'], 5, 10);
            $conf['open']['authorizer_access_token'] = addonsService::hideStr($conf['open']['authorizer_access_token'], 10, 40);
            $conf['open']['authorizer_refresh_token'] = addonsService::hideStr($conf['open']['authorizer_refresh_token'], 10, 40);
            $func_info = Json::decode($conf['open']['func_info']);

            $func_infos = [];
            foreach ($func_info as $item) {
                $func_infos[] =OpenWeixinAuthCode::getLabel($item['funcscope_category']['id']);
            }
            $conf['open']['func_info'] = $func_infos;
        }



        return $conf;
    }

    /**
     * 创建场景二维码
     * @throws InvalidConfigException
     */
    static function createQrcode(int $timeType,string $scene,int $expireSeconds,string $addons = ''): array
    {
        if ($addons){
            $scene = $addons.'#'.$scene;//用户区分触发模块
        }
        // Array
        // (
        //     [ticket] => gQFD8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyTmFjVTRWU3ViUE8xR1N4ajFwMWsAAgS2uItZAwQA6QcA
        //     [expire_seconds] => 518400
        //     [url] => http://weixin.qq.com/q/02NacU4VSubPO1GSxj1p1k
        // )
        try {
            $expire_seconds = $expireSeconds?:6 * 24 * 3600;
            $app = self::getWechatApp(1);
            if (is_array($app) && key_exists('code',$app) && $app['code'] !== 200){
                return ResultHelper::json(200, '第三方平台配置信息不存在');
            }
            if ($timeType === 0){
                $Res = $app->qrcode->temporary($scene, $expire_seconds);
            }else{
                $expire_seconds = 0;
                $Res = $app->qrcode->forever($scene);
            }

            $origin_url = Yii::$app->request->getOrigin();

            $OfficialaccountQrcode = new OfficialaccountQrcode();
            $OfficialaccountQrcode->load([
                'type' =>$timeType,
                'extra' =>0,
                'user_id' => Yii::$app->user->identity->user_id ?? 0,
                'qrcid' =>0,
                'member_id'=> Yii::$app->user->identity->member_id??0,
                'scene_str' =>$scene,
                'name' =>'officialaccount',
                'keyword' =>'',
                'model' =>'',
                'origin_url' =>$origin_url,
                'ticket' =>$Res['ticket']??"",
                'url' =>$Res['url']??'',
                'expire' =>$expire_seconds,
                'end_time'=>date('Y-m-d H:i:s',time()+$expire_seconds),
                'subnum' =>0,
                'addons' =>$addons,
                'status' =>1,
            ],'');
            if (!$OfficialaccountQrcode->save()){
                $msg = ErrorsHelper::getModelError($OfficialaccountQrcode);
                throw new ErrorException($msg);
            }
            $url = $app->qrcode->url($Res['ticket']);
            return ResultHelper::json(200, '生成成功', [
                'url' => $url,
                'ticket'=> $Res['ticket']
            ]);
        }catch (Exception $e){
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }

    }

    /**
     * 根据$ticket 获取信息
     */
    static function getQrcodeInfo(string $ticket): array
    {
        $OfficialaccountQrcode = new OfficialaccountQrcode();
        return $OfficialaccountQrcode->find()->where(['ticket'=>$ticket])->asArray()->one();
    }

    /**
     * 校验用户登录
     * @param mixed $ticket
     * @return array
     * @throws ErrorException
     * @throws \Throwable
     * @throws \yii\base\Exception
     */
    public static function checkLogin(mixed $ticket): array
    {
        $OfficialaccountQrcode = new OfficialaccountQrcode();
        $data =  $OfficialaccountQrcode->find()->where(['ticket'=>$ticket])->select('data')->scalar();
        if (empty($data)){
            return ResultHelper::json(200, '请扫码',[
                'res'=>'err'
            ]);
        }
        $user = Json::decode($data);

        $adminUser = User::find()->where(['open_id' => $user['openid']])->findBloc()->one();
        if ($adminUser) {
            $userinfo = (new AccessTokenService())->getAccessToken($adminUser, 1);
            loggingHelper::writeLog('officialaccount','actionSignup','存在就登录成功',[
                'userinfo'=>$userinfo,
                'bloc_id'=>Yii::$app->request->input('bloc_id', 1),
                'store_id'=>Yii::$app->request->input('store_id', 1),
            ]);
            return ResultHelper::json(200, '登录成功！', [
                'res'=>'success',
                'userinfo'=>$userinfo
            ]);
        }else{
            return ResultHelper::json(200, '请绑定账号',[
                'res'=>'err'
            ]);
        }

//        else {
//            $adminUser = new User();
//            $maxId = User::find()->max('id');
//            $adminUser->open_id = $user['openid'];
//            $adminUser->union_id = $user['union_id'] ?? null;
//
//            /**
//             * 微信注册手机号码注册为空，后续要求绑定
//             */
//            $res = $adminUser->signup($maxId + 1, '', ($maxId + 1) . '@cn.com', '123465', 1);
//            loggingHelper::writeLog('officialaccount','actionSignup','不存在就注册成功',[
//                'res'=>$res,
//                'bloc_id'=>Yii::$app->request->input('bloc_id', 1),
//                'store_id'=>Yii::$app->request->input('store_id', 1),
//            ]);
//            return ResultHelper::json(200, '注册成功',[
//                'res'=>'success',
//                'userinfo'=>(array)$res
//            ]);
//        }
    }
}