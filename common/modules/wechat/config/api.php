<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-30 17:57:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-30 22:57:40
 */

return [
    // 小程序接口
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/basics'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST share'                       => 'share',
            'POST,HEAD signup'                 => 'signup',
            'POST,HEAD payparameters'          => 'payparameters',
            'POST,HEAD,GET,PUT refundednotify' => 'refundednotify',
            'POST,HEAD,GET,PUT notify'         => 'notify',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/sendmsg'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST,HEAD send' => 'send',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/qrcode'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST  create-qrcode' => 'create-qrcode',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/decrypt'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST  msg' => 'msg',
        ],
    ],
    // 小程序接口
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/basics'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST share'                       => 'share',
            'POST,HEAD signup'                 => 'signup',
            'POST,HEAD payparameters'          => 'payparameters',
            'POST,HEAD,GET,PUT refundednotify' => 'refundednotify',
            'POST,HEAD,GET,PUT notify'         => 'notify',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/sendmsg'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST,HEAD send' => 'send',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/qrcode'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST  getqrcode' => 'getqrcode',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/decrypt'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST  msg'    => 'msg',
            'POST  mobile' => 'mobile',
        ],
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/backend'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST fast_login'  => 'fast-login',
            'POST union_login' => 'union-login',
        ],
    ],
];
