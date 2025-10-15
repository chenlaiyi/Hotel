## �¼�ע��
    ���ó���
    ģ���¼������� ThinkPHP ���ģ�͵�ģ���¼���������������д�����ݿ�֮ǰ��һЩԤ���������
    
    ģ���¼���ָ�ڽ���ģ�͵�д�������ʱ�򴥷��Ĳ�����Ϊ����������ģ�Ͷ���� insert��delete��update �����Լ���ʵ������ʼ��ʱ������
    
    ģ����֧�� OnInitialize��OnInsert��OnDelete��OnUpdate �¼���

### �¼���Ϊע��	����

    OnInitialize	ʵ�屻ʵ����ʱ����
    OnInsert	����ǰ
    OnDelete	ɾ��ǰ
    OnUpdate	����ǰ
## ʹ��ʾ��

    �����¼�ע��
    ��ģ�����п���ͨ��ע�⼰�����෽����ʵ���¼�ע���������������ʾ��
```php
<?php
declare(strict_types=1);

namespace EasySwoole\FastDb\Tests\Model;

use EasySwoole\FastDb\AbstractInterface\AbstractEntity;
use EasySwoole\FastDb\Attributes\Hook\OnInitialize;
use EasySwoole\FastDb\Attributes\Hook\OnInsert;
use EasySwoole\FastDb\Attributes\Hook\OnDelete;
use EasySwoole\FastDb\Attributes\Hook\OnUpdate;
// ...

/**
 * @property int    $id
 * @property string $name
 * @property int    $status
 * @property int    $score
 * @property int    $create_time
 */
#[OnInitialize('onInitialize')]
#[OnInsert('onInsert')]
#[OnDelete('onDelete')]
#[OnUpdate('onUpdate')]
class User extends AbstractEntity
{
    // ...

    public function onInitialize()
    {
        // todo::
    }

    public function onInsert()
    {
        if (empty($this->status)) {
            return false;
        }
        if (empty($this->create_time)) {
            $this->create_time = time();
        }
    }

    public function onDelete()
    {
        // todo::
    }

    public function onUpdate()
    {
        // todo::
    }
}

```
    
    ���涨���� OnInitialize��OnInsert��OnDelete��OnUpdate �¼�ע�⣬����ע����ͨ������ #[OnInitialize('onInitialize')] �ķ�ʽ�� OnInitialize ע�⴫�����������Ӧ���¼���Ϊ�����¼�������ʱִ�еĻص� onInitialize��onInsert ��onDelete��onUpdate��
    
    ���õĻص��������Զ�����һ����������ǰ��ģ�Ͷ���ʵ���������� OnInsert��OnDelete��OnUpdate �¼��Ļص�����(onInsert ��onDelete��onUpdate) ������� false���򲻻����ִ�С�

## ʹ��
```php

    $user = new User(['name' => 'EasySwoole', 'id' => 1000]);
    $result = $user->insert();
    var_dump($result); // false������ false����ʾ insert ʧ�ܡ�
```