## 跨模块事件处理

```php
Yii::$app->trigger(AddonsEvent::EVENT_ADDONS, new AddonsEvent([
            'addons' => 'diandi_tea',
            'method' => 'roomInit',
            'params'=> [
                'room_id' => $this->id,
                'room_type_id' => $this->room_type_id
            ]
        ]));
```