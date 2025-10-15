<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-06 16:57:29
 */
$device = require __DIR__ . '/device.php';
$manage = require __DIR__ . '/manage.php';
$public = require __DIR__ . '/public.php';
return array_merge($public, $device, $manage);
