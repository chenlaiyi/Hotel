<?php

namespace common\modules\openWeixin\events\components;

use yii\base\Component;

class Foo extends Component
{
    const EVENT_HELLO = 'hello';

    public function bar()
    {
        $this->trigger(self::EVENT_HELLO);
    }
}