
# ���ط���������rpc

    ���ط�����Ĭ��rpc����˿�Ϊ 9600

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
    'service' => 'ServiceOne', // ��Ҫ���õķ�������
    'module'  => 'ModuleOne', // ��Ҫ���õķ����µ���ģ������
    'action'  => 'action',  // ��Ҫ���õķ����µ���ģ��ķ�������
    'arg'     => ['a', 'b', 'c'], // ��Ҫ���ݵĲ���
];

$raw = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// tcp://127.0.0.1:9600��ʾ�������ַ�� �� rpc ����˵ĵ�ַ�������Ǳ��أ�����ʹ�� 127.0.0.1
// ��������Ҫ����ʵ������������е���
$fp = stream_socket_client('tcp://127.0.0.1:9600');
fwrite($fp, pack('N', strlen($raw)) . $raw); // pack ����У��

$try = 3;
$data = fread($fp, 4);
if (strlen($data) < 4 && $try > 0) {
    $data .= fread($fp, 4);
    $try--;
    usleep(1);
}

// ������ͷ��У��
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
    // ����Ƿ���˷��صĽ��
    var_dump($data);
}

fclose($fp);
```