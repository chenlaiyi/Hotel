<?php


namespace common\plugins\diandi_auth;


use yii\base\BootstrapInterface;
use common\components\addons\AddonsModule;


/**
 * diandi_dingzuo module definition class.
 */
class admin extends AddonsModule implements BootstrapInterface
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = "common\plugins\diandi_auth\admin";

    /**
     * {@inheritdoc}
     */

    public function init(): void
    {

        parent::init();

    }

    public function bootstrap($app)
    {
    }

}