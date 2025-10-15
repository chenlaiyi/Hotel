<?php

namespace common\modules\openWeixin\models;

use common\components\ActiveRecord\YiiActiveRecord;
use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%bloc_open_wechat_token}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property string|null $authorizer_appid authorizer_appid
 * @property string|null $authorizer_access_token
 * @property int|null $expires_in
 * @property string|null $authorizer_refresh_token
 * @property string|null $func_info
 * @property int|null $update_time
 * @property int|null $create_time
 */
class BlocOpenWechatToken extends YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bloc_open_wechat_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id','store_id', 'expires_in','service_type_id', 'verify_type_id'], 'integer'],
            [['authorizer_appid', 'authorizer_access_token', 'authorizer_refresh_token','qrcode_url'], 'string', 'max' => 255],
            [['nick_name'], 'string', 'max' => 50],
            [['func_info','update_time', 'create_time'], 'safe'],
            [['bloc_id','service_type_id','verify_type_id'], 'unique', 'targetAttribute' => ['bloc_id','service_type_id','verify_type_id']],
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
                'class' => \common\behaviors\SaveBehavior::className(),
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
            'id' => 'ID',
            'bloc_id' => 'Bloc ID',
            'authorizer_appid' => 'authorizer_appid',
            'authorizer_access_token' => 'Authorizer Access Token',
            'expires_in' => 'Expires In',
            'authorizer_refresh_token' => 'Authorizer Refresh Token',
            'func_info' => 'Func Info',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}