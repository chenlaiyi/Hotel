<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:07:14
 */


namespace common\modules\openWeixin\services;

use addons\zyj_clothing\models\ZyjClothingQrcode;
use common\models\User;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\WebsiteStationGroup;
use common\modules\officialaccount\models\OfficialaccountQrcode;
use common\modules\openWeixin\models\BlocOpenWechatToken;
use common\modules\openWeixin\models\OpenWechatUser;
use common\services\BaseService;
use diandi\addons\models\form\Wechat;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Json;

/**
 * 第三方授权统一处理
 */
class OpenWechatAuthService extends BaseService
{

    static function getBlocId(): int
    {
        $domain = Yii::$app->request->getHostName();
        $info   = WebsiteStationGroup::findOne(['domain_url' => $domain]);
        if (!empty($info->bloc_id)) {
            return $info->bloc_id;
        }
        /**
         * 默认是系统配置
         */
        $bloc_id = env('WECHATOPENPLATFORMCONFIG_BLOC_ID');
        if (Yii::$app->request->input('auth_bloc_id')) {
            $bloc_id = Yii::$app->request->input('auth_bloc_id');
        }
        return $bloc_id;
    }

    /**
     * 获取公众号信息
     */
    static function getWechatInfo(): mixed
    {
        $bloc_id = self::getBlocId();
        return BlocOpenWechatToken::find()->where(['bloc_id' => $bloc_id, 'service_type_id' => 2])->asArray()->one();
    }

    /**
     * 公众号业务类获取
     *
     * @return mixed
     */
    static function getOfficialAccount(): mixed
    {
        $bloc_id = self::getBlocId();
        $wechat  = BlocOpenWechatToken::find()->where(['bloc_id' => $bloc_id, 'service_type_id' => 2])->asArray()->one();
        if (empty($wechat)) {
            return ResultHelper::json(400, '请配置后使用');
        }

        $openPlatform = Yii::$app->wechat->openPlatform;

        // 代公众号实现业务
        return $openPlatform->officialAccount($wechat['authorizer_appid'], $wechat['authorizer_refresh_token']);
    }

    /**
     * 小程序业务类获取
     *
     * @return mixed
     */
    static function getMiniProgram(): mixed
    {
        $bloc_id = self::getBlocId();
        $wechat  = BlocOpenWechatToken::find()->where(['bloc_id' => $bloc_id, 'service_type_id' => 0])->asArray()->one();

        if (empty($wechat)) {
            return ResultHelper::json(400, '请配置后使用');
        }
        $openPlatform = Yii::$app->wechat->openPlatform;

        // 代小程序实现业务
        return $openPlatform->miniProgram($wechat['authorizer_appid'], $wechat['authorizer_refresh_token']);
    }


    /**
     * 校验全局是否已经配置
     *
     * @param $authorizer_appid
     * @return bool
     */
    static function checkConfigHas($authorizer_appid): bool
    {
        return BlocOpenWechatToken::find()->where(['authorizer_appid' => $authorizer_appid])->exists();
    }

    /**
     * 初始化全局配置
     *
     * @param $app_id
     * @param $secret
     * @param $token
     * @param $aes_key
     * @param $headimg
     * @return array
     */
    static function initBlocConfig($app_id, $secret, $token, $aes_key, $headimg): array
    {
        $bloc_id = self::getBlocId();
        $model   = new Wechat();
        $data    = [
            'app_id'  => $app_id,
            'secret'  => $secret,
            'token'   => $token,
            'aes_key' => $aes_key,
            'headimg' => $headimg
        ];
        $model->load($data);
        $Res = $model->saveConf($bloc_id);
        if ($Res['code'] == 200) {
            return ResultHelper::json(200, $Res['message'], $Res);
        } else {
            return ResultHelper::json(400, $Res['message']);
        }

    }

