<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-01-18 16:51:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-03-30 22:15:17
 */

$hostname = env('REDIS_HOST', '127.0.0.1');
$port = env('REDIS_PORT', 6379);
$database = env('REDIS_DB', 0);
$password = env('REDIS_AUTH', null);

if ($password === '' || $password === false) {
    $password = null;
}

return [
    'class' => 'yii\redis\Connection',
    'hostname' => $hostname,
    'port' => (int) $port,
    'database' => (int) $database,
    'password' => $password,
];
