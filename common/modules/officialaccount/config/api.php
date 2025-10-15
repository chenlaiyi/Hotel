<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-30 17:57:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-30 22:57:40
 */

return [
    // 公众号接口
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/basics'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,GET ceshi' => 'ceshi',
            'POST,HEAD signup' => 'signup',
            'POST,HEAD bind-openid' => 'bind-openid',
            'GET,POST,HEAD auth' => 'auth',
            'GET,POST,HEAD userinfo' => 'userinfo',
            'POST,HEAD payparameters' => 'payparameters',
            'POST,HEAD payappparameters' => 'payappparameters',
            'POST,HEAD,GET,PUT notify' => 'notify',
        ],
    ],
    // 激活公众号开发模式
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/msg'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST index' => 'index',
            'GET,POST event/<appid>' => 'event',
            'GET,POST open' => 'open',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/jssdk'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST  config' => 'config',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/qrcode'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,GET getqrcode' => 'qrcode',
        ],
    ]
];
