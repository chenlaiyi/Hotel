<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-30 17:57:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-30 22:57:40
 */

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['openWeixin/msg'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST index' => 'index',
            'GET,POST event/<appid>' => 'event',
            'GET,POST open/<appid>' => 'open',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['openWeixin/basics'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET auth' => 'auth',
            'GET,POST auth-url' => 'auth-url',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['openWeixin/wechat'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST auth-url' => 'auth-url',
            'GET,POST user-by-code' => 'user-by-code',
        ],
    ],
];
