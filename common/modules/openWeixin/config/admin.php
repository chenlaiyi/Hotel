<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-02-08 10:52:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-28 20:12:23
 */

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['openWeixin/basics'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET auth' => 'auth',
            'GET,POST auth-url' => 'auth-url',
            'GET,POST auth-wechat' => 'auth-wechat'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['openWeixin/wxapp'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST registerMiniprogram' => 'register-miniprogram',
            'POST register-miniprogram-by-offiaccount' => 'register-miniprogram-by-offiaccount',
            'POST register-beta-miniprogram' => 'register-beta-miniprogram',

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
