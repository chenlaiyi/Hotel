<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-06 16:57:29
 */
return [
    //    ����ӿڿ�ʼ
    // ����
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
    // ����
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
    //�Ƶ�
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/place/list'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST add' => 'add',
            'POST edit' => 'edit',
            'POST del' => 'del',
            'GET index' => 'index',
            'GET list' => 'list',
        ],
    ],
    // ��Դ����
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/place/type'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST edit' => 'edit',
            'POST add' => 'add',
            'POST del' => 'del',
            'POST set-default' => 'set-default',
            'GET list' => 'list',
        ],
    ],
    // ��������
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/config/base'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST set' => 'set',
            'GET info' => 'info',
        ],
    ],
    // ¥��
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
    // ¥��
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
    // ��Ԫ
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
    // ����
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
    // ����
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
    // �������
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
    // ����
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
    // ����
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/manage/landlord/info'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET detail' => 'detail',
            'POST edit' => 'edit',
            'POST init' => 'init',
            'GET country' => 'country',
            'POST setType' => 'set-type',
            'POST password' => 'password',
            'POST identity' => 'identity',
            'POST landlord' => 'landlord',
            'GET message' => 'message',
        ],
    ],
    //Э��
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/agreement/index'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST contract' => 'contract',
            'POST upcontract' => 'upcontract',
        ],
    ]
];
