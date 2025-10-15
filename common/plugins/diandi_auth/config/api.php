<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-16 11:38:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-17 10:09:13
 */



return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_auth/app'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET nav' => 'nav',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_auth/member'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list' => 'list',
            'GET role' => 'role',
            'POST role-money' => 'role-money',
            'POST permission' => 'permission',
            'POST set-role' => 'set-role',
            'POST set-money' => 'set-money',
            'POST add' => 'add',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_auth/base'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET yw-cate' => 'yw-cate',
            'GET xfmid' => 'xfmid',
            'GET xf-child' => 'xf-child',
            'GET xf-menu' => 'xf-menu',
            'GET xf-bt' => 'xf-bt',
            'GET xf-menu-item' => 'xf-menu-item',
        ],
    ]
];
