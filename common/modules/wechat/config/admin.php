<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-02-08 10:52:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-28 20:12:23
 */

return [
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['wechat/config'],
        'pluralize'     => false,
        'extraPatterns' => [
            'POST info' => 'info',
        ],
    ],

];
