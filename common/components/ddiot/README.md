# DDIOT SDK for PHP

DDIOT SDK 是一个用于访问 DDIOT 物联网平台的 PHP SDK。它提供了简单易用的接口，帮助开发者快速集成 DDIOT 平台的功能。

## 安装

使用 Composer 安装：

```bash
composer require ddiot/sdk
```

## 配置

在使用 SDK 之前，需要先进行配置：

```php
$config = [
    'app_id' => 'your_app_id',        // 应用ID
    'app_secret' => 'your_app_secret', // 应用密钥
    'project_sn' => 'your_project_sn', // 项目编号
    'is_dev' => true                  // 是否开发环境
];
```

## 使用示例

```php
use ddiot\http\AipBase;

// 初始化客户端
$client = new AipBase('1.0.0', true);

// 设置配置
$client->setConfig($config);

// 发送请求
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
```

## 主要功能

1. 设备管理
2. 数据上报
3. 命令下发
4. 状态查询

## 错误处理

SDK 使用异常机制处理错误，所有可能的错误都会抛出异常。建议使用 try-catch 进行错误处理：

```php
try {
    $result = $client->post('/api/device/status', $data);
} catch (Exception $e) {
    // 处理错误
    echo "Error: " . $e->getMessage() . "\n";
}
```

## 开发环境

- PHP >= 7.4
- 需要 PHP 的 curl 扩展
- 需要 PHP 的 json 扩展

## 许可证

MIT License