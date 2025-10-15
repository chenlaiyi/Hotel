<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id'                  => 'app-install',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'install\controllers',
    'components'          => [
        'request'  => [
            'class'                  => 'common\components\ExtendedRequest',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey'    => 'a2JT39LPV_JRdgCv4HchqUzCgefuAQUT',
            'enableCookieValidation' => true,
            'enableCsrfValidation'   => true,
            'charset'                => 'UTF-8', // 请求相关字符集
        ],
        'response' => [
            'charset'    => 'UTF-8', // 响应字符集
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class'         => \yii\web\JsonResponseFormatter::class,
                    'encodeOptions' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE,
                ],
            ],
        ],

        'user'         => [
            'class'           => 'yii\web\User',
            'identityClass'   => 'admin\models\DdApiAccessToken',
            'enableAutoLogin' => true,
            'enableSession'   => true,
            'loginUrl'        => null,
            'identityCookie'  => ['name' => '_identity-install', 'httpOnly' => true],
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager'  => [
            'class'        => 'diandi\\admin\\components\\DbManager', // 使用数据库管理配置文件
            'defaultRoles' => ['基础权限组'], //默认角色
        ],
    ],
    'params'              => $params,
];


return $config;
