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
        'controller' => ['officialaccount/jssdk'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST  config' => 'config',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/basics'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET auth' => 'auth',
            'GET,POST auth-url' => 'auth-url',
            'GET,POST check-login' => 'check-login',
            'GET,POST auth-wechat' => 'auth-wechat',
            'GET,POST qrcode' => 'qrcode',
            'GET,POST unbind' => 'unbind'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/media'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'GET list' => 'list',
            'POST add' => 'create',
            'POST listAll' => 'listAll',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/news'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST list' => 'list',
            'POST count' => 'count',
            'POST up-wx' => 'up-wx',
            'POST synchronous' => 'synchronous',
            'DELETE delete-new/<mediaId>' => 'delete-new'
        ],
    ],
    // 公众号配置接口
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/config'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST info' => 'info'
        ],
    ],
    // 公众号接口
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/menu'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST list' => 'list',
            'POST edit' => 'edit',
            'POST list-tree-menu' => 'list-tree-menu',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/basics'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,HEAD signup' => 'signup',
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
            'GET,POST event' => 'event',
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
            'DELETE deletes' => 'deletes',
        ],
    ],
    // 公众号接口
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/basics'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,HEAD signup' => 'signup',
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
            'GET,POST event' => 'event',
            'GET,POST open' => 'open',
        ],
    ],
    //模板消息
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/msg-template'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST data' => 'data',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/send'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST msg' => 'msg',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/template'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST sync-wx-template' => 'sync-wx-template',
            'GET,POST event' => 'event',
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
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/usertag'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST create-tag' => 'create-tag',
            'POST up-tag' => 'up-tag',
            'POST del-tag' => 'del-tag',
            'POST user-tags' => 'user-tags',
            'POST tag-users' => 'tag-users',
            'POST untag-users' => 'untag-users',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/receive'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/subscribe'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/fans'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST synchronous' => 'synchronous',
            'POST sendMsg' => 'sendMsg',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/account'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST listAll' => 'listAll',
            'POST generateQRUrl' => 'generateQRUrl',
            'POST listTreeWxAccount' => 'listTreeWxAccount',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/msg'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST updateResContent' => 'updateResContent',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/menu'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST listTreeMenu' => 'listTreeMenu',
            'POST validateData' => 'validateData',
            'POST syncAccountMenu' => 'syncAccountMenu',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/news'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST addNews' => 'addNews',
            'POST getNews' => 'getNews',
            'POST uploadNews' => 'uploadNews',
            'POST listAll' => 'listAll',
            'POST listAll2' => 'listAll2',
            'POST filterFans' => 'filterFans',
            'POST sendNewsPreview' => 'sendNewsPreview',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['officialaccount/text'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'POST add' => 'create',
            'POST listAll' => 'listAll',
            'POST update/<id>' => 'update',
            'POST delete/<id>' => 'delete',
        ],
    ]
];
