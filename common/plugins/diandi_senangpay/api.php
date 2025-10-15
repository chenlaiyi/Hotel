<?php

namespace common\plugins\diandi_senangpay;

use common\components\addons\PluginsModule;

/**
* diandi_dingzuo module definition class.
*/
class api extends PluginsModule
{
/**
* {@inheritdoc}
*/
public $controllerNamespace = "common\plugins\diandi_senangpay\api";

/**
* {@inheritdoc}
*/
public function init(): void
{
parent::init();
}
}