<?php

namespace common\modules\openWeixin\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%bloc_open_wechat_user}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $user_id 用户ID
 * @property string|null $openid openid
 * @property string|null $nick_name
 * @property string|null $update_time
 * @property string|null $create_time
 */
class OpenWechatUser extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bloc_open_wechat_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'user_id'], 'integer'],
            [['openid','union_id'], 'string', 'max' => 100],
            [['update_time', 'create_time'], 'safe'],
            [['nick_name'], 'string', 'max' => 50],
            [['bloc_id','openid'], 'unique', 'targetAttribute' => ['bloc_id', 'openid']],
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
            'user_id' => '用户ID',
            'openid' => 'openid',
            'nick_name' => 'Nick Name',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}