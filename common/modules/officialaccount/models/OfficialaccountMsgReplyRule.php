<?php

namespace common\modules\officialaccount\models;

use common\traits\ActiveQuery\StoreTrait;
use Yii;

/**
 * This is the model class for table "{{%officialaccount_msg_reply_rule}}".
 *
 * @property int $rule_id
 * @property string|null $appid appid
 * @property string $rule_name 规则名称
 * @property string $match_value 匹配的关键词、事件等
 * @property int $exact_match 是否精确匹配
 * @property string $reply_type 回复消息类型
 * @property string $reply_content 回复消息内容
 * @property int $status 规则是否有效
 * @property string|null $desc 备注说明
 * @property string|null $effect_time_start
 * @property string|null $effect_time_end
 * @property int|null $priority 规则优先级
 * @property string|null $create_time
 * @property string|null $update_time
 */
class OfficialaccountMsgReplyRule extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_msg_reply_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rule_name', 'match_value', 'reply_content'], 'required'],
            [['exact_match', 'status', 'priority'], 'integer'],
            [['appid', 'rule_name', 'reply_type'], 'string', 'max' => 20],
            [['match_value'], 'string', 'max' => 200],
            [['reply_content'], 'string', 'max' => 1024],
            [['desc'], 'string', 'max' => 255],
            [['effect_time_start', 'effect_time_end', 'create_time', 'update_time'], 'string', 'max' => 30],
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
                'snow_id' => 'rule_id'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'rule_id' => 'Rule ID',
            'appid' => 'appid',
            'rule_name' => '规则名称',
            'match_value' => '匹配的关键词、事件等',
            'exact_match' => '是否精确匹配',
            'reply_type' => '回复消息类型',
            'reply_content' => '回复消息内容',
            'status' => '规则是否有效',
            'desc' => '备注说明',
            'effect_time_start' => 'Effect Time Start',
            'effect_time_end' => 'Effect Time End',
            'priority' => '规则优先级',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}