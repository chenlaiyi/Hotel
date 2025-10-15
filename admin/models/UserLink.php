<?php

namespace admin\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%user_link}}".
 *
 * @property int $id
 * @property int|null $user_id 管理员id
 * @property int|null $bloc_id 集团id
 * @property int|null $store_id 子公司id
 * @property int|null $status 是否启用
 * @property int|null $is_default 是否默认
 * @property int|null $link_user_id 部门
 * @property string|null $create_time
 * @property string|null $update_time
 */
class UserLink extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_link}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'bloc_id', 'store_id', 'status', 'is_default', 'link_user_id'], 'integer'],
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

    function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'link_user_id']);

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => '管理员id',
            'bloc_id' => '集团id',
            'store_id' => '子公司id',
            'status' => '是否启用',
            'is_default' => '是否默认',
            'link_user_id' => '部门',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}