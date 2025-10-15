<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-21 10:32:51
 */
$app = require __DIR__ . '/app.php';
$admin = require __DIR__ . '/backend.php';
return array_merge($admin, $app);
