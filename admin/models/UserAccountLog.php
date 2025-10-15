<?php

namespace admin\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%user_account_log}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property int|null $user_id 会员id
 * @property string|null $account_type 资金类型
 * @property float|null $old_money
 * @property float|null $money 资金
 * @property int|null $is_add 0增加，1减少
 * @property string|null $remark 备注
 * @property int|null $money_id 操作日志ID
 * @property int|null $update_time 创建时间
 * @property int|null $create_time 更新时间
 */
class UserAccountLog extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_account_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'user_id', 'is_add', 'money_id', 'update_time', 'create_time'], 'integer'],
            [['old_money', 'money'], 'number'],
            [['account_type'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 255],
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
            'store_id' => 'Store ID',
            'user_id' => '会员id',
            'account_type' => '资金类型',
            'old_money' => 'Old Money',
            'money' => '资金',
            'is_add' => '0增加，1减少',
            'remark' => '备注',
            'money_id' => '操作日志ID',
            'update_time' => '创建时间',
            'create_time' => '更新时间',
        ];
    }
}