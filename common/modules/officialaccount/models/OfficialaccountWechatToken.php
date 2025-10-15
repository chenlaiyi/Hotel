<?php

namespace common\modules\officialaccount\models;

use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;

/**
 * 微信token
 * This is the model class for table "{{%officialaccount_wechat_token}}".
 *
 * @property int $id 粉丝id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string|null $update_time 更新时间
 * @property string|null $create_time
 * @property string|null $access_token AccessToken(访问令牌)
 * @property string|null $token 微信服务Token(令牌)
 * @property string|null $encoding_aes_key 消息加密秘钥EncodingAesKey
 * @property string|null $apiUrl API地址
 */
class OfficialaccountWechatToken extends ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_wechat_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id'], 'integer'],
            [['update_time', 'create_time'], 'safe'],
            [['access_token', 'token', 'encoding_aes_key', 'apiUrl'], 'string', 'max' => 255],
        ];
    }

    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class' => SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => '粉丝id',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'update_time' => '更新时间',
            'create_time' => 'Create Time',
            'access_token' => 'AccessToken(访问令牌)',
            'token' => '微信服务Token(令牌)',
            'encoding_aes_key' => '消息加密秘钥EncodingAesKey',
            'apiUrl' => 'API地址',
        ];
    }
}