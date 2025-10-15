<?php


namespace common\plugins\diandi_auth;



use common\components\addons\AddonsModule;



/**

 * diandi_dingzuo module definition class.

 */

class console extends AddonsModule

{

    /**

     * {@inheritdoc}

     */

    public $controllerNamespace = "plugins\diandi_auth\console";



    /**

     * {@inheritdoc}

     */

    public function init(): void

    {

        parent::init();

    }

}

