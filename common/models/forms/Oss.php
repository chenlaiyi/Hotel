<?php

namespace common\models\forms;

use yii\base\Model;

class Oss extends Model
{
    public $is_showall = false;

    /**
     * @var string application name
     */
    public $id;
    public $bloc_id;

    public $Aliyunoss_accessKeyId;
    public $Aliyunoss_resource;
    public $Aliyunoss_accessKeySecret;
    public $Aliyunoss_bucket;
    public $Aliyunoss_url;
    public $Aliyunoss_endPoint;
    public $Tengxunoss_APPID;
    public $Tengxunoss_SecretID;
    public $Tengxunoss_SecretKEY;
    public $Tengxunoss_Bucket;
    public $Tengxunoss_area;
    public $Tengxunoss_url;
    public $Qiniuoss_Accesskey;
    public $Qiniuoss_Secretkey;
    public $Qiniuoss_Bucket;
    public $Qiniuoss_url;
    public $remote_type;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [[
                'Aliyunoss_accessKeyId',
                'Aliyunoss_resource',
                'Aliyunoss_bucket',
                'Aliyunoss_accessKeySecret',
                'Aliyunoss_url',
                'Aliyunoss_endPoint',
                'Tengxunoss_APPID',
                'Tengxunoss_SecretID',
                'Tengxunoss_SecretKEY',
                'Tengxunoss_Bucket',
                'Tengxunoss_area',
                'Tengxunoss_url',
                'Qiniuoss_Accesskey',
                'Qiniuoss_Secretkey',
                'Qiniuoss_Bucket',
                'remote_type',
                'Qiniuoss_url',
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
            'Aliyunoss_accessKeyId' => 'Aliyunoss_accessKeyId',
            'Aliyunoss_resource' => 'Aliyunoss_resource',
            'Aliyunoss_accessKeySecret' => 'Aliyunoss_accessKeySecret',
            'Aliyunoss_url' => 'Aliyunoss_url',
            'Tengxunoss_APPID' => 'Tengxunoss_APPID',
            'Tengxunoss_SecretID' => 'Tengxunoss_SecretID',
            'Tengxunoss_SecretKEY' => 'Tengxunoss_SecretKEY',
            'Tengxunoss_Bucket' => 'Tengxunoss_Bucket',
            'Tengxunoss_area' => 'Tengxunoss_area',
            'Tengxunoss_url' => 'Tengxunoss_url',
            'Qiniuoss_Accesskey' => 'Qiniuoss_Accesskey',
            'Qiniuoss_Secretkey' => 'Qiniuoss_Secretkey',
            'Qiniuoss_Bucket' => 'Qiniuoss_Bucket',
            'Qiniuoss_url' => 'Qiniuoss_url',
        ];
    }
}