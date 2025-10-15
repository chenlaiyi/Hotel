<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-23 20:28:46
 */


namespace admin\modules\customer;

use Yii;

/**
 * app module definition class
 */
class site extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'admin\modules\customer\controllers';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        /**
         * 路由到admin应用下路由
         */
        if (Yii::$app->params['userType'] === 'admin') {
            Yii::$app->setControllerPath('@admin/controllers');
        }
        parent::init();
    }
}
