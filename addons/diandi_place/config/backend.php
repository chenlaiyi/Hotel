<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-21 10:32:51
 */
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/enums'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index'
        ],
    ],
    // 酒店-品牌
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/brand'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET enums' => 'enums',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-品牌
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/place-type'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET enums' => 'enums',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
//    房东
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/landlord'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'GET enums' => 'enums',
            'POST create' => 'create',
            'POST userlist' => 'userlist',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-楼层
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/tier'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST list' => 'list',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-评论
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/comment'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-星级评论
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/commentstars'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-配置
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/config'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST blocSet' => 'bloc-set',
            'POST bloc' => 'bloc',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-酒店管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/place'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST list' => 'list',
            'POST typelist' => 'typelist',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-区域管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/region'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-服务管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/server'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST list' => 'list',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 酒店-国家管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place/country'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST list' => 'list',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 会员-会员管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/member/member'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 会员-会员优惠券
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/member/membercoupon'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 房间-房间管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room/type'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,GET list' => 'list',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 房间-房间管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room/room'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST list' => 'list',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 房间-房间人员管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room/roompersions'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 房间-房价维护
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room/roomprice'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 房间-房间相册
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room/roomslide'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
    // 房间-房间服务
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room/server'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
            'POST create' => 'create',
            'POST,PUT update/<id>' => 'update',
            'POST,GET,DELETE delete/<id>' => 'delete',
            'GET view/<id>' => 'view',
        ],
    ],
];
