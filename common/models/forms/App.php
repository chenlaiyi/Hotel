<?php

namespace common\models\forms;

use yii\base\Model;

class App extends Model
{
    public $is_showall = false;
    public $id;

    public $bloc_id;
    /**
     * @var string application name
     */
    public $android_ver;
    public $android_url;
    public $ios_ver;
    public $ios_url;
    public $partner;
    public $partner_key;
    public $paysignkey;
    public $app_id;
    public $app_secret;
    public $regist;
    public $multinational;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [[
                'android_ver',
                'android_url',
                'ios_ver',
                'ios_url',
                'partner',
                'partner_key',
                'paysignkey',
                'app_id',
                'app_secret'
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
            'android_ver'=>'°²×¿°æ±¾',
            'android_url'=>'°²×¿×îÐÂ°æµØÖ·',
            'ios_ver'=>'ios°æ±¾',
            'ios_url'=>'ios×îÐÂ°æµØÖ·',
            'partner'=>'²Æ¸¶Í¨ÉÌ»§ºÅ',
            'partner_key'=>'²Æ¸¶Í¨ÃÜÔ¿',
            'paysignkey'=>'Ö§¸¶Ç©ÃûÃÜÔ¿',
            'app_id'=>'Î¢ÐÅ¿ª·ÅÆ½Ì¨app_id',
            'app_secret'=>'Î¢ÐÅ¿ª·ÅÆ½Ì¨app_secret',
            'regist' => ''
        ];
    }
}