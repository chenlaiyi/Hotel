<?php

namespace common\modules\officialaccount\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%officialaccount_msg_template_list}}".
 *
 * @property int $id id
 * @property int|null $bloc_id
 * @property string $template_id 公众号模板ID
 * @property string|null $url 链接地址
 * @property string|null $data 模板内容
 * @property string|null $miniprogram_appid 小程序appid
 * @property string|null $miniprogram_pagepath 小程序页面地址
 * @property int $status 是否有效
 * @property string|null $create_time
 * @property string|null $update_time
 */
class OfficialaccountMsgTemplateList extends \common\components\ActiveRecord\YiiActiveRecord
{

    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_msg_template_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'status'], 'integer'],
            [['template_id'], 'required'],
            [['data'], 'string'],
            [['template_id', 'miniprogram_appid', 'miniprogram_pagepath'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255],
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
            'id' => 'id',
            'bloc_id' => 'Bloc ID',
            'template_id' => '公众号模板ID',
            'url' => '链接地址',
            'data' => '模板内容',
            'miniprogram_appid' => '小程序appid',
            'miniprogram_pagepath' => '小程序页面地址',
            'status' => '是否有效',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}