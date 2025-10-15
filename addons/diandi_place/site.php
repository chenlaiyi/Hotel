<?php
namespace addons\diandi_place;
use common\components\addons\AddonsModule;
/**
 * diandi_place module definition class
 */
class site extends AddonsModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'addons\diandi_place\backend';
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
        // custom initialization code goes here
    }
}
