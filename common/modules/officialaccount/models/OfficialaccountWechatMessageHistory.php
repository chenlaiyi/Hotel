<?php

namespace common\modules\officialaccount\models;

use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;

/**
 * 微信历史消息
 * This is the model class for table "{{%officialaccount_wechat_message_history}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property int|null $rid 相应规则ID
 * @property int|null $kid 所属关键字ID
 * @property string|null $from 请求用户ID
 * @property string|null $module 处理模块
 * @property string|null $message 消息体内容
 * @property int|null $type 发送类型
 * @property string|null $create_time
 * @property string|null $update_time
 */
class OfficialaccountWechatMessageHistory extends ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_wechat_message_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'rid', 'kid', 'type'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['from'], 'string', 'max' => 100],
            [['module'], 'string', 'max' => 50],
            [['message'], 'string', 'max' => 255]
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
            'id' => 'ID',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'rid' => '相应规则ID',
            'kid' => '所属关键字ID',
            'from' => '请求用户ID',
            'module' => '处理模块',
            'message' => '消息体内容',
            'type' => '发送类型',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}