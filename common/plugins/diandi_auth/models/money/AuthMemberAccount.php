<?php

namespace common\plugins\diandi_auth\models\money;

use common\behaviors\SaveBehavior;
use common\components\ActiveRecord\YiiActiveRecord;
use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%diandi_auth_account}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string|null $create_time
 * @property string|null $update_time
 * @property int|null $account_store_id
 * @property int|null $account_cloud_id
 * @property int|null $member_id
 * @property int|null $user_id
 * @property float|null $user_money 当前余额
 * @property float|null $frozen_money 冻结金额
 * @property float|null $accumulate_money 累计余额
 */
class AuthMemberAccount extends YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_auth_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'account_store_id', 'account_cloud_id', 'member_id', 'user_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['user_money', 'frozen_money', 'accumulate_money','expensey_money','expense_money'], 'number'],
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
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'account_store_id' => 'Account Store ID',
            'account_cloud_id' => 'Account Cloud ID',
            'member_id' => 'Member ID',
            'user_id' => 'User ID',
            'user_money' => '当前余额',
            'frozen_money' => '冻结金额',
            'accumulate_money' => '累计余额',
        ];
    }
}