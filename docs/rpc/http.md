# HTTP

## ����
    
    �ڸ�Ŀ¼�µ� .env
    RPC_SERVER_IP = 127.0.0.1
    RPC_SERVER_PORT = 8080

## ����
    ������linux��ʹ��
    windows: swoole-cli rpc.php
    linux: php rpc.php

## ��չ�������
    
    common\rpc\AddonsRpcService.php

## module ����
    
    �Բ��diandi_hotelΪ��������rpcģ��
    addons\diandi_hotel\rpc
    
### ����ʾ��

```php
<?php

namespace addons\diandi_hotel\rpc;

use common\pdo\BaseAbstractServiceModule;use common\pdo\PdoPoolContainer;


class Device extends BaseAbstractServiceModule
{
    public static string $moduleName = 'Device';

    function moduleName(): string
    {
        return 'Device';
    }

    function ceshi()
    {
        $pool = PdoPoolContainer::getInstance()->get('pdoPool');
        $pdo = $pool->get();
        $statement = $pdo->prepare("select * from dd_user where id = ?");
        if (!$statement) {
            throw new \RuntimeException('Prepare failed');
        }

        $result = $statement->execute([11]);
        if (!$result) {
            throw new \RuntimeException('Execute failed');
        }
        $result = $statement->fetchAll();
//                if ($a + $b !== (int)$result[0][0]) {
//                    throw new \RuntimeException('Bad result');
//                }
    
        $pool->put($pdo);
        $this->response()->setMsg(['a'=>1221,'b'=>$result]);
    }


}
```

## ���ݿ����ӳ�

    �������ӳ��ͷź���Ҫ��$pool->put($pdo);

```php
        $pool = PdoPoolContainer::getInstance()->get('pdoPool');
        $pdo = $pool->get();
        $statement = $pdo->prepare("select * from dd_user where id = ?");
        if (!$statement) {
            throw new \RuntimeException('Prepare failed');
        }

        $result = $statement->execute([11]);
        if (!$result) {
            throw new \RuntimeException('Execute failed');
        }
        $result = $statement->fetchAll();
//                if ($a + $b !== (int)$result[0][0]) {
//                    throw new \RuntimeException('Bad result');
//                }
    
        $pool->put($pdo);
```

### �������ӳ�

    �������ӳ��ͷź���Ҫ��$pool->put($redis);
```php
            $pool = PdoPoolContainer::getInstance()->get('redisPool');

            $redis = $pool->get();
            $result = $redis->set('foo', 'bar');
            if (!$result) {
                throw new RuntimeException('Set failed');
            }
            $result = $redis->get('foo');
            if ($result !== 'bar') {
                throw new RuntimeException('Get failed');
            }
            $pool->put($redis);
```

### �����ַ

    http://127.0.0.1:8080/addons/device/ceshi

### addons rpc��������
### device rpcģ������
### ceshi rpcģ�鷽������

# ��������
    
    ������ rpcģ�鷽�� ceshi �е���
```php
use Simps\MQTT\Message\SubAck;
use Simps\MQTT\Protocol\ProtocolInterface;

$codes = [0];
$message_id = 8520;

$ack = new SubAck();
$ack->setCodes($codes)
    ->setMessageId($message_id);

$ack_data = $ack->getContents();
$ack_data = (string) $ack;

// MQTT5
$ack->setProtocolLevel(ProtocolInterface::MQTT_PROTOCOL_LEVEL_5_0)
    ->setCodes($codes)
    ->setMessageId($message_id)
    ->setProperties([
        'will_delay_interval' => 60,
        'message_expiry_interval' => 60,
    ]);

$ack_data = $ack->getContents();
$ack_data = (string) $ack;

```
    


    

