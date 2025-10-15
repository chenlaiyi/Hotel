<?php

namespace common\plugins\diandi_auth\models\cloud;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%diandi_auth_addons_time}}".
 *
 * @property int $id
 * @property int|null $member_id 会员ID
 * @property string|null $addons 授权模块
 * @property string|null $start_time 开始时间
 * @property string|null $end_time 结束时间
 * @property string|null $domin_url 授权域名
 * @property int|null $create_time
 * @property int|null $update_time
 */
class DiandiAuthAddonsTime extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_auth_addons_time}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'member_id', 'create_time', 'update_time'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['addons'], 'string', 'max' => 50],
            [['domin_url'], 'string', 'max' => 100],
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
            'member_id' => '会员ID',
            'addons' => '授权模块',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'domin_url' => '授权域名',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}