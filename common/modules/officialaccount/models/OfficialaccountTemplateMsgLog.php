<?php

namespace common\modules\officialaccount\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%officialaccount_template_msg_log}}".
 *
 * @property int $log_id ID
 * @property string $appid appid
 * @property string|null $touser 用户openid
 * @property string|null $template_id templateid
 * @property string|null $data 消息数据
 * @property string|null $url 消息链接
 * @property string|null $miniprogram 小程序信息
 * @property string|null $send_time 发送时间
 * @property string|null $send_result 发送结果
 */
class OfficialaccountTemplateMsgLog extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_template_msg_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['appid'], 'required'],
            [['appid'], 'string', 'max' => 20],
            [['touser', 'template_id', 'send_result'], 'string', 'max' => 50],
            [['data', 'url', 'miniprogram'], 'string', 'max' => 255],
            [['send_time'], 'string', 'max' => 30],
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
                'snow_id' => 'log_id'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'log_id' => 'ID',
            'appid' => 'appid',
            'touser' => '用户openid',
            'template_id' => 'templateid',
            'data' => '消息数据',
            'url' => '消息链接',
            'miniprogram' => '小程序信息',
            'send_time' => '发送时间',
            'send_result' => '发送结果',
        ];
    }
}