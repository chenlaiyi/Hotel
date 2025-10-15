<?php


namespace common\plugins\diandi_auth;


use common\plugins\diandi_auth\events\LoginEvent;
use common\components\addons\AddonsModule;
use common\helpers\loggingHelper;
use common\modules\openWeixin\events\wechatUserLoginEvent;
use yii\base\Event;


/**
 * diandi_dingzuo module definition class.
 */
class api extends AddonsModule
{

    /**
     * {@inheritdoc}
     */

    public $controllerNamespace = "common\plugins\diandi_auth\api";


    /**
     * {@inheritdoc}
     */

    public function init(): void
    {
        parent::init();
        loggingHelper::writeLog('diandi_auth', 'init', '��ʼ��', [
            'addons' => $this->id
        ]);
        // �����¼�������ʵ��
        $handler = new LoginEvent();
        \Yii::$app->on(
            wechatUserLoginEvent::WECHAT_USER_LOGIN_EVENT,
            [$handler, 'wechatUserLoginEvent']
        );
        loggingHelper::writeLog('diandi_auth', 'init', '�¼��������', [
            'addons' => $this->id
        ]);
    }

}