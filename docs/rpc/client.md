
# 本地服务器调用rpc

    本地服务器默认rpc服务端口为 9600

```php
<?php
/**
 * This file is part of EasySwoole.
 *
 * @link https://www.easyswoole.com
 * @document https://www.easyswoole.com
 * @contact https://www.easyswoole.com/Preface/contact.html
 * @license https://github.com/easy-swoole/easyswoole/blob/3.x/LICENSE
 */

$data = [
    'service' => 'ServiceOne', // 需要调用的服务名称
    'module'  => 'ModuleOne', // 需要调用的服务下的子模块名称
    'action'  => 'action',  // 需要调用的服务下的子模块的方法名称
    'arg'     => ['a', 'b', 'c'], // 需要传递的参数
];

$raw = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// tcp://127.0.0.1:9600（示例请求地址） 是 rpc 服务端的地址，这里是本地，所以使用 127.0.0.1
// 开发者需要根据实际情况调整进行调用
$fp = stream_socket_client('tcp://127.0.0.1:9600');
fwrite($fp, pack('N', strlen($raw)) . $raw); // pack 数据校验

$try = 3;
$data = fread($fp, 4);
if (strlen($data) < 4 && $try > 0) {
    $data .= fread($fp, 4);
    $try--;
    usleep(1);
}

// 做长度头部校验
$len = unpack('N', $data);
$data = '';
$try = 3;
if (strlen($data) < $len[1] && $try > 0) {
    $data .= fread($fp, $len[1]);
    $try--;
    usleep(1);
}

if (strlen($data) != $len[1]) {
    echo 'data error';
} else {
    $data = json_decode($data, true);
    // 这就是服务端返回的结果
    var_dump($data);
}

fclose($fp);
```