<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-06 16:57:29
 */
return [
    //    管理接口开始
    // 民宿
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/apartment/list'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST del' => 'del',
            'GET list' => 'list',
            'GET p-room-list' => 'p-room-list',
        ],
    ],
    // 民宿
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/homestay/list'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST del' => 'del',
            'GET list' => 'list',
            'GET p-room-list' => 'p-room-list',
        ],
    ],
    //酒店
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/place/list'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST edit' => 'edit',
            'POST del' => 'del',
            'GET index' => 'index',
            'POST customer' => 'customer',
            'GET list' => 'list',
        ],
    ],
    // 房源类型
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/place/type'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit' => 'edit',
            'POST set-default' => 'set-default',
            'POST add' => 'add',
            'POST del' => 'del',
            'GET list' => 'list',
        ],
    ],
    // 公共配置
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/config/base'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST set' => 'set',
            'GET info' => 'info',
        ],
    ],
    // 楼栋
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/config/building'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST adds' => 'adds',
            'GET info' => 'info',
        ],
    ],
    // 楼层
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/config/tier'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST edit' => 'edit',
            'POST adds' => 'adds',
            'POST del' => 'del',
            'GET list' => 'list',
            'GET info' => 'info',
        ],
    ],
    // 单元
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/config/unit'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'GET list' => 'list',
            'POST adds' => 'adds',
            'GET info' => 'info',
        ],
    ],
    // 房型
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/room/type'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'GET list' => 'list',
            'GET edit' => 'edit',
            'GET info' => 'info',
            'GET detail' => 'detail',
            'GET del' => 'del',
        ],
    ],
    // 房间
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/room/list'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST index-statistics' => 'index-statistics',
            'POST add' => 'add',
            'POST adds' => 'adds',
            'GET list' => 'list',
            'POST del' => 'del',
            'GET situation' => 'situation',
            'POST edit' => 'edit',
            'POST status-init' => 'status-init',
            'GET detail' => 'detail',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/room/temp'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST occupancy' => 'occupancy',
            'GET person-list' => 'person-list',
            'GET frozen' => 'frozen',
            'GET out-room' => 'out-room',
            'GET out-room-all' => 'out-room-all',
        ],
    ],
    // 房间服务
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/room/server'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'GET del' => 'del',
            'GET list' => 'list',
        ],
    ],
    // 房客
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/tenant/list'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST detail' => 'detail',
            'GET list' => 'list',
        ],
    ],
    // 房东
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/landlord/info'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET detail' => 'detail',
            'POST edit' => 'edit',
            'POST init' => 'init',
            'GET country' => 'country',
            'GET list' => 'list',
            'POST setType' => 'set-type',
            'POST password' => 'password',
            'POST identity' => 'identity',
            'POST landlord' => 'landlord',
            'GET message' => 'message',
        ],
    ],
    // 异步回调
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/notify'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST,PUT callback' => 'call-back',
        ],
    ],
    // 测试
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/api'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST rpc' => 'rpc',
            'GET,POST index' => 'index',
            'GET,POST pay' => 'pay',
        ],
    ],
    // 公共
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/wo'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST person-add' => 'person-add',
            'POST face-add' => 'face-add',
            'POST auth-device' => 'auth-device',
            'POST delete-device' => 'delete-device',
            'GET,POST,PUT webhook' => 'webhook',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/enums'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
        ],
    ],
    //协议
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/agreement/index'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST contract' => 'contract',
            'POST upcontract' => 'upcontract',
        ],
    ],
    // pc首页
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/pc/index'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET location' => 'location', //广告数据
            'GET region' => 'region', //广告数据
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/gzt'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST saveface' => 'save-face',
            'GET info' => 'info',
            'POST querysingle' => 'query-single',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/room'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST list' => 'list', //检索
            'POST detail' => 'detail', //检索
            'GET homestaydetail' => 'homestay-detail', //民宿整租详情
            'GET homestayhzdetail' => 'homestayhz-detail', //合租房间详情
            'GET placedetail' => 'placedetail', //酒店房间详情
            'GET child' => 'child', //子房间
            'GET list' => 'list', //列表
            'GET thumbs' => 'thumbs', //房间相册
            'GET evaluate' => 'evaluate', //房间评价列表
            'GET like' => 'like', //猜你喜欢
            'GET homestay' => 'homestay', //民宿关联推荐
            'GET place' => 'place', //酒店关联推荐
            'GET apartment' => 'apartment', //公寓关联推荐
            'POST collect' => 'collect', //房间收藏
            'POST statusInit' => 'status-init', //房态统计
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST detail' => 'detail',
            'POST like' => 'like',
            'POST evaluate' => 'evaluate',
            'POST rim' => 'rim',
        ],
    ],
    // 设备开始
    // 门锁
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
    // 楼栋
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/place'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET listinit' => 'list-init',
            'GET tabs' => 'tabs',
            'GET ad' => 'ad',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/rim'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list'=>'list'
        ],
    ]
];
