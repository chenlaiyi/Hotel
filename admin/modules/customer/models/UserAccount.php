<?php

namespace admin\modules\customer\models;


use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%user_account}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $store_id 商户id
 * @property int|null $user_id 用户id
 * @property int|null $level 会员等级
 * @property float|null $user_money 当前余额
 * @property float|null $accumulate_money 累计余额
 * @property float|null $give_money 累计赠送余额
 * @property float|null $consume_money 累计消费金额
 * @property float|null $frozen_money 冻结金额
 * @property int|null $user_integral 当前积分
 * @property int|null $accumulate_integral 累计积分
 * @property int|null $give_integral 累计赠送积分
 * @property float|null $consume_integral 累计消费积分
 * @property int|null $frozen_integral 冻结积分
 * @property float|null $credit1
 * @property float|null $credit2
 * @property float|null $credit3
 * @property float|null $credit4
 * @property float|null $credit5
 * @property int|null $status 状态[-1:删除;0:禁用;1启用]
 * @property  $create_time
 * @property  $update_time
 */
class UserAccount extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%customer_user_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'user_id', 'level', 'user_integral', 'accumulate_integral', 'give_integral', 'frozen_integral', 'status'], 'integer'],
            [['user_money', 'accumulate_money', 'give_money', 'consume_money', 'frozen_money', 'consume_integral', 'credit1', 'credit2', 'credit3', 'credit4', 'credit5'], 'number'],
            [['bloc_id', 'store_id', 'user_id'], 'unique', 'targetAttribute' => ['bloc_id', 'store_id', 'user_id']],
            [['create_time', 'update_time'],'safe']
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
            'store_id' => '商户id',
            'user_id' => '用户id',
            'level' => '会员等级',
            'user_money' => '当前余额',
            'accumulate_money' => '累计余额',
            'give_money' => '累计赠送余额',
            'consume_money' => '累计消费金额',
            'frozen_money' => '冻结金额',
            'user_integral' => '当前积分',
            'accumulate_integral' => '累计积分',
            'give_integral' => '累计赠送积分',
            'consume_integral' => '累计消费积分',
            'frozen_integral' => '冻结积分',
            'credit1' => 'Credit1',
            'credit2' => 'Credit2',
            'credit3' => 'Credit3',
            'credit4' => 'Credit4',
            'credit5' => 'Credit5',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}