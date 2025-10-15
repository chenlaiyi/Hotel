<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/device/device'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'GET devinfo' => 'devinfo',
            'POST del' => 'del',
            'POST add' => 'add',
            'POST edit' => 'edit',
            'GET room-device' => 'room-device',
        ],
    ],
];