    static function authInfoSave($auth_code): array
    {
        $openPlatform        = Yii::$app->wechat->openPlatform;
        $authorization_info  = $openPlatform->handleAuthorize($auth_code);
        $Res                 = $authorization_info['authorization_info'];
        $BlocOpenWechatToken = new BlocOpenWechatToken();

        try {
            $detail = $openPlatform->getAuthorizer($Res['authorizer_appid']);
//            $authorizer_info = $detail['authorizer_info'];
//            $secret = '';
//            $aes_key = '';
//            self::initBlocConfig($Res['authorizer_appid'],$secret,$Res['authorizer_access_token'],$aes_key,$authorizer_info['head_img']);
            $Data = [
                'authorizer_appid'         => $Res['authorizer_appid'],
                'bloc_id'                  => self::getBlocId(),
                'authorizer_access_token'  => $Res['authorizer_access_token'],
                'expires_in'               => $Res['expires_in'],
                'service_type_id'          => $detail['authorizer_info']['service_type_info']['id'],
                'verify_type_id'           => $detail['authorizer_info']['verify_type_info']['id'],
                'nick_name'                => $detail['authorizer_info']['nick_name'],
                'qrcode_url'               => $detail['authorizer_info']['qrcode_url'],
                'authorizer_refresh_token' => $Res['authorizer_refresh_token'],
                'func_info'                => json_encode($Res['func_info'])
            ];
            loggingHelper::writeLog('OpenWechatAuthService', 'authInfoSave', '授权详情数据', [
                'detail' => $detail
            ]);
            if (self::checkConfigHas($Res['authorizer_appid'])) {
                $oldToken = $BlocOpenWechatToken->find()->where(['authorizer_appid' => $Res['authorizer_appid']])->one();
                $oldToken->setAttributes($Data);
                $oldToken->update();
                return ResultHelper::json(200, '授权成功', $Data);
            } else {

                $BlocOpenWechatToken->load($Data, '');
                if ($BlocOpenWechatToken->load($Data, '') && $BlocOpenWechatToken->save()) {
                    return ResultHelper::json(200, '授权成功', $Data);
                } else {
                    $msg = ErrorsHelper::getModelError($BlocOpenWechatToken);
                    return ResultHelper::json(400, $msg);
                }
            }
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        }
    }

