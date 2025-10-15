<?php

namespace common\modules\officialaccount\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%officialaccount_msg}}".
 *
 * @property int $id 主键
 * @property string $appid appid
 * @property string $openid 微信用户ID
 * @property int|null $in_out 消息方向
 * @property string|null $msg_type 消息类型
 * @property string|null $detail 消息详情
 * @property string|null $create_time
 * @property string|null $update_time
 */
class OfficialaccountMsg extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_msg}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['appid', 'openid'], 'required'],
            [['in_out'], 'integer'],
            [['appid'], 'string', 'max' => 20],
            [['openid'], 'string', 'max' => 32],
            [['msg_type'], 'string', 'max' => 25],
            [['detail'], 'string', 'max' => 255],
            [['create_time', 'update_time'], 'string', 'max' => 30],
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
            'id' => '主键',
            'appid' => 'appid',
            'openid' => '微信用户ID',
            'in_out' => '消息方向',
            'msg_type' => '消息类型',
            'detail' => '消息详情',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}