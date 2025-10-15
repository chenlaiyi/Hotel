<?php
return [
    // �첽�ص�
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/notify'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST,PUT callback' => 'call-back',
        ],
    ],
    // ����
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/public/api'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,POST rpc' => 'rpc',
            'GET,POST index' => 'index',
            'GET,POST ceshi' => 'ceshi',
            'GET,POST pay' => 'pay',
        ],
    ],
    // ����
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/public/wo'],
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
        'controller' => ['diandi_place/public/enums'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET index' => 'index',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/public/gzt'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST saveface' => 'save-face',
            'GET info' => 'info',
            'POST querysingle' => 'query-single',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/public/room'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST list' => 'list', //����
            'POST detail' => 'detail', //����
            'GET homestaydetail' => 'homestay-detail', //������������
            'GET homestayhzdetail' => 'homestayhz-detail', //���ⷿ������
            'GET placedetail' => 'placedetail', //�Ƶ귿������
            'GET child' => 'child', //�ӷ���
            'GET list' => 'list', //�б�
            'GET thumbs' => 'thumbs', //�������
            'GET evaluate' => 'evaluate', //���������б�
            'GET like' => 'like', //����ϲ��
            'GET homestay' => 'homestay', //���޹����Ƽ�
            'GET place' => 'place', //�Ƶ�����Ƽ�
            'GET apartment' => 'apartment', //��Ԣ�����Ƽ�
            'POST collect' => 'collect', //�����ղ�
            'POST statusInit' => 'status-init', //��̬ͳ��
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/public/place'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST detail' => 'detail',
            'POST like' => 'like',
            'POST evaluate' => 'evaluate',
            'POST rim' => 'rim',
            'GET listinit' => 'list-init',
            'GET list' => 'list',
            'GET tabs' => 'tabs',
            'GET ad' => 'ad',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['diandi_place/public/rim'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET list'=>'list'
        ],
    ]
];