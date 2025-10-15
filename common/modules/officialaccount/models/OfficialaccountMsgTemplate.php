<?php

namespace common\modules\officialaccount\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%officialaccount_msg_template}}".
 *
 * @property int         $id id
 * @property string      $appid appid
 * @property string      $template_id 公众号模板ID
 * @property string|null $name 模版名称
 * @property string|null $title 标题
 * @property string|null $content 模板内容
 * @property string|null $example 消息内容
 * @property string|null $url 链接
 * @property string|null $miniprogram 小程序信息
 * @property int         $status 是否有效
 * @property string|null $create_time
 * @property string|null $update_time
 */
class OfficialaccountMsgTemplate extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_msg_template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['template_id', 'status'], 'required'],
            [['content'], 'string'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 20],
            [['template_id'], 'string', 'max' => 100],
            [['example', 'remark'], 'string', 'max' => 255],
            [['sign'], 'string', 'max' => 128],
            [['create_time', 'update_time'], 'string', 'max' => 30],
            [['template_id'], 'unique'],
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
                'class'            => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type'        => 'datetime',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'id',
            'appid'       => 'appid',
            'template_id' => '公众号模板ID',
            'name'        => '模版名称',
            'title'       => '标题',
            'content'     => '模板内容',
            'example'     => '消息内容',
            'status'      => '是否有效',
            'sign'        => '标识',
            'remark'      => '使用说明',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}