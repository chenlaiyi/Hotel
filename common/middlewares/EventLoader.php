<?php

namespace common\middlewares;

use yii\base\BootstrapInterface;

class EventLoader  implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->on(\yii\web\Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->user->enableSession = false;
        });
    }

}