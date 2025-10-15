<?php

namespace common\modules\openWeixin\controllers\admin;

use api\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\openWeixin\events\wechatUserLoginEvent;
use Yii;

Class WechatController extends AController
{
    protected array $authOptional = ['auth-url','user-by-code'];

    public function actionAuthUrl(): array
    {
        $openPlatform = Yii::$app->OpenApp->getApp();
        $redirectUri = Yii::$app->request->input('redirect_uri');
        if (is_array($openPlatform) && key_exists('code', $openPlatform) && $openPlatform['code'] !=200){
            return ResultHelper::json(400, $openPlatform['message']);
        }
        $redirectUrl = $openPlatform->oauth->scopes(['snsapi_userinfo'])
            ->redirect($redirectUri);
        return ResultHelper::json(200, 'success',[
            'url' => $redirectUrl
        ]);
    }

    function actionUserByCode(): array
    {
        $openPlatform = Yii::$app->OpenApp->getApp();
        $code = Yii::$app->request->input('code');
        $user = $openPlatform->oauth->userFromCode($code);
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $event = new wechatUserLoginEvent();
        $event->openid = $user->getId();
        $event->member_id = $member_id;
        $addons = Yii::$app->request->input('addons');
        Yii::$app->getModule($addons);
        Yii::$app->trigger($event::WECHAT_USER_LOGIN_EVENT, $event);
        return ResultHelper::json(200, '获取成功', ['user'=>$user->toArray()]);
    }

}
