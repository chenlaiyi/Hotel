<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 11:48:59
 */


$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'app-develop',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'develop\controllers',
    'components' => [
        'request' => [
            'class' => 'common\components\ExtendedRequest',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a2JT39LPV_JRdgCv4HchqUzCgefuAQUT',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'admin\models\DdApiAccessToken',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ]
    ],
    'params' => $params,
];


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'viewPath' => '@develop/views/gii',
        /*自定义*/
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.137.1'],
        'controllerNamespace' => 'develop\controllers',
        'generators' => [
            'addons' => [
                'class' => 'addonstpl\addons\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/addons/default',
                ],
            ],
            'plugins' => [
                'class' => 'addonstpl\plugins\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/plugins/default',
                ],
            ],
            'model' => [
                'class' => 'addonstpl\model\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/model/default',
                ],
            ],
            'adminapi' => [
                'class' => 'addonstpl\adminapi\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/adminapi/default',
                ],
            ],
            'crud' => [ //生成器名称
                'class' => 'addonstpl\crud\Generator',
                'templates' => [ //设置我们自己的模板
                    //模板名 => 模板路径
                    'myCrud' => '@console/gii/giitpl/crud/default',
                ],
            ],
            'module' => [
                'class' => 'addonstpl\module\Generator',
                'templates' => [
                    'addons' => '@console/gii/giitpl/module/default',
                ],
            ],
            'controller' => [
                'class' => 'addonstpl\controller\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/controller/default',
                ],
            ],
            'form' => [
                'class' => 'addonstpl\form\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/form/default',
                ],
            ],
            'extension' => [
                'class' => 'addonstpl\extension\Generator',
                'templates' => [
                    'default' => '@console/gii/giitpl/extension/default',
                ],
            ],
        ],
    ];
}

return $config;
