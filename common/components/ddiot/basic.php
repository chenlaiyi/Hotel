<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-05-10 05:25:28
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-05-10 05:25:34
 */
require_once __DIR__ . '/../vendor/autoload.php';

use ddiot\http\AipBase;

// 配置信息
$config = [
    'app_id' => 'your_app_id',
    'app_secret' => 'your_app_secret',
    'project_sn' => 'your_project_sn',
    'is_dev' => true
];

// 初始化客户端
$client = new AipBase('1.0.0');

// 设置配置
$client->setConfig($config);

// 示例：发送请求
try {
    $data = [
        'device_id' => 'test_device',
        'timestamp' => time()
    ];

    $result = $client->post('/api/device/status', $data);
    print_r($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
