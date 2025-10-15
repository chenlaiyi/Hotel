# ģ��

## ����ģ����

    ����ģ������Ҫ�̳�`\common\components\ActiveRecord\YiiActiveRecord`��

## ��������
    
    ����Ĭ��Ϊ`id`�������������`id`����Ҫͨ��`@Property(isPrimaryKey: true)`ע��ָ������

## �����ֶ�
    
    �ϸ�ģʽ��Ҫ��ģ�����ж����ֶΣ����ϸ�ģʽ������yii��rules,rules�е��ֶα��������Ϊ˽���������
    
    �ֶ�Ĭ��Ϊ`id`������ֶβ���`id`����Ҫͨ��`@Property`ע��ָ���ֶ�
    
## �������
    
### asArray() : ���rules�ж�����ֶ�
    $user = User::findRecord(['mobile'=> $mobile])->asArray();
### toArray() : ���˽�����Զ�����ֶ�    
    $user = User::findRecord(['mobile'=> $mobile])->toArray();

## ����ʾ��

```php
<?php

namespace addons\diandi_hotel\models\Rpc;



use EasySwoole\FastDb\Attributes\Property;

class ceshiRpcModel extends \common\components\ActiveRecord\YiiActiveRecord
{
    #[Property(isPrimaryKey: true)]
    public int $id;
    
    #[Property]
    public ?int $store_id;

    #[Property]
    public ?int $bloc_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_switch_ceshi}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [[
                'bloc_id',
                'store_id',
            ], 'integer'],
            [['create_time', 'update_time'], 'safe']
        ];
    }
}
```