    /**
     * 关注公众号自动登录系统
     *
     * @param $FromUserName
     * @param $Ticket
     * @return void
     */
    public static function autoScanLogin($FromUserName, $Ticket)
    {
        loggingHelper::writeLog('openWeixin', 'OpenWechatAuthService', '发送模板消息', [
            'touser' => $FromUserName
        ]);
        $OpenWechatUser = new OpenWechatUser();
        $WechatUser     = OpenWechatUser::find()->where(['openid' => $FromUserName])->asArray()->one();
        $user           = self::getOfficialAccount()->user->get($FromUserName);
        $ddUser         = User::find()->where(['open_id' => $FromUserName])->select(['id', 'bloc_id', 'mobile'])->asArray()->one();
        try {
            if (empty($WechatUser)) { //第一次登录
                $OpenWechatUser->load([
                    'openid'   => $FromUserName,
                    'union_id' => $user['unionid'],
                    'bloc_id'  => $ddUser ? $ddUser['bloc_id'] : 0,
                    'user_id'  => $ddUser ? $ddUser['id'] : 0,
                ], '') && $OpenWechatUser->save();
            } else {
                $WechatUser          = OpenWechatUser::findOne(['openid' => $FromUserName]);
                $WechatUser->bloc_id = $ddUser ? $ddUser['bloc_id'] : 0;
                $WechatUser->user_id = $ddUser ? $ddUser['id'] : 0;
                $WechatUser->update();
            }
            $OfficialaccountQrcode = new OfficialaccountQrcode();
            $Qrcode                = $OfficialaccountQrcode->findOne(['ticket' => $Ticket]);
            $Qrcode->data          = Json::encode([
                'openid'   => $FromUserName,
                'union_id' => $user['unionid'],
                'bloc_id'  => $ddUser ? $ddUser['bloc_id'] : 0,
                'user_id'  => $ddUser ? $ddUser['id'] : 0,
            ]);
            $Qrcode->update();
            loggingHelper::writeLog('openWeixin', 'OpenWechatAuthService', '获取用户信息', ['user' => $user]);
            $user_id = $ddUser ? $ddUser['id'] : 0;
            $accoun  = self::getWechatInfo();

            if (empty($user_id)) {//未绑定账号
                $auth_url = env('WECHATOPENPLATFORMCONFIG_AUTH_URL', 'https://www.dandicloud.cn/store');

                $message = '欢迎关注' . $accoun['nick_name'] . '，' . PHP_EOL .
                    '1、已有账号，请点击<a href="' . $auth_url . '/login?openid=' . $FromUserName . '&redirect=%2F&timestamp=' . time() . '">登录并绑定账号</a>  以完成管理员绑定' . PHP_EOL .
                    '2、没有账号，请点击<a href="' . $auth_url . '/register?openid=' . $FromUserName . '&time='.time().'">快速注册</a>  ';
                $Res     = self::getOfficialAccount()->customer_service->message($message)->to($FromUserName)->send();
            } else {
                /**
                 * 发送登录成功
                 */
                $message = '欢迎关注' . $accoun['nick_name'] . '，' . PHP_EOL .
                    '登录成功';
                $Res     = self::getOfficialAccount()->customer_service->message($message)->to($FromUserName)->send();

            }


            loggingHelper::writeLog('openWeixin', 'OpenWechatAuthService', '发送模板消息-res', [
                'Res' => $Res
            ]);
        } catch (Exception|ErrorException $e) {
            loggingHelper::writeLog('openWeixin', 'OpenWechatAuthService', '发送模板消息-err', [
                'msg' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            loggingHelper::writeLog('openWeixin', 'OpenWechatAuthService', '发送模板消息-err', [
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * 快捷注册管理用户
     *
     * @throws Exception
     * @throws ErrorException
     * @throws Throwable
     */
    static function registerAdminUser($open_id, $union_id)
    {
        $adminUser           = new User();
        $maxId               = User::find()->max('id');
        $adminUser->open_id  = $open_id;
        $adminUser->union_id = $union_id;
        return $adminUser->signup($maxId + 1, $maxId + 1, ($maxId + 1) . '@cn.com', '123465', 1);
    }

    /**
     * 绑定
     *
     * @param mixed $FromUserName
     * @param string $Ticket
     * @return array
     */
    public static function autoUserBind(mixed $FromUserName, string $Ticket): array
    {
        $OpenWechatUser = new OpenWechatUser();
        $user_id        = OfficialaccountQrcode::find()->where(['ticket' => $Ticket])->select('user_id')->scalar();
        $WechatUser     = OpenWechatUser::find()->where(['openid' => $FromUserName])->orWhere(['user_id' => $user_id])->select(['user_id', 'user_id'])->one();

        loggingHelper::writeLog('openWeixin', 'autoUserBind', '绑定微信', [
            'user_id'    => $user_id,
            'WechatUser' => $WechatUser
        ]);
        try {
            $user = self::getOfficialAccount()->user->get($FromUserName);

            if (empty($WechatUser)) { //第一绑定登录
                $OpenWechatUser->load([
                    'openid'   => $FromUserName,
                    'union_id' => $user['unionid'],
                    'user_id'  => $user_id
                ], '') && $OpenWechatUser->save();
            } else {
                $WechatUser->openid   = $FromUserName;
                $WechatUser->union_id = $user['unionid'];
                $WechatUser->update();
            }

            $adminUser = User::find()->where(['open_id' => $FromUserName])->one();
            if ($adminUser) {
                loggingHelper::writeLog('openWeixin', 'autoUserBind', '绑定微信数据-重复绑定', [
                    'adminUser' => $adminUser
                ]);
                return ResultHelper::json(200, '绑定成功！', $adminUser->toArray());
            } else {
                $adminUser = User::findOne($user_id);
                loggingHelper::writeLog('openWeixin', 'autoUserBind', '绑定微信数据', [
                    'user'      => $user,
                    'adminUser' => $adminUser
                ]);
                $adminUser->open_id  = $user['openid'];
                $adminUser->union_id = $user['unionid'];
                $res                 = $adminUser->update();
                return ResultHelper::json(200, '绑定成功', (array) $res);
            }
        } catch (StaleObjectException $e) {
            loggingHelper::writeLog('openWeixin', 'autoUserBind', '绑定微信数据-err', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile()
            ]);
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        } catch (Throwable $e) {
            loggingHelper::writeLog('openWeixin', 'autoUserBind', '绑定微信数据-err', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile()
            ]);
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        }
    }

}