<?php

namespace common\models\forms;

use yii\base\Model;

class Microapp extends Model
{
    public $is_showall = false;

    /**
     * @var string application name
     */
    public $name;
    public $id;

    public $bloc_id;
    /**
     * @var string admin email
     */
    public $description;
    public $original;
    public $AppId;
    public $AppSecret;
    public $headimg;
    public $codeUrl;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [[
                'name',
                'description',
                'original',
                'AppId',
                'AppSecret',
                'headimg',
                'codeUrl',
            ], 'string'],
            [['id', 'bloc_id'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'С��������',
            'description' => 'С��������',
            'original' => 'ԭʼid',
            'AppId' => 'AppId',
            'AppSecret' => 'AppSecret',
            'headimg' => '��ά��',
            'codeUrl'=>'��ͨ��ά������'
        ];
    }
}