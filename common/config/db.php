<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-01-18 16:51:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-26 15:23:56
 */

$dbHost = env('DB_HOST', '127.0.0.1');
$dbPort = env('DB_PORT', 3306);
$dbName = env('DB_NAME', 'hotel_ddg_org_cn');
$dbUser = env('DB_USER', 'hotel_ddg_org_cn');
$dbPass = env('DB_PASS', 'ZditcmBs8Fk8zMan');
$dbPrefix = env('DB_PREFIX', 'dd_');

return [
    'class'               => 'yii\db\Connection',
    'dsn'                 => 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';port=' . $dbPort,
    'username'            => $dbUser,
    'password'            => $dbPass,
    'tablePrefix'         => $dbPrefix,
    'charset'             => 'utf8mb4',
    'attributes'          => [
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES  => false,
    ],
    'enableSchemaCache'   => false,
    // Duration of schema cache.
    'schemaCacheDuration' => 3600,
    // Name of the cache component used to store schema information
    'schemaCache'         => 'cache',
];
