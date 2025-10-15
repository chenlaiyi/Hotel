<?php

namespace common\events;

class AddonsEvent extends \yii\base\Event
{
    const EVENT_ADDONS = 'AddonsEvent';
    public array $addons = [];
    public $method;
    public $params;

